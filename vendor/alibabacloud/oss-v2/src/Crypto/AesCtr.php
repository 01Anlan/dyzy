<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class AesCtr
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class AesCtr implements CipherInterface
{
    public const KEY_SIZE = 32;
    public const IV_SIZE = 16;

    public const BLOCK_SIZE_LEN = 16;

    public const BLOCK_BITS_LEN = 8 * 16;

    public const CIPHER_NAME = 'aes-256-ctr';

    public const ALGORITHM = 'AES/CTR/NoPadding';

    const OPTIONS = OPENSSL_RAW_DATA | OPENSSL_NO_PADDING;

    private CipherData $cipherData;

    private int $offset;

    private int $counter;

    private string $highIv;

    private string $lastData;

    public function __construct(
        CipherData $cipherData,
        int $offset = 0
    )
    {
        $this->cipherData = $cipherData;
        $this->offset = $offset;
        $this->lastData = '';
        if ($offset % self::BLOCK_SIZE_LEN !== 0) {
            throw new \InvalidArgumentException('offset is not align to encrypt block');
        }
        $this->highIv = substr($this->cipherData->iv, 0, 8);
        $this->counter = $this->lowIvToInt($cipherData->iv) + intval($offset / self::BLOCK_SIZE_LEN);
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt(string $data): string
    {
        if ($data === '') {
            return '';
        }
        $lastLen = strlen($this->lastData);
        if ($lastLen > 0) {
            $data = $this->lastData . $data;
        }

        $edata = openssl_encrypt(
            $data,
            self::CIPHER_NAME,
            $this->cipherData->key,
            self::OPTIONS,
            $this->countToIv()
        );
        if ($edata === false) {
            throw new \RuntimeException('AesCtr encrypt fail');
        }
        if ($lastLen > 0) {
            $edata = substr($edata, $lastLen);
        }
        $this->counter += intval(strlen($data) / self::BLOCK_SIZE_LEN);

        $remains = strlen($data) % self::BLOCK_SIZE_LEN;

        $this->lastData = $remains > 0 ? substr($data, -$remains) : '';
        //print("counter: $this->counter \n");
        return $edata;
    }

    /**
     * @param string $data
     * @return string
     */
    public function decrypt(string $data): string
    {
        if ($data === '') {
            return '';
        }
        $lastLen = strlen($this->lastData);
        if ($lastLen > 0) {
            $data = $this->lastData . $data;
        }

        $edata = openssl_decrypt(
            $data,
            self::CIPHER_NAME,
            $this->cipherData->key,
            self::OPTIONS,
            $this->countToIv()
        );
        if ($edata === false) {
            throw new \RuntimeException('AesCtr decrypt fail');
        }
        if ($lastLen > 0) {
            $edata = substr($edata, $lastLen);
        }
        $this->counter += intval(strlen($data) / self::BLOCK_SIZE_LEN);

        $remains = strlen($data) % self::BLOCK_SIZE_LEN;

        $this->lastData = $remains > 0 ? substr($data, -$remains) : '';
        return $edata;
    }

    public function reset()
    {
        $this->counter = $this->lowIvToInt($this->cipherData->iv) + intval($this->offset / self::BLOCK_SIZE_LEN);
        $this->lastData = '';
    }

    static public function randomKey(): string
    {
        return openssl_random_pseudo_bytes(self::KEY_SIZE);
    }

    static public function randomIv(): string
    {
        $iv = openssl_random_pseudo_bytes(self::IV_SIZE);
        // only use 4 byte,in order not to overflow when SeekIV()
        $iv = substr($iv, 0, 8) . pack('N', 0) . substr($iv, 12, 4);

        return $iv;
    }

    private function lowIvToInt(string $iv): int
    {
        $vals = unpack('J2', $iv, 0);
        return $vals[2];
    }

    private function countToIv(): string
    {
        return $this->highIv . pack('J', $this->counter);
    }
}
