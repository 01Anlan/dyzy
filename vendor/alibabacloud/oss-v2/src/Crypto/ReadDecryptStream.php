<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\StreamDecoratorTrait;

/**
 * Read and decrypt data from stream
 * Class ReadDecryptStream
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class ReadDecryptStream implements StreamInterface
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
     * @param StreamInterface $stream stream to lazily write
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
        $data = $this->cipher->decrypt($data);
        return $data;
    }

    public function write($string): int
    {
        throw new \RuntimeException('Cannot write a ReadDecryptStream');
    }    
}
