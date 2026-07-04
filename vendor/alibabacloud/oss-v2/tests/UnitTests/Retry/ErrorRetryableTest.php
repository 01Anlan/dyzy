<?php

namespace UnitTests\Retry;

use AlibabaCloud\Oss\V2\Retry;
use AlibabaCloud\Oss\V2\Exception;
use GuzzleHttp;

class ErrorRetryableTest extends \PHPUnit\Framework\TestCase
{
    const ATTEMPTED_CELLING = 64;

    public function testHTTPStatusCodeRetryable()
    {
        $r = new Retry\HTTPStatusCodeRetryable();
        $this->assertEquals(false, $r->isErrorRetryable(new \Exception()));
        $this->assertEquals(false, $r->isErrorRetryable(self::genStatusCodeError(403)));
        $this->assertEquals(false, $r->isErrorRetryable(self::genStatusCodeError(405)));

        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(401)));
        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(408)));
        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(429)));
        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(500)));
        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(501)));
        $this->assertEquals(true, $r->isErrorRetryable(self::genStatusCodeError(599)));
    }

    public function testServiceErrorCodeRetryable()
    {
        $r = new Retry\ServiceErrorCodeRetryable();
        $this->assertEquals(false, $r->isErrorRetryable(new \Exception()));
        $this->assertEquals(false, $r->isErrorRetryable(self::genServiceCodeError('123')));

        $this->assertEquals(true, $r->isErrorRetryable(self::genServiceCodeError('RequestTimeTooSkewed')));
        $this->assertEquals(true, $r->isErrorRetryable(self::genServiceCodeError('BadRequest')));
    }

    public function testClientErrorCodeRetryable()
    {
        $r = new Retry\ClientErrorRetryable();
        $this->assertEquals(false, $r->isErrorRetryable(new \Exception()));
        $this->assertEquals(false, $r->isErrorRetryable(self::genServiceCodeError('123')));

        $this->assertEquals(true, $r->isErrorRetryable(new Exception\CredentialsException('')));
        $this->assertEquals(true, $r->isErrorRetryable(new Exception\InconsistentExecption('1', '2')));

        $request = new GuzzleHttp\Psr7\Request('GET', 'http://foo.com');
        $respons = new GuzzleHttp\Psr7\Response();
        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\BadResponseException('', $request, $respons)));

        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\ConnectException('', $request)));
        $this->assertEquals(true, $r->isErrorRetryable(new GuzzleHttp\Exception\RequestException('', $request)));
    }

    public static function genStatusCodeError(int $statusCode)
    {
        return new Exception\ServiceException(
            [
                'status_code' => $statusCode,
            ]
        );
    }

    public static function genServiceCodeError(string $code)
    {
        return new Exception\ServiceException(
            [
                'code' => $code,
            ]
        );
    }
}
