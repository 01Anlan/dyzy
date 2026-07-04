<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp;
use AlibabaCloud\Oss\V2\Defaults;
use AlibabaCloud\Oss\V2\Models;

/**
 * Copier for copy objects.
 * Class Copier
 * @package AlibabaCloud\Oss\V2
 */
final class Copier
{

    /**
     * The client instance.
     * @var Client
     */
    private $client;

    /**
     * Copy options
     * @var array<string,mixed>
     */
    private $options;

    /**
     * @var array Default values for copy configuration.
     */
    private $defaultConfig = [
        'part_size' => Defaults::DEFAULT_COPY_PART_SIZE,
        'parallel_num' => Defaults::DEFAULT_COPY_PARALLEL,
        'multipart_copy_threshold' => Defaults::DEFAULT_COPY_THRESHOLD,
        'leave_parts_on_error' => false,
        'disable_shallow_copy' => false,
    ];

    private const MetadataCopied = [
        'content-type',
        'content-language',
        'content-encoding',
        'content-disposition',
        'cache-control',
        'expires',
    ];

    /**
     * Copier constructor.
     * @param Client $client The client instance.
     * @param array $args accepts the following:
     * - part_size int: The part size. Default value: 64 MiB.
     * - parallel_num int: The number of the upload tasks in parallel. Default value: 3.
     * - multipart_copy_threshold int: The minimum object size for calling the multipart copy operation.
     *   Default value: 200 MiB.
     * - leave_parts_on_error bool: Specifies whether to retain the uploaded parts when an upload task fails.
     *   By default, the uploaded parts are not retained.
     * - disable_shallow_copy bool: Specifies that the shallow copy capability is not used.
     *   By default, the shallow copy capability is used.
     * @return Copier
     */
    public function __construct(
        $client,
        array $args = []
    )
    {
        $this->client = $client;
        $this->options = $this->filterArgs($args) + $this->defaultConfig;
    }

    /**
     * copy object
     * @param Models\CopyObjectRequest $request
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     */
    public function copyAsync(
        Models\CopyObjectRequest $request,
        array $args = []
    ): GuzzleHttp\Promise\Promise
    {
        $context = [];
        $this->checkArgs($context, $request, $args);

        return $this->doCopy($context);
    }

    /**
     * copy object
     * @param Models\CopyObjectRequest $request
     * @param array $args
     * @return Models\CopyResult
     */
    public function copy(
        Models\CopyObjectRequest $request,
        array $args = []
    ): Models\CopyResult
    {
        return $this->copyAsync($request, $args)->wait();
    }

    private function checkArgs(array &$context, Models\CopyObjectRequest &$request, array &$args): array
    {
        $context = $this->filterArgs($args) + $this->options;

        if (!Validation::isValidBucketName(Utils::safetyString($request->bucket))) {
            throw new \InvalidArgumentException('invalid field, request.bucket.');
        }

        if (!Validation::isValidObjectName(Utils::safetyString($request->key))) {
            throw new \InvalidArgumentException('invalid field, request.key.');
        }

        if (!Validation::isValidObjectName(Utils::safetyString($request->sourceKey))) {
            throw new \InvalidArgumentException('invalid field, request.sourceKey.');
        }

        if ($request->metadataDirective != null && !in_array($request->metadataDirective, ['COPY', 'REPLACE'])) {
            throw new \InvalidArgumentException('invalid field, request.metadataDirective.');
        }

        if ($request->taggingDirective != null && !in_array($request->taggingDirective, ['COPY', 'REPLACE'])) {
            throw new \InvalidArgumentException('invalid field, request.taggingDirective.');
        }

        if (Utils::safetyInt($context['part_size']) <= 0) {
            $context['part_size'] = Defaults::DEFAULT_COPY_PART_SIZE;
        }

        if (Utils::safetyInt($context['parallel_num']) <= 0) {
            $context['parallel_num'] = Defaults::DEFAULT_COPY_PARALLEL;
        }

        if (Utils::safetyInt($context['multipart_copy_threshold']) < 0) {
            $context['multipart_copy_threshold'] = Defaults::DEFAULT_COPY_THRESHOLD;
        }

        $context['request'] = $request;
        $context['path'] = sprintf('oss://%s/%s', $request->bucket, $request->key);

        //meta_prop & tag_prop
        if (isset($args['meta_prop']) && $args['meta_prop'] instanceof Models\HeadObjectResult) {
            $context['total_size'] = $args['meta_prop']->contentLength;
            $context['meta_prop'] = $args['meta_prop'];
        }

        return $context;
    }

