<?php

namespace UnitTests\Retry;

use AlibabaCloud\Oss\V2\Retry;
use AlibabaCloud\Oss\V2\Defaults;
use AlibabaCloud\Oss\V2\Exception;

use GuzzleHttp;

class RetryerTest extends \PHPUnit\Framework\TestCase
{
    const ATTEMPTED_CELLING = 64;

    public function testNopRetryer()
    {
        $r = new Retry\NopRetryer();
        $this->assertEquals(false, $r->isErrorRetryable(new \Exception('')));
        $this->assertEquals(1, $r->getMaxAttempts());

        try {

            $r->retryDelay(1, new \Exception(''));
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertEquals('NotImplemented', $e->getMessage());
        }
    }

    public function testStandardRetryer()
    {
        //default
        $r = new Retry\StandardRetryer();
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $r->getMaxAttempts());

        $this->assertEquals(false, $r->isErrorRetryable(new \Exception()));
        $this->assertEquals(false, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(403)));
        $this->assertEquals(false, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(405)));

        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(401)));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(408)));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(429)));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(500)));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(501)));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genStatusCodeError(599)));

        $this->assertEquals(false, $r->isErrorRetryable(ErrorRetryableTest::genServiceCodeError('123')));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genServiceCodeError('RequestTimeTooSkewed')));
        $this->assertEquals(true, $r->isErrorRetryable(ErrorRetryableTest::genServiceCodeError('BadRequest')));

        $this->assertEquals(true, $r->isErrorRetryable(new Exception\ServiceException([
            'status_code' => 403,
            'code' => 'RequestTimeTooSkewed',
        ])));

        $this->assertEquals(true, $r->isErrorRetryable(new Exception\CredentialsException('')));
        $this->assertEquals(true, $r->isErrorRetryable(new Exception\InconsistentExecption('1', '2')));

        $request = new GuzzleHttp\Psr7\Request('GET', 'http://foo.com');
        $respons = new GuzzleHttp\Psr7\Response();
        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\BadResponseException('', $request, $respons)));
        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\ConnectException('', $request)));
        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\RequestException('', $request)));


        for ($x = 0; $x <= self::ATTEMPTED_CELLING * 2; $x++) {
            $delay = $r->retryDelay($x, new \Exception());
            $this->assertLessThan(Defaults::MAX_BACKOFF_S + 1, $delay);
            $this->assertGreaterThan(0, $delay);
        }

        $delay = $r->retryDelay($x, null);
        $this->assertLessThan(Defaults::MAX_BACKOFF_S + 1, $delay);
        $this->assertGreaterThan(0, $delay);
    }
}
