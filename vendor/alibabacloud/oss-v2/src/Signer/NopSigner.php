<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Signer;

class NopSigner implements SignerInterface
{
    public function sign(SigningContext $signingCtx)
    {
        return null;
    }
}
