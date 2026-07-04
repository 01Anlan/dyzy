<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp;
use AlibabaCloud\Oss\V2\Models;

/**
 * Downloader for handling objects for downloads.
 * Class Downloader
 * @package AlibabaCloud\Oss\V2
 */
final class Downloader
{

    /**
     * @var Client|EncryptionClient The client instance.
     */
    private $client;

    /**
     * download options
     * @var array<string,mixed>
     */
    private $options;

    /**
     * @var array Default values for download configuration.
     */
    private $defaultConfig = [
        'part_size' => Defaults::DEFAULT_DOWNLOAD_PART_SIZE,
        'parallel_num' => Defaults::DEFAULT_DOWNLOAD_PARALLEL,
        'use_temp_file' => false,
    ];

    /**
     * Downloader constructor.
     * @param Client|EncryptionClient $client The client instance.
     * @param array $args accepts the following:
     * - part_size int: The part size. Default value: 6 MiB.
     * - parallel_num int: The number of the upload tasks in parallel. Default value: 3.
     * - use_temp_file bool: Specifies whether to use a temporary file when you download an object.
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
     * download file
     * @param Models\GetObjectRequest $request
     * @param string $filepath
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     */
    public function downloadFileAsync(
        Models\GetObjectRequest $request,
        string $filepath,
        array $args = []
    ): GuzzleHttp\Promise\Promise
    {
        $context = [];
        $this->checkArgs($context, $request, $args);
        $this->checkFilepath($context, $filepath);
        return $this->writeParallel($context);
    }

    /**
     * download file
     * @param Models\GetObjectRequest $request
     * @param string $filepath
     * @param array $args
     * @return Models\DownloadResult
     */
    public function downloadFile(
        Models\GetObjectRequest $request,
        string $filepath,
        array $args = []
    ): Models\DownloadResult
    {
        return $this->downloadFileAsync($request, $filepath, $args)->wait();
    }

    /**
     * download to
     * @param Models\GetObjectRequest $request
     * @param \Psr\Http\Message\StreamInterface $stream
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     */
    public function downloadToAsync(
        Models\GetObjectRequest $request,
        \Psr\Http\Message\StreamInterface $stream,
        array $args = []
    ): GuzzleHttp\Promise\Promise
    {
        $context = [];
        $this->checkArgs($context, $request, $args);
        $this->checkStream($context, $stream);

        if ($stream->isSeekable() && $stream->getMetadata('wrapper_type') === 'plainfile') {
            return $this->writeParallel($context);
        }

        return $this->writeSequential($context);
    }

    /**
     * download to
     * @param Models\GetObjectRequest $request
     * @param \Psr\Http\Message\StreamInterface $stream
     * @param array $args
     * @return Models\DownloadResult
     */
    public function downloadTo(
        Models\GetObjectRequest $request,
        \Psr\Http\Message\StreamInterface $stream,
        array $args = []
    ): Models\DownloadResult
    {
        return $this->downloadToAsync($request, $stream, $args)->wait();
    }

