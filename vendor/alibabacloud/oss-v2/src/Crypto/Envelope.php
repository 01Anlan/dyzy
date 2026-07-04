<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Crypto;

/**
 * Class Envelope
 * @package AlibabaCloud\Oss\V2\Crypto
 */
final class Envelope
{
    public ?string $iv;
    public ?string $key;

    public ?string $matDesc;

    public ?string $wrapAlg;

    public ?string $cekAlg;

    public ?string $unencryptedMd5;

    public ?string $unencryptedContentLen;

    public function __construct(
        ?string $iv = null,
        ?string $key = null,
        ?string $cekAlg = null,
        ?string $wrapAlg = null,
        ?string $matDesc = null,
        ?string $unencryptedMd5 = null,
        ?string $unencryptedContentLen = null
    ) {
        $this->iv = $iv;
        $this->key = $key;
        $this->cekAlg = $cekAlg;
        $this->wrapAlg = $wrapAlg;
        $this->matDesc = $matDesc;
        $this->unencryptedMd5 = $unencryptedMd5;
        $this->unencryptedContentLen = $unencryptedContentLen;
    }
}
