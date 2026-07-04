<?php

namespace UnitTests\Signer;

use AlibabaCloud\Oss\V2\Signer\SigningContext;

class SignerContextTest extends \PHPUnit\Framework\TestCase
{

    public function testSignerContextTestWithNull()
    {
        $g = new SigningContext();
        $this->assertIsArray($g->subResource);
        $this->assertNull($g->product);
        $this->assertNull($g->region);
        $this->assertNull($g->bucket);
        $this->assertNull($g->key);
        $this->assertNull($g->request);
        $this->assertNull($g->credentials);
        $this->assertNull($g->time);
        $this->assertNull($g->signedHeaders);
        $this->assertNull($g->stringToSign);
        $this->assertFalse($g->authMethodQuery);
    }

}