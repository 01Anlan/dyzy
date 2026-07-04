<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\StreamDecoratorTrait;

/**
 * Read and encrypt data from stream
 * Class ReadEncryptStream
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class ReadEncryptStream implements StreamInterface
{
    use StreamDecoratorTrait;

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var CipherInterface
     */
    private CipherInterface $cipher;

    /**
     * @param StreamInterface $stream stream to read
     * @param CipherInterface $cipher a cipher instances
     */
    public function __construct(StreamInterface $stream, CipherInterface $cipher)
    {
        $this->stream = $stream;
        $this->cipher = $cipher;
    }

    public function rewind(): void
    {
        $this->cipher->reset();
        $this->seek(0);
    }

    public function read($length): string
    {
        $data = $this->stream->read($length);
        $data = $this->cipher->encrypt($data);
        return $data;
    }

    public function write($string): int
    {
        throw new \RuntimeException('Cannot write a ReadEncryptStream');
    }  
}
