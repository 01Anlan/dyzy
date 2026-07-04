<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

use AlibabaCloud\Oss\V2\Utils;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\StreamDecoratorTrait;

/**
 * Lazily decrypt data into the stream
 * Class LazyDecryptStream
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class LazyDecryptStream implements StreamInterface
{
    use StreamDecoratorTrait;

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var int
     */
    private $discardSize;

    /**
     * @var callable(ResponseInterface $response): ContentCipherInterface
     */
    private $getContentCipher;

    private ?CipherInterface $cipher;


    private $discard;

    /**
     * @param StreamInterface $stream stream to lazily write
     * @param int $discardSize how many bytes need to be discarded
     * @param callable(ResponseInterface $response): ContentCipherInterface $getContentCipher
     */
    public function __construct(StreamInterface $stream, int $discardSize, callable $getContentCipher)
    {
        $this->stream = $stream;
        $this->discardSize = $discardSize;
        $this->getContentCipher = $getContentCipher;
        $this->discard = $discardSize;
        $this->cipher = null;
    }

    public function on_headers(ResponseInterface $response)
    {
        /**
         * @var ContentCipherInterface $contentCipher
         * @var array $contentRange
         */
        $contentCipher = ($this->getContentCipher)($response);
        if ($contentCipher != null) {
            $offset = 0;
            if ($response->hasHeader('content-range')) {
                $contentRange = $response->getHeader('content-range')[0];
                $vals = Utils::parseContentRange($contentRange);
                if ($vals === false) {
                    throw new \RuntimeException("parse Content-Range error, got $contentRange");
                }
                $offset = $vals[0];
            }
            $this->cipher = $contentCipher->getCipher($offset);
        }
    }

    public function rewind(): void
    {
        $this->discard = $this->discardSize;
        if ($this->cipher != null) {
            $this->cipher->reset();
        }
        $this->seek(0);
    }

    public function write($string): int
    {
        $len = strlen($string);
        if ($len == 0) {
            return 0;
        }
        if ($this->cipher != null) {
            $string = $this->cipher->decrypt($string);
        }
        if ($this->discard > 0) {
            $min = min($this->discard, $len);
            $string = substr($string, $min);
            $this->discard -= $min;
        }
        $this->stream->write($string);
        return $len;
    }
    
    public function unwrap(): StreamInterface
    {
        return $this->stream;
    }
}
