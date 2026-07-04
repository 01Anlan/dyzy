<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\LazyOpenStream;
use Psr\Http\Message\StreamInterface;

/**
 * Converts sink into a stream that saves the error content in memory
 * instead of the user's stream.
 * Class SinkStreamWrapper
 * @package AlibabaCloud\Oss\V2
 */
final class SinkStreamWrapper implements StreamInterface
{
    use StreamDecoratorTrait;

    private bool $isMem = false;
    private string $memString = '';

    /**
     * @var StreamInterface
     */
    private $stream;

    private bool $fromFilepath;

    public function __construct(StreamInterface $stream, bool $fromFilepath = false)
    {
        $this->stream = $stream;
        $this->fromFilepath = $fromFilepath;
    }

    public function write($string): int
    {
        if ($this->isMem) {
            $this->memString .= $string;
            return strlen($string);
        }
        return $this->stream->write($string);
    }

    public function on_headers(\Psr\Http\Message\ResponseInterface $response)
    {
        if (method_exists($this->stream, 'on_headers')) {
            call_user_func([$this->stream, 'on_headers'], $response);
        }

        if ($response->getStatusCode() >= 400) {
            $this->isMem = true;
        }
    }

    public function resetMemState(): void
    {
        $this->memString = '';
        $this->isMem = false;
    }

    public function getMemContent(): string
    {
        return $this->memString;
    }

    public function isFromFilepath(): bool
    {
        return $this->fromFilepath;
    }

    public function unwrap(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * Create a new stream based on the sink.
     * if $sink type is string, it's filepath
     * @return SinkStreamWrapper
     */
    public static function sinkFor($sink): SinkStreamWrapper
    {
        $fromFilepath = \is_string($sink);
        $stream = $fromFilepath ?
            new LazyOpenStream($sink, 'w+') : Utils::streamFor($sink);
        return new SinkStreamWrapper($stream, $fromFilepath);
    }
}
