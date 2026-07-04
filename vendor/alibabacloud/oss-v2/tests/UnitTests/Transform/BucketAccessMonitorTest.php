<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketAccessMonitor;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketAccessMonitorTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketAccessMonitor()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketAccessMonitorRequest();
            $input = BucketAccessMonitor::fromPutBucketAccessMonitor($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketAccessMonitorRequest('bucket-123');
            $input = BucketAccessMonitor::fromPutBucketAccessMonitor($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessMonitorConfiguration", (string)$e);
        }

        $request = new Models\PutBucketAccessMonitorRequest('bucket-123', new Models\AccessMonitorConfiguration(
            status: Models\AccessMonitorStatusType::ENABLED
        ));
        $input = BucketAccessMonitor::fromPutBucketAccessMonitor($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><AccessMonitorConfiguration><Status>Enabled</Status></AccessMonitorConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketAccessMonitor()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketAccessMonitor::toPutBucketAccessMonitor($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketAccessMonitor::toPutBucketAccessMonitor($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketAccessMonitor()
    {
        // miss required field
        try {
            $request = new Models\GetBucketAccessMonitorRequest();
            $input = BucketAccessMonitor::fromGetBucketAccessMonitor($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketAccessMonitorRequest('bucket-123');
        $input = BucketAccessMonitor::fromGetBucketAccessMonitor($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketAccessMonitor()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketAccessMonitor::toGetBucketAccessMonitor($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<AccessMonitorConfiguration>
 <Status>Enabled</Status>
</AccessMonitorConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketAccessMonitor::toGetBucketAccessMonitor($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(Models\AccessMonitorStatusType::ENABLED, $result->accessMonitorConfiguration->status);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
