<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketArchiveDirectRead;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketArchiveDirectReadTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketArchiveDirectRead()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketArchiveDirectReadRequest();
            $input = BucketArchiveDirectRead::fromPutBucketArchiveDirectRead($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketArchiveDirectReadRequest('bucket-123');
            $input = BucketArchiveDirectRead::fromPutBucketArchiveDirectRead($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, archiveDirectReadConfiguration", (string)$e);
        }

        $request = new Models\PutBucketArchiveDirectReadRequest('bucket-123', new Models\ArchiveDirectReadConfiguration(
            true
        ));
        $input = BucketArchiveDirectRead::fromPutBucketArchiveDirectRead($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ArchiveDirectReadConfiguration><Enabled>true</Enabled></ArchiveDirectReadConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketArchiveDirectRead()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketArchiveDirectRead::toPutBucketArchiveDirectRead($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketArchiveDirectRead::toPutBucketArchiveDirectRead($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketArchiveDirectRead()
    {
        // miss required field
        try {
            $request = new Models\GetBucketArchiveDirectReadRequest();
            $input = BucketArchiveDirectRead::fromGetBucketArchiveDirectRead($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketArchiveDirectReadRequest('bucket-123');
        $input = BucketArchiveDirectRead::fromGetBucketArchiveDirectRead($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketArchiveDirectRead()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketArchiveDirectRead::toGetBucketArchiveDirectRead($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ArchiveDirectReadConfiguration>
 <Enabled>true</Enabled>
</ArchiveDirectReadConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketArchiveDirectRead::toGetBucketArchiveDirectRead($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertTrue($result->archiveDirectReadConfiguration->enabled);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ArchiveDirectReadConfiguration>
 <Enabled>false</Enabled>
</ArchiveDirectReadConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketArchiveDirectRead::toGetBucketArchiveDirectRead($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertFalse($result->archiveDirectReadConfiguration->enabled);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
