<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class CipherData
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class CipherData
{
    public ?string $iv;
    public ?string $key;

    public ?string $encryptedIv;
    public ?string $encryptedKey;

    public ?string $matDesc;

    public ?string $wrapAlgorithm;

    public ?string $cekAlgorithm;

    public function __construct(
        ?string $iv = null,
        ?string $key = null,
        ?string $encryptedIv = null,
        ?string $encryptedKey = null,
        ?string $matDesc = null,
        ?string $wrapAlgorithm = null,
        ?string $cekAlgorithm = null
    )
    {
        $this->iv = $iv;
        $this->key = $key;
        $this->encryptedIv = $encryptedIv;
        $this->encryptedKey = $encryptedKey;
        $this->matDesc = $matDesc;
        $this->wrapAlgorithm = $wrapAlgorithm;
        $this->cekAlgorithm = $cekAlgorithm;
    }
}