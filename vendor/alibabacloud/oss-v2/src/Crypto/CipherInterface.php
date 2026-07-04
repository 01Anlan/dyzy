<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Interface CipherInterface
 * @package AlibabaCloud\Oss\V2\Crypto
 */
interface CipherInterface
{
    public function encrypt(string $data): string;

    public function decrypt(string $data): string;

    public function reset();
}
