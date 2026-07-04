<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use Psr\Http\Message\StreamInterface;

/**
 * Progress stream.
 * Class ProgressStream
 * @package AlibabaCloud\Oss\V2
 */
class ProgressStream implements StreamInterface
{
    use StreamDecoratorTrait;

    private $stream;

    private $onProgress;

    private $lwritten;

    private $written;

    private $total;

    /**
     * @param StreamInterface $stream Stream that is being read.
     * @param callable $onProgress A tracker with progress reporting.
     *                                    Syntax and parameters for onProgress likes func(int $increment, int $transferred, int $total)
     *                                    - increment The size of the data transmitted by this callback.
     *                                    - transferred The size of transmitted data.
     *                                    - total The size of the requested data. If the value of this parameter is -1, it specifies that the size cannot be obtained.
     * @param int|null $total The size of the data. -1 or null means the size is unknown.
     */
    public function __construct(
        StreamInterface $stream,
        $onProgress,
        ?int $total
    )
    {
        $this->stream = $stream;
        $this->onProgress = $onProgress;
        $this->lwritten = 0;
        $this->written = 0;
        $this->total = $total ?? -1;
    }

    public function read($length): string
    {
        $data = $this->stream->read($length);
        $n = strlen($data);
        $this->written += $n;
        if ($this->written >= $this->lwritten) {
            call_user_func($this->onProgress, $n, $this->written, $this->total);
        }
        return $data;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        // Seeking arbitrarily is not supported.
        if ($offset !== 0) {
            throw new \InvalidArgumentException('Seeking arbitrarily is not supported for ProgressStream');
        }

        $this->lwritten = $this->written;
        $this->written = 0;
        $this->stream->seek($offset);
    }
}
