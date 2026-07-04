<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketTransferAcceleration;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketTransferAccelerationTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketTransferAcceleration()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketTransferAccelerationRequest();
            $input = BucketTransferAcceleration::fromPutBucketTransferAcceleration($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketTransferAccelerationRequest('bucket-123');
            $input = BucketTransferAcceleration::fromPutBucketTransferAcceleration($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, transferAccelerationConfiguration", (string)$e);
        }

        $request = new Models\PutBucketTransferAccelerationRequest('bucket-123', new Models\TransferAccelerationConfiguration(
            true
        ));
        $input = BucketTransferAcceleration::fromPutBucketTransferAcceleration($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><TransferAccelerationConfiguration><Enabled>true</Enabled></TransferAccelerationConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketTransferAcceleration()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketTransferAcceleration::toPutBucketTransferAcceleration($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketTransferAcceleration::toPutBucketTransferAcceleration($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketTransferAcceleration()
    {
        // miss required field
        try {
            $request = new Models\GetBucketTransferAccelerationRequest();
            $input = BucketTransferAcceleration::fromGetBucketTransferAcceleration($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketTransferAccelerationRequest('bucket-123');
        $input = BucketTransferAcceleration::fromGetBucketTransferAcceleration($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketTransferAcceleration()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketTransferAcceleration::toGetBucketTransferAcceleration($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<TransferAccelerationConfiguration>
 <Enabled>true</Enabled>
</TransferAccelerationConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketTransferAcceleration::toGetBucketTransferAcceleration($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertTrue($result->transferAccelerationConfiguration->enabled);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<TransferAccelerationConfiguration>
 <Enabled>false</Enabled>
</TransferAccelerationConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketTransferAcceleration::toGetBucketTransferAcceleration($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertFalse($result->transferAccelerationConfiguration->enabled);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
