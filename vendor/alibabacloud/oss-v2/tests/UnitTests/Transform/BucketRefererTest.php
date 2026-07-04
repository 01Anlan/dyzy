<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketReferer;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;
use DateTime;
use DateTimeZone;

class BucketRefererTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketReferer()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketRefererRequest();
            $input = BucketReferer::fromPutBucketReferer($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketRefererRequest('bucket-123');
            $input = BucketReferer::fromPutBucketReferer($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, refererConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketRefererRequest('bucket-123', new Models\RefererConfiguration(
            allowEmptyReferer: false,
            allowTruncateQueryString: true,
            truncatePath: true,
            refererList: new Models\RefererList(["http://www.aliyun.com", "https://www.aliyun.com", "http://www.*.com", "https://www.?.aliyuncs.com",]),
            refererBlacklist: new Models\RefererBlacklist(["http://www.refuse.com", "https://*.hack.com", "http://ban.*.com", "https://www.?.deny.com",])
        ));
        $input = BucketReferer::fromPutBucketReferer($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RefererConfiguration><AllowEmptyReferer>false</AllowEmptyReferer><AllowTruncateQueryString>true</AllowTruncateQueryString><TruncatePath>true</TruncatePath><RefererList><Referer>http://www.aliyun.com</Referer><Referer>https://www.aliyun.com</Referer><Referer>http://www.*.com</Referer><Referer>https://www.?.aliyuncs.com</Referer></RefererList><RefererBlacklist><Referer>http://www.refuse.com</Referer><Referer>https://*.hack.com</Referer><Referer>http://ban.*.com</Referer><Referer>https://www.?.deny.com</Referer></RefererBlacklist></RefererConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketRefererRequest('bucket-123', new Models\RefererConfiguration(
            allowEmptyReferer: false,
            allowTruncateQueryString: true,
            truncatePath: true,
            refererList: new Models\RefererList(["http://www.aliyun.com", "https://www.aliyun.com", "http://www.*.com", "https://www.?.aliyuncs.com",]),
        ));
        $input = BucketReferer::fromPutBucketReferer($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RefererConfiguration><AllowEmptyReferer>false</AllowEmptyReferer><AllowTruncateQueryString>true</AllowTruncateQueryString><TruncatePath>true</TruncatePath><RefererList><Referer>http://www.aliyun.com</Referer><Referer>https://www.aliyun.com</Referer><Referer>http://www.*.com</Referer><Referer>https://www.?.aliyuncs.com</Referer></RefererList></RefererConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo3
        $request = new Models\PutBucketRefererRequest('bucket-123', new Models\RefererConfiguration(
            allowEmptyReferer: false,
            refererList: new Models\RefererList([""]),
        ));
        $input = BucketReferer::fromPutBucketReferer($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RefererConfiguration><AllowEmptyReferer>false</AllowEmptyReferer><RefererList><Referer></Referer></RefererList></RefererConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketReferer()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReferer::toPutBucketReferer($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketReferer::toPutBucketReferer($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketReferer()
    {
        // miss required field
        try {
            $request = new Models\GetBucketRefererRequest();
            $input = BucketReferer::fromGetBucketReferer($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketRefererRequest('bucket-123');
        $input = BucketReferer::fromGetBucketReferer($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketReferer()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReferer::toGetBucketReferer($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<RefererConfiguration>
  <AllowEmptyReferer>false</AllowEmptyReferer>
  <AllowTruncateQueryString>true</AllowTruncateQueryString>
  <TruncatePath>true</TruncatePath>
  <RefererList>
    <Referer>http://www.aliyun.com</Referer>
    <Referer>https://www.aliyun.com</Referer>
    <Referer>http://www.*.com</Referer>
    <Referer>https://www.?.aliyuncs.com</Referer>
  </RefererList>
</RefererConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketReferer::toGetBucketReferer($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertFalse($result->refererConfiguration->allowEmptyReferer);
        $this->assertTrue($result->refererConfiguration->allowTruncateQueryString);
        $this->assertTrue($result->refererConfiguration->truncatePath);
        $this->assertEquals(4, count($result->refererConfiguration->refererList->referers));
        $this->assertEquals("https://www.?.aliyuncs.com", ($result->refererConfiguration->refererList->referers[3]));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<RefererConfiguration>
  <AllowEmptyReferer>false</AllowEmptyReferer>
  <AllowTruncateQueryString>true</AllowTruncateQueryString>
  <TruncatePath>true</TruncatePath>
  <RefererList>
    <Referer>http://www.aliyun.com</Referer>
    <Referer>https://www.aliyun.com</Referer>
    <Referer>http://www.*.com</Referer>
    <Referer>https://www.?.aliyuncs.com</Referer>
  </RefererList>
  <RefererBlacklist>
    <Referer>http://www.refuse.com</Referer>
    <Referer>https://*.hack.com</Referer>
    <Referer>http://ban.*.com</Referer>
    <Referer>https://www.?.deny.com</Referer>
  </RefererBlacklist>
</RefererConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketReferer::toGetBucketReferer($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertFalse($result->refererConfiguration->allowEmptyReferer);
        $this->assertTrue($result->refererConfiguration->allowTruncateQueryString);
        $this->assertTrue($result->refererConfiguration->truncatePath);
        $this->assertEquals(4, count($result->refererConfiguration->refererList->referers));
        $this->assertEquals(4, count($result->refererConfiguration->refererBlacklist->referers));
        $this->assertEquals("https://www.?.aliyuncs.com", ($result->refererConfiguration->refererList->referers[3]));
        $this->assertEquals("http://ban.*.com", ($result->refererConfiguration->refererBlacklist->referers[2]));
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
