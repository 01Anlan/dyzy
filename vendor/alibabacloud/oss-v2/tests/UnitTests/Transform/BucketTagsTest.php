<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketTags;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketTagsTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketTags()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketTagsRequest();
            $input = BucketTags::fromPutBucketTags($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketTagsRequest('bucket-123');
            $input = BucketTags::fromPutBucketTags($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, tagging", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketTagsRequest('bucket-123',
            tagging: new Models\Tagging(
                tagSet: new Models\TagSet(
                    [new Models\Tag(key: 'key1', value: 'value1'), new Models\Tag(key: 'key2', value: 'value2')])
            )
        );
        $input = BucketTags::fromPutBucketTags($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><Tagging><TagSet><Tag><Key>key1</Key><Value>value1</Value></Tag><Tag><Key>key2</Key><Value>value2</Value></Tag></TagSet></Tagging>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketTags()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketTags::toPutBucketTags($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketTags::toPutBucketTags($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketTags()
    {
        // miss required field
        try {
            $request = new Models\GetBucketTagsRequest();
            $input = BucketTags::fromGetBucketTags($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketTagsRequest('bucket-123');
        $input = BucketTags::fromGetBucketTags($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketTags()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketTags::toGetBucketTags($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<Tagging>
  <TagSet>
    <Tag>
      <Key>testa</Key>
      <Value>testv1</Value>
    </Tag>
    <Tag>
      <Key>testb</Key>
      <Value>testv2</Value>
    </Tag>
  </TagSet>
</Tagging>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketTags::toGetBucketTags($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals(2, count($result->tagging->tagSet->tags));
        $this->assertEquals('testa', $result->tagging->tagSet->tags[0]->key);
        $this->assertEquals('testv2', $result->tagging->tagSet->tags[1]->value);
    }

    public function testFromDeleteBucketTags()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketTagsRequest();
            $input = BucketTags::fromDeleteBucketTags($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketTagsRequest('bucket-123');
        $input = BucketTags::fromDeleteBucketTags($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        $request = new Models\DeleteBucketTagsRequest('bucket-123', tagging: 'k1,k2');
        $input = BucketTags::fromDeleteBucketTags($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('k1,k2', $input->getParameters()['tagging']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketTags()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketTags::toDeleteBucketTags($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketTags::toDeleteBucketTags($output);
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