    private function doCopy(array &$context): GuzzleHttp\Promise\Promise
    {
        return GuzzleHttp\Promise\Coroutine::of(function () use (&$context) {

            // get object meta
            if (!isset($context['meta_prop'])) {
                /**
                 * @var Models\CopyObjectRequest $sourceRequest 
                 */                
                $sourceRequest = $context['request'];
                $request = new Models\HeadObjectRequest();
                Utils::copyRequest($request, $sourceRequest);
                if ($sourceRequest->sourceBucket != null) {
                    $request->bucket = $sourceRequest->sourceBucket;
                }
                $request->key = $sourceRequest->sourceKey;
                $request->versionId = $sourceRequest->sourceVersionId;
                yield $this->client->headObjectAsync($request)->then(
                    function (Models\HeadObjectResult $result) use (&$context) {
                        $context['total_size'] = $result->contentLength;
                        $context['meta_prop'] = $result;
                    },
                );
            }

            self::adjustPartsize($context);

            if ($context['total_size'] <= $context['multipart_copy_threshold']) {
                yield $this->singleCopy($context);
            } else if (self::canUseShallowCopy($context)) {
                // Use singleCopy first, 
                // and then use multipartCopy if you encounter an timeout error
                $ctx_errors = [];
                yield $this->singleCopy($context)->otherwise(
                    function ($reason) use (&$ctx_errors) {
                        $ctx_errors[] = $reason;
                    },
                );

                if (!empty($ctx_errors)) {
                    //TODO yield $this->multipartCopy($context);
                    throw end($ctx_errors);
                }
            } else {
                yield $this->multipartCopy($context);
            }
        })->then(
            function ($result) use (&$context) {
                return $context['copy_result'];
            },
            function ($reason) use (&$context) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\CopyException(
                    $context['upload_id'] ?? '',
                    $context['path'] ?? '',
                    $reason
                ));
            }
        );
    }

    private function singleCopy(array &$context): GuzzleHttp\Promise\Promise
    {
        $request = $context['request'];
        return $this->client->copyObjectAsync($request)->then(
            function (Models\CopyObjectResult $result) use (&$context) {
                $res = new Models\CopyResult();
                Utils::copyResult($res, $result);
                $context['copy_result'] = $res;
                return $res;
            }
        );
    }

    private static function canUseShallowCopy(array &$context)
    {
        if ($context['disable_shallow_copy'] === true) {
            return false;
        }

        /**
         * @var Models\CopyObjectRequest $request
         * @var Models\CopyObjectResult $metaProp
         */
        $request = $context['request'];
        $metaProp = $context['meta_prop'];

        // change StorageClass
        if ($request->storageClass != null) {
            return false;
        }

        // Cross bucket
        if (
            $request->sourceBucket != null &&
            $request->sourceBucket !== $request->bucket
        ) {
            return false;
        }

        // Decryption
        if ($metaProp->serverSideEncryption != null) {
            return false;
        }

        return true;
    }

    private static function adjustPartsize(array &$context)
    {
        $total_size = $context['total_size'];
        $part_size = $context['part_size'];
        if ($total_size > 0) {
            while ($total_size / $part_size >= Defaults::MAX_UPLOAD_PARTS) {
                $part_size += $context['part_size'];
            }
        }
        $context['part_size'] = $part_size;
    }

    private static function iterPart(array &$context): \Generator
    {
        $total_size = $context['total_size'];
        $part_size = $context['part_size'];
        $start = 0;

        for (
            $partNumber = 1;
            $start < $total_size;
            $partNumber++
        ) {

            yield [$partNumber, $start, min($part_size, $total_size - $start)];
            $start += $part_size;
        }
    }

    private static function overwirteMetadataProp(Models\InitiateMultipartUploadRequest &$imRequest, array &$context)
    {
        /**
         * @var Models\CopyObjectRequest $request
         * @var Models\CopyObjectResult $metaProp
         */
        $request = $context['request'];
        $metaProp = $context['meta_prop'];
        $directive = $request->metadataDirective ?? '';

        switch (strtolower($directive)) {
            case '':
            case 'copy':
                if ($metaProp == null) {
                    throw new \InvalidArgumentException("request.metadataDirective is COPY, but meets nil metaProp for source");
                }
                $imRequest->cacheControl = null;
                $imRequest->contentType = null;
                $imRequest->contentDisposition = null;
                $imRequest->contentEncoding = null;
                $imRequest->expires = null;
                $imRequest->metadata = null;
                $imRequest->expires = null;
                $imHeaders = [];

                // copy meta form source
                foreach ($metaProp->headers as $k => $v) {
                    $lk = strtolower($k);
                    if (strncmp($lk, 'x-oss-meta', 10) == 0) {
                        $imHeaders[$k] = $v;
                    } else if (in_array($lk, self::MetadataCopied)) {
                        $imHeaders[$k] = $v;
                    }
                }
                $imRequestRo = new \ReflectionObject($imRequest);
                $pp = $imRequestRo->getProperty('headers');
                if (PHP_VERSION_ID < 80100) {
                    $pp->setAccessible(true);
                }
                $pp->setValue($imRequest, $imHeaders);

                break;
            case 'replace':
                // the metedata has been copied via the copyRequest function before
                break;
            default:
                //unsupport Directive
                throw new \InvalidArgumentException("Unsupport MetadataDirective, $directive");
        }
    }

    private static function needFetchTagProp(array &$context)
    {
        if (isset($context['tag_prop'])) {
            return false;
        }

        /**
         * @var Models\CopyObjectRequest $request
         * @var Models\CopyObjectResult $metaProp
         */
        $request = $context['request'];
        $metaProp = $context['meta_prop'];

        if (Utils::safetyInt($metaProp->taggingCount) <= 0) {
            return false;
        }

        $directive = strtolower($request->taggingDirective ?? '');

        return $directive === '' || $directive === 'copy';
    }

    private static function overwirteTagProp(Models\InitiateMultipartUploadRequest &$imRequest, array &$context)
    {
        $request = $context['request'];
        $tagProp = $context['tag_prop'] ?? null;
        /**
         * @var Models\CopyObjectRequest $request
         * @var Models\GetObjectTaggingResult $tagProp
         */
        $directive = $request->taggingDirective ?? '';

        switch (strtolower($directive)) {
            case '':
            case 'copy':
                $imRequest->tagging = null;
                if ($tagProp != null && $tagProp->tagSet != null) {
                    $tags = [];
                    foreach ($tagProp->tagSet->tags ?? [] as $tag) {
                        $tags[] = sprintf('%s=%s', $tag->key, $tag->value);
                    }

                    if (!empty($tags)) {
                        $imRequest->tagging = implode('&', $tags);
                    }
                }
                break;
            case 'replace':
                // the tagging has been copied via the copyRequest function before
                break;
            default:
                //unsupport Directive
                throw new \InvalidArgumentException("Unsupport TaggingDirective, $directive");
        }
    }

    private function multipartCopy(array &$context): GuzzleHttp\Promise\Promise
    {
        return GuzzleHttp\Promise\Coroutine::of(function () use (&$context) {

            // tag prop
            if (self::needFetchTagProp($context)) {
                /**
                 * @var Models\CopyObjectRequest $sourceRequest
                 */
                $sourceRequest = $context['request'];
                $request = new Models\GetObjectTaggingRequest();
                Utils::copyRequest($request, $sourceRequest);
                if ($sourceRequest->sourceBucket != null) {
                    $request->bucket = $sourceRequest->sourceBucket;
                }
                $request->key = $sourceRequest->sourceKey;
                $request->versionId = $sourceRequest->sourceVersionId;
                yield $this->client->getObjectTaggingAsync($request)->then(
                    function (Models\GetObjectTaggingResult $result) use (&$context) {
                        $context['tag_prop'] = $result;
                    },
                );
            }

            // init the multipart
            $request = new Models\InitiateMultipartUploadRequest();
            Utils::copyRequest($request, $context['request']);
            $request->disableAutoDetectMimeType = true;
            self::overwirteMetadataProp($request, $context);
            self::overwirteTagProp($request, $context);
            yield $this->client->initiateMultipartUploadAsync($request)->then(
                function (Models\InitiateMultipartUploadResult $result) use (&$context) {
                    $context['upload_id'] = $result->uploadId;
                },
            );

            // timeout for MultiPartCopy API, 10s per 200M, max timeout is 50s
            $PART_SIZE = 200 * 1024 * 1024;
            $STEP = 10;
            $mpcReadTimeout = Defaults::READWRITE_TIMEOUT;
            $part_size = intval($context['part_size']);
            while ($part_size > $PART_SIZE) {
                $mpcReadTimeout += $STEP;
                $part_size -= $PART_SIZE;
                if ($mpcReadTimeout > 50) {
                    break;
                }
            }
            $context['mpc_read_timeout'] = $mpcReadTimeout;

            // upload part
            $context['errors'] = [];
            $context['parts'] = [];
            $uploadFns = function () use (&$context) {
                foreach (self::iterPart($context) as $args) {
                    $request = new Models\UploadPartCopyRequest();
                    Utils::copyRequest($request, $context['request']);
                    $request->partNumber = $args[0];
                    $request->uploadId = $context['upload_id'];
                    $request->sourceRange = sprintf('bytes=%d-%d', $args[1], $args[1] + $args[2] - 1);
                    yield $args[0] => $this->client->uploadPartCopyAsync($request)->otherwise(
                        function ($reason) use (&$context) {
                            $context['errors'][] = $reason;
                            return GuzzleHttp\Promise\Create::rejectionFor($reason);
                        },
                    );
                    if (!empty($context['errors'])) {
                        break;
                    }
                }
            };

            $each = new GuzzleHttp\Promise\EachPromise(
                $uploadFns(),
                [
                    'concurrency' => $context['parallel_num'],
                    'fulfilled' => function (Models\UploadPartCopyResult $result, $key) use (&$context) {
                        $context['parts'][] = new Models\UploadPart($key, $result->etag);
                        return $result;
                    }
                ]
            );
            yield $each->promise();

            // complete upload
            if (empty($context['errors'])) {
                $parts = $context['parts'];
                usort($parts, function ($a, $b) {
                    if ($a->partNumber == $b->partNumber) return 0;
                    return $a->partNumber < $b->partNumber ? -1 : 1;
                });

                $request = new Models\CompleteMultipartUploadRequest();
                Utils::copyRequest($request, $context['request']);
                $request->uploadId = $context['upload_id'];
                $request->completeMultipartUpload = new Models\CompleteMultipartUpload($parts);
                yield $this->client->completeMultipartUploadAsync($request)->then(
                    function (Models\CompleteMultipartUploadResult $result) use (&$context) {
                        $context['upload_result'] = $result;
                        return $result;
                    },
                    function ($reason) use (&$context) {
                        $context['errors'][] = $reason;
                    },
                );
            }

            if (!empty($context['errors'])) {
                if ($context['leave_parts_on_error'] === false) {
                    $request = new Models\AbortMultipartUploadRequest();
                    Utils::copyRequest($request, $context['request']);
                    $request->uploadId = $context['upload_id'];
                    yield $this->client->abortMultipartUploadAsync($request);
                }
                throw end($context['errors']);
            }
        })->then(
            function ($result) use (&$context) {
                $result = $context['upload_result'];
                $res = new Models\CopyResult();
                Utils::copyResult($res, $result);
                $res->uploadId = $context['upload_id'];
                $context['copy_result'] = $res;
                return $res;
            },
            function ($reason) use (&$context) {
                return GuzzleHttp\Promise\Create::rejectionFor($reason);
            }
        );
    }

    private function filterArgs(array &$args)
    {
        return \array_filter($args, function ($key) {
            return \array_key_exists($key, $this->defaultConfig);
        }, \ARRAY_FILTER_USE_KEY);
    }
}
