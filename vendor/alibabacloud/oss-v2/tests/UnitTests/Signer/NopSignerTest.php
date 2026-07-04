<?php

namespace UnitTests\Signer;

use AlibabaCloud\Oss\V2\Signer\NopSigner;
use AlibabaCloud\Oss\V2\Signer\SigningContext;

class NopSignerTest extends \PHPUnit\Framework\TestCase
{

    public function testNopSigner()
    {
        $r = new NopSigner();
        $g = new SigningContext();
        $this->assertNull($r->sign($g));
    }

}