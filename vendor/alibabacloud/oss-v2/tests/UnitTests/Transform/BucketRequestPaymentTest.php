<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketRequestPayment;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketRequestPaymentTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketRequestPayment()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketRequestPaymentRequest();
            $input = BucketRequestPayment::fromPutBucketRequestPayment($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketRequestPaymentRequest('bucket-123');
            $input = BucketRequestPayment::fromPutBucketRequestPayment($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, requestPaymentConfiguration", (string)$e);
        }

        $request = new Models\PutBucketRequestPaymentRequest('bucket-123', new Models\RequestPaymentConfiguration(
            'Requester'
        ));
        $input = BucketRequestPayment::fromPutBucketRequestPayment($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RequestPaymentConfiguration><Payer>Requester</Payer></RequestPaymentConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\PutBucketRequestPaymentRequest('bucket-123', new Models\RequestPaymentConfiguration(
            'BucketOwner'
        ));
        $input = BucketRequestPayment::fromPutBucketRequestPayment($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RequestPaymentConfiguration><Payer>BucketOwner</Payer></RequestPaymentConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketRequestPayment()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRequestPayment::toPutBucketRequestPayment($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketRequestPayment::toPutBucketRequestPayment($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketRequestPayment()
    {
        // miss required field
        try {
            $request = new Models\GetBucketRequestPaymentRequest();
            $input = BucketRequestPayment::fromGetBucketRequestPayment($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketRequestPaymentRequest('bucket-123');
        $input = BucketRequestPayment::fromGetBucketRequestPayment($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketRequestPayment()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRequestPayment::toGetBucketRequestPayment($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<RequestPaymentConfiguration>
  <Payer>Requester</Payer>
</RequestPaymentConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketRequestPayment::toGetBucketRequestPayment($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('Requester', $result->requestPaymentConfiguration->payer);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<RequestPaymentConfiguration>
  <Payer>BucketOwner</Payer>
</RequestPaymentConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketRequestPayment::toGetBucketRequestPayment($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('BucketOwner', $result->requestPaymentConfiguration->payer);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
