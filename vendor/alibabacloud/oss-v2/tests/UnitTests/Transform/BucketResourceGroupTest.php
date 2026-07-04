<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketResourceGroup;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketResourceGroupTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketResourceGroup()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketResourceGroupRequest();
            $input = BucketResourceGroup::fromPutBucketResourceGroup($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketResourceGroupRequest('bucket-123');
            $input = BucketResourceGroup::fromPutBucketResourceGroup($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucketResourceGroupConfiguration", (string)$e);
        }

        $request = new Models\PutBucketResourceGroupRequest('bucket-123', new Models\BucketResourceGroupConfiguration(
            'rg-aekz****'
        ));
        $input = BucketResourceGroup::fromPutBucketResourceGroup($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketResourceGroupConfiguration><ResourceGroupId>rg-aekz****</ResourceGroupId></BucketResourceGroupConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketResourceGroup()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketResourceGroup::toPutBucketResourceGroup($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketResourceGroup::toPutBucketResourceGroup($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketResourceGroup()
    {
        // miss required field
        try {
            $request = new Models\GetBucketResourceGroupRequest();
            $input = BucketResourceGroup::fromGetBucketResourceGroup($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketResourceGroupRequest('bucket-123');
        $input = BucketResourceGroup::fromGetBucketResourceGroup($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketResourceGroup()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketResourceGroup::toGetBucketResourceGroup($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketResourceGroupConfiguration>
  <ResourceGroupId>rg-aekz****</ResourceGroupId>
</BucketResourceGroupConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketResourceGroup::toGetBucketResourceGroup($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('rg-aekz****', $result->bucketResourceGroupConfiguration->resourceGroupId);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
