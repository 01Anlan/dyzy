<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use \Psr\Http\Message\StreamInterface;
use GuzzleHttp;
use AlibabaCloud\Oss\V2\Models;

/**
 * Uploader for handling objects for uploads
 */
final class Uploader
{

    /**
     * The oss client.
     * @var Client|EncryptionClient
     */
    private $client;

    /**
     * Upload options
     * @var array<string,mixed>
     */
    private $options;

    /**
     * @var array Default values for upload configuration.
     */
    private $defaultConfig = [
        'part_size' => Defaults::DEFAULT_UPLOAD_PART_SIZE,
        'parallel_num' => Defaults::DEFAULT_UPLOAD_PARALLEL,
        'leave_parts_on_error' => false,
    ];


    /**
     * Is or not EncryptionClient
     * @var bool|null
     */
    private ?bool $isEncryptionClient;

    /**
     * Uploader constructor.
     * @param Client|EncryptionClient $client The client instance.
     * @param array $args accepts the following:
     * - part_size int: The part size. Default value: 6 MiB.
     * - parallel_num int: The number of the upload tasks in parallel. Default value: 3.
     * - leave_parts_on_error bool: Specifies whether to retain the uploaded parts when an upload task fails.
     *                              By default, the uploaded parts are not retained.
     */
    public function __construct(
        $client,
        array $args = []
    )
    {
        $this->client = $client;
        if ($this->client instanceof Client) {
            $this->isEncryptionClient = false;
        } else {
            $this->isEncryptionClient = true;
        }
        $this->options = $this->filterArgs($args) + $this->defaultConfig;
    }

    /**
     * upload file
     * @param Models\PutObjectRequest $request
     * @param string $filepath
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     */
    public function uploadFileAsync(
        Models\PutObjectRequest $request,
        string $filepath,
        array $args = []
    ): GuzzleHttp\Promise\Promise
    {
        $context = [];
        $this->checkArgs($context, $request, $args);
        $this->checkFilepath($context, $filepath);
        $this->applySource($context);

        return $this->upload($context);
    }

    /**
     * upload file
     * @param Models\PutObjectRequest $request
     * @param string $filepath
     * @param array $args
     * @return Models\UploadResult
     */
    public function uploadFile(
        Models\PutObjectRequest $request,
        string $filepath,
        array $args = []
    ): Models\UploadResult
    {
        return $this->uploadFileAsync($request, $filepath, $args)->wait();
    }


    /**
     * upload stream
     * @param Models\PutObjectRequest $request
     * @param StreamInterface $stream
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     */
    public function uploadFromAsync(
        Models\PutObjectRequest $request,
        StreamInterface $stream,
        array $args = []
    ): GuzzleHttp\Promise\Promise
    {
        $context = [];
        $this->checkArgs($context, $request, $args);
        $context['stream'] = $stream;
        $this->applySource($context);

        return $this->upload($context);
    }

    /**
     * upload stream
     * @param Models\PutObjectRequest $request
     * @param StreamInterface $stream
     * @param array $args
     * @return Models\UploadResult
     */
    public function uploadFrom(
        Models\PutObjectRequest $request,
        StreamInterface $stream,
        array $args = []
    ): Models\UploadResult
    {
        return $this->uploadFromAsync($request, $stream, $args)->wait();
    }

    private function checkArgs(array &$context, Models\PutObjectRequest &$request, array &$args): array
    {
        if (!Validation::isValidBucketName(Utils::safetyString($request->bucket))) {
            throw new \InvalidArgumentException('invalid field, request.bucket.');
        }

        if (!Validation::isValidObjectName(Utils::safetyString($request->key))) {
            throw new \InvalidArgumentException('invalid field, request.key.');
        }

        $context = $this->filterArgs($args) + $this->options;

        if (Utils::safetyInt($context['part_size']) <= 0) {
            $context['part_size'] = Defaults::DEFAULT_PART_SIZE;
        }

        if (Utils::safetyInt($context['parallel_num']) <= 0) {
            $context['parallel_num'] = Defaults::DEFAULT_UPLOAD_PARALLEL;
        }

        $context['request'] = $request;

        return $context;
    }

    private function checkFilepath(array &$context, string $filepath)
    {
        if ($filepath == '') {
            throw new \InvalidArgumentException('invalid field, filepath.');
        }

        $absfilepath = realpath($filepath);

        if (!is_file($absfilepath)) {
            throw new \InvalidArgumentException("File not exists, $filepath");
        }

        if (!is_readable($absfilepath)) {
            throw new \InvalidArgumentException("File is not readable, $filepath");
        }

        $stat = stat($absfilepath);

        if ($stat === false) {
            throw new \InvalidArgumentException("stat fail, $filepath");
        }

        $stream = new GuzzleHttp\Psr7\LazyOpenStream($absfilepath, 'rb');
        $stream->seek(0);

        $context['filepath'] = $filepath;
        $context['abs_filepath'] = $absfilepath;
        $context['file_stat'] = $stat;
        $context['stream'] = $stream;
    }

