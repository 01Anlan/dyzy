<?php

declare(strict_types=1);

namespace UnitTests\Fixtures;

use GuzzleHttp;

/**
 * Stream decorator that prevents a stream from rewind.
 */
final class NoRewindStream implements \Psr\Http\Message\StreamInterface
{
    use GuzzleHttp\Psr7\StreamDecoratorTrait;

    /** @var \Psr\Http\Message\StreamInterface */
    private $stream;

    public function rewind(): void
    {
        throw new \RuntimeException('Cannot rewind a NoRewindStream');
    }
}
