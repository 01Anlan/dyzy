<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class AesCtrCipher
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class AesCtrCipher implements ContentCipherInterface
{
    /**
     * @var CipherData
     */
    private CipherData $cipherData;

    /**
     * AesCtrCipher constructor.
     * @param CipherData $cipherData
     */
    public function __construct(CipherData $cipherData)
    {
        $this->cipherData = $cipherData;
    }

    /**
     * @param int $length
     * @return int
     */
    public function getEncryptedLen(int $length): int
    {
        //AES CTR encryption mode does not change content length
        return $length;
    }

    /**
     * @return CipherData
     */
    public function getCipherData(): CipherData
    {
        return $this->cipherData;
    }

    /**
     * @return int
     */
    public function getAlignLen(): int
    {
        return AesCtr::BLOCK_SIZE_LEN;
    }

    /**
     * @param int $offset
     * @return CipherInterface
     */
    public function getCipher(int $offset = 0): CipherInterface
    {
        return new AesCtr($this->cipherData, $offset);        
    }
}