    private function checkArgs(array &$context, Models\GetObjectRequest &$request, array &$args): array
    {
        $context = $this->filterArgs($args) + $this->options;

        if (!Validation::isValidBucketName(Utils::safetyString($request->bucket))) {
            throw new \InvalidArgumentException('invalid field, request.bucket.');
        }

        if (!Validation::isValidObjectName(Utils::safetyString($request->key))) {
            throw new \InvalidArgumentException('invalid field, request.key.');
        }

        // range
        $pos = 0;
        $epos = -1;
        if ($request->rangeHeader != null) {
            if ($this->client instanceof EncryptionClient) {
                throw new \InvalidArgumentException("encryption client does not support range.");
            }
            $vals = Utils::parseHttpRange($request->rangeHeader);
            if ($vals === false) {
                throw new \InvalidArgumentException("invalid field, request.rangeHeader.");
            }
            if ($vals[0] > 0) {
                $pos = $vals[0];
            }
            if ($vals[1] > 0) {
                $epos = $vals[1] + 1;
            }
        }
        $context['pos'] = $pos;
        $context['epos'] = $epos;

        if (Utils::safetyInt($context['part_size']) <= 0) {
            $context['part_size'] = Defaults::DEFAULT_PART_SIZE;
        }

        if (Utils::safetyInt($context['parallel_num']) <= 0) {
            $context['parallel_num'] = Defaults::DEFAULT_DOWNLOAD_PARALLEL;
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

        if ($absfilepath === false) {
            $absfilepath = $filepath;
        }

        $stream = new GuzzleHttp\Psr7\LazyOpenStream($absfilepath, 'w');
        if (!$stream->isWritable()) {
            throw new \InvalidArgumentException("File is not writable, $filepath");
        }
        $context['filepath'] = $filepath;
        $context['abs_filepath'] = $absfilepath;
        $context['stream'] = $stream;
    }

    private function checkStream(array &$context, \Psr\Http\Message\StreamInterface $stream)
    {
        if (!$stream->isWritable()) {
            throw new \InvalidArgumentException('Stream is not writable');
        }
        $context['filepath'] = 'to stream';
        $context['abs_filepath'] = 'to stream';
        $context['stream'] = $stream;
    }

    /**
     * Summary of iterPart
     * @param array $context
     * @return \Generator<array[int, int]>
     */
    private static function iterPart(array &$context): \Generator
    {
        $start = $context['pos'];
        $end = $context['epos'];
        $part_size = $context['part_size'];
        while ($start < $end) {
            yield [$start, min($part_size, $end - $start)];
            $start += $part_size;
        }
    }

    private static function drainPart(array &$context)
    {
        /**
         * @var \Psr\Http\Message\StreamInterface $stream
         * @var int $rstart
         */
        $stream = $context['stream'];
        $wstart = $context['wstart'];

        usort($context['parts'], function ($a, $b) {
            if ($a['start'] == $b['start']) return 0;
            return $a['start'] < $b['start'] ? -1 : 1;
        });

        while (!empty($context['parts'])) {
            $item = $context['parts'][0];
            if ($item['start'] !== $wstart) {
                break;
            }
            /**
             * @var Models\GetObjectResult $result
             */
            $result = $item['result'];
            GuzzleHttp\Psr7\Utils::copyToStream($result->body, $stream);
            $wstart += $result->body->getSize();
            array_shift($context['parts']);
        }
        $context['wstart'] = $wstart;
    }

    private function writeSequential(array &$context): GuzzleHttp\Promise\Promise
    {
        return GuzzleHttp\Promise\Coroutine::of(function () use (&$context) {
            // get object meta
            $request = new Models\HeadObjectRequest();
            Utils::copyRequest($request, $context['request']);
            yield $this->client->headObjectAsync($request)->then(
                function (Models\HeadObjectResult $result) use (&$context) {
                    $pos = $context['epos'];
                    $context['epos'] = $pos < 0 ? $result->contentLength : min($pos, $result->contentLength);
                },
            );

            // download parallel & write sequential
            $context['errors'] = [];
            $context['parts'] = [];
            $context['wstart'] = $context['pos'];

            $downloadFns = function () use (&$context) {
                foreach (self::iterPart($context) as $args) {
                    /**
                     * @var Models\GetObjectRequest $request
                     */
                    $request = clone $context['request'];
                    $request->rangeHeader = sprintf('bytes=%d-%d', $args[0], $args[0] + $args[1] - 1);
                    $request->rangeBehavior = 'standard';
                    $request->progressFn = null;
                    yield $this->client->getObjectAsync($request)->otherwise(
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
                $downloadFns(),
                [
                    'concurrency' => $context['parallel_num'],
                    'fulfilled' => function (Models\GetObjectResult $result, $key) use (&$context) {
                        $rangeStart = $result->contentRange != null ? Utils::parseContentRange($result->contentRange) : 0;
                        $rangeStart = \is_array($rangeStart) ? $rangeStart[0] : 0;
                        $context['parts'][] = ['start' => $rangeStart, 'result' => $result];
                        self::drainPart($context);
                        return $result;
                    }
                ]
            );
            yield $each->promise();

            // remains parts
            self::drainPart($context);

            if (!empty($context['errors'])) {
                throw end($context['errors']);
            }

            if (!empty($context['parts'])) {
                throw new \RuntimeException('There are still unsaved parts.');
            }
        })->then(
            function ($result) use (&$context) {
                $res = new Models\DownloadResult();
                $res->written = $context['epos'] - $context['pos'];
                return $res;
            },
            function ($reason) use (&$context) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\DownloadException(
                    $context['filepath'] ?? '',
                    $reason
                ));
            }
        );
    }

    private function writeParallel(array &$context): GuzzleHttp\Promise\Promise
    {
        return GuzzleHttp\Promise\Coroutine::of(function () use (&$context) {

            // get object meta
            $request = new Models\HeadObjectRequest();
            Utils::copyRequest($request, $context['request']);
            yield $this->client->headObjectAsync($request)->then(
                function (Models\HeadObjectResult $result) use (&$context) {
                    $pos = $context['epos'];
                    $context['epos'] = $pos < 0 ? $result->contentLength : min($pos, $result->contentLength);
                },
            );

            // download & write parallel
            $context['errors'] = [];
            $context['parts'] = [];

            $downloadFns = function () use (&$context) {
                /**
                 * @var \Psr\Http\Message\StreamInterface $stream
                 * @var int $rstart
                 */
                $stream = $context['stream'];
                $rstart = $context['pos'];
                foreach (self::iterPart($context) as $args) {
                    /**
                     * @var Models\GetObjectRequest $request
                     */
                    $request = clone $context['request'];
                    $request->rangeHeader = sprintf('bytes=%d-%d', $args[0], $args[0] + $args[1] - 1);
                    $request->rangeBehavior = 'standard';
                    $request->progressFn = null;
                    $offset = $stream->tell() + $args[0] - $rstart;
                    $sink = new GuzzleHttp\Psr7\LimitStream(
                        new GuzzleHttp\Psr7\LazyOpenStream($stream->getMetadata('uri'), 'rb+'),
                        -1,
                        $offset
                    );
                    yield $this->client->getObjectAsync(
                        $request,
                        [
                            'request_options' => [
                                'sink' => $sink
                            ]
                        ]
                    )->otherwise(
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
                $downloadFns(),
                [
                    'concurrency' => $context['parallel_num'],
                ]
            );
            yield $each->promise();

            if (!empty($context['errors'])) {
                throw end($context['errors']);
            }
        })->then(
            function ($result) use (&$context) {
                $res = new Models\DownloadResult();
                $res->written = $context['epos'] - $context['pos'];
                return $res;
            },
            function ($reason) use (&$context) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\DownloadException(
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
}
