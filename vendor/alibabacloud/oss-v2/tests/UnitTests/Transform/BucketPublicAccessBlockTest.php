<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Models\PublicAccessBlockConfiguration;
use AlibabaCloud\Oss\V2\Transform\BucketPublicAccessBlock;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketPublicAccessBlockTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketPublicAccessBlock()
    {
        // miss required field
        try {
            $request = new Models\PutBucketPublicAccessBlockRequest();
            $input = BucketPublicAccessBlock::fromPutBucketPublicAccessBlock($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketPublicAccessBlockRequest('bucket-123');
            $input = BucketPublicAccessBlock::fromPutBucketPublicAccessBlock($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, publicAccessBlockConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketPublicAccessBlockRequest('bucket-123', new Models\PublicAccessBlockConfiguration(
            true
        ));
        $input = BucketPublicAccessBlock::fromPutBucketPublicAccessBlock($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><PublicAccessBlockConfiguration><BlockPublicAccess>true</BlockPublicAccess></PublicAccessBlockConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketPublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPublicAccessBlock::toPutBucketPublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketPublicAccessBlock::toPutBucketPublicAccessBlock($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketPublicAccessBlock()
    {
        try {
            $request = new Models\GetBucketPublicAccessBlockRequest();
            $input = BucketPublicAccessBlock::fromGetBucketPublicAccessBlock($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketPublicAccessBlockRequest('bucket-123');
        $input = BucketPublicAccessBlock::fromGetBucketPublicAccessBlock($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketPublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPublicAccessBlock::toGetBucketPublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
            <PublicAccessBlockConfiguration>
			  <BlockPublicAccess>true</BlockPublicAccess>
			</PublicAccessBlockConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketPublicAccessBlock::toGetBucketPublicAccessBlock($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertTrue($result->publicAccessBlockConfiguration->blockPublicAccess);
    }

    public function testFromDeleteBucketPublicAccessBlock()
    {
        try {
            $request = new Models\DeleteBucketPublicAccessBlockRequest();
            $input = BucketPublicAccessBlock::fromDeleteBucketPublicAccessBlock($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketPublicAccessBlockRequest('bucket-123');
        $input = BucketPublicAccessBlock::fromDeleteBucketPublicAccessBlock($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketPublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPublicAccessBlock::toDeleteBucketPublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketPublicAccessBlock::toDeleteBucketPublicAccessBlock($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
