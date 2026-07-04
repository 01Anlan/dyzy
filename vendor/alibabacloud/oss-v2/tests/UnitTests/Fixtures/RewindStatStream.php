<?php

declare(strict_types=1);

namespace UnitTests\Fixtures;

use GuzzleHttp;

/**
 * Stream decorator that prevents a stream from rewind.
 */
final class RewindStatStream implements \Psr\Http\Message\StreamInterface
{
    use GuzzleHttp\Psr7\StreamDecoratorTrait;

    /** @var \Psr\Http\Message\StreamInterface */
    private $stream;

    private int $rewindCount = 0;

    public function rewind(): void
    {
        $this->rewindCount++;
        $this->stream->rewind();
    }

    /**
     * Get the value of rewindCount
     */ 
    public function getRewindCount()
    {
        return $this->rewindCount;
    }
}
