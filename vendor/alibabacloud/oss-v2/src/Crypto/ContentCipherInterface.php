<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;
use Psr\Http\Message\StreamInterface;

/**
 * Interface ContentCipherInterface
 * @package AlibabaCloud\Oss\V2\Crypto
 */
interface ContentCipherInterface
{
    public function getEncryptedLen(int $length): int;

    public function getCipherData(): CipherData;

    public function getAlignLen(): int;

    public function getCipher(int $offset = 0): CipherInterface;
}