    private function applySource(array &$context)
    {
        $total_size = $context['stream']->getSize() ?? -1;
        $part_size = $context['part_size'];
        if ($total_size > 0) {
            while ($total_size / $part_size >= Defaults::MAX_UPLOAD_PARTS) {
                $part_size += $context['part_size'];
            }
        }

        $context['total_size'] = $total_size;
        $context['part_size'] = $part_size;
    }

    private function upload(array &$context): GuzzleHttp\Promise\Promise
    {
        if ($context['total_size'] >= 0 && $context['total_size'] < $context['part_size']) {
            return $this->singlePart($context);
        }

        return $this->multiPart($context);
    }

    private function singlePart(array &$context): GuzzleHttp\Promise\Promise
    {
        $request = clone $context['request'];
        $request->body = $context['stream'];
        if (empty($request->contentType)) {
            $request->contentType = $this->get_content_type($context);
        }
        return $this->client->putObjectAsync($request)->then(
            function (Models\PutObjectResult $result) {
                $res = new Models\UploadResult();
                Utils::copyResult($res, $result);
                return $res;
            },
            function ($reason) use ($context) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\UploadException(
                    $context['upload_id'] ?? '',
                    $context['filepath'] ?? '',
                    $reason
                ));
            }
        );
    }

    private static function iterPart(array &$context): \Generator
    {
        $source = $context['stream'];
        $seekable = $source->isSeekable() && $source->getMetadata('wrapper_type') === 'plainfile';
        $part_size = intval($context['part_size']);

        for (
            $partNumber = 1;
            $seekable ? $source->tell() < $source->getSize() : !$source->eof();
            $partNumber++
        ) {
            if ($seekable) {
                $body = new GuzzleHttp\Psr7\LimitStream(
                    new GuzzleHttp\Psr7\LazyOpenStream($source->getMetadata('uri'), 'rb'),
                    $part_size,
                    $source->tell()
                );
                $source->seek(min($source->tell() + $part_size, $source->getSize()));
            } else {
                $body = GuzzleHttp\Psr7\Utils::streamFor();
                GuzzleHttp\Psr7\Utils::copyToStream(
                    new GuzzleHttp\Psr7\LimitStream($source, $part_size, $source->tell()),
                    $body
                );
            }

            yield [$partNumber, $body];
        }
    }

    private function multiPart(array &$context): GuzzleHttp\Promise\Promise
    {
        return GuzzleHttp\Promise\Coroutine::of(function () use (&$context) {

            // init the multipart
            $request = new Models\InitiateMultipartUploadRequest();
            Utils::copyRequest($request, $context['request']);
            if ($this->isEncryptionClient) {
                $request->csePartSize = $context['part_size'];
                $request->cseDataSize = $context['total_size'];
            }
            if (empty($imRequest->contentType)) {
                $request->contentType = $this->get_content_type($context);
            }
            yield $this->client->initiateMultipartUploadAsync($request)->then(
                function (Models\InitiateMultipartUploadResult $result) use (&$context) {
                    $context['upload_id'] = $result->uploadId;
                    if ($this->isEncryptionClient) {
                        $context['encryption_multi_part_context'] = $result->encryptionMultipartContext;
                    }
                },
            );

            // upload part
            $context['errors'] = [];
            $context['parts'] = [];
            $uploadFns = function () use (&$context) {
                foreach (self::iterPart($context) as $args) {
                    $request = new Models\UploadPartRequest();
                    Utils::copyRequest($request, $context['request']);
                    $request->partNumber = $args[0];
                    $request->uploadId = $context['upload_id'];
                    $request->body = $args[1];
                    if ($this->isEncryptionClient) {
                        $request->encryptionMultipartContext = $context['encryption_multi_part_context'];
                    }
                    yield $args[0] => $this->client->uploadPartAsync($request)->otherwise(
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
                    'fulfilled' => function (Models\UploadPartResult $result, $key) use (&$context) {
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
                $res = new Models\UploadResult();
                Utils::copyResult($res, $result);
                $res->uploadId = $context['upload_id'];
                return $res;
            },
            function ($reason) use (&$context) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\UploadException(
                    $context['upload_id'] ?? '',
                    $context['filepath'] ?? '',
                    $reason
                ));
            }
        );
    }

    private function filterArgs(array &$args)
    {
        return \array_filter($args, function ($key) {
            return \array_key_exists($key, $this->defaultConfig);
        }, \ARRAY_FILTER_USE_KEY);
    }

    private function get_content_type(array &$context): ?string
    {
        if (isset($context['abs_filepath'])) {
            return Utils::guessContentType($context['abs_filepath'], 'application/octet-stream');
        }
        return null;
    }
}
