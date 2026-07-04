<?php

namespace UnitTests\Retry;

use AlibabaCloud\Oss\V2\Retry;

class BackoffTest extends \PHPUnit\Framework\TestCase
{
    const ATTEMPTED_CELLING = 64;

    public function testEqualJitterBackoff()
    {
        $basedelay = 1.0;
        $maxdelay = 20.0;
        $r = new Retry\EqualJitterBackoff($basedelay, $maxdelay);

        for ($x = 0; $x <= self::ATTEMPTED_CELLING * 2; $x++) {
            $delay = $r->backoffDelay($x, new \Exception());
            $this->assertLessThan( $maxdelay + 1, $delay);
            $this->assertGreaterThan(0, $delay);
        }

        $delay = $r->backoffDelay($x, null);
        $this->assertLessThan( $maxdelay + 1, $delay);
        $this->assertGreaterThan(0, $delay);        
    }

    public function testFullJitterBackoff()
    {
        $basedelay = 1.0;
        $maxdelay = 20.0;
        $r = new Retry\FullJitterBackoff($basedelay, $maxdelay);

        for ($x = 0; $x <= self::ATTEMPTED_CELLING * 2; $x++) {
            $delay = $r->backoffDelay($x, new \Exception());
            $this->assertLessThan( $maxdelay + 1, $delay);
            $this->assertGreaterThan(0, $delay);
        }

        $delay = $r->backoffDelay($x, null);
        $this->assertLessThan( $maxdelay + 1, $delay);
        $this->assertGreaterThan(0, $delay);
    }

    public function testFixedDelayBackoff()
    {
        $maxdelay = 20.0;
        $r = new Retry\FixedDelayBackoff($maxdelay);

        for ($x = 0; $x <= self::ATTEMPTED_CELLING * 2; $x++) {
            $delay = $r->backoffDelay($x, new \Exception());
            $this->assertEquals($maxdelay, $delay);
        }

        $delay = $r->backoffDelay($x, null);
        $this->assertEquals($maxdelay, $delay);
    }    
}
