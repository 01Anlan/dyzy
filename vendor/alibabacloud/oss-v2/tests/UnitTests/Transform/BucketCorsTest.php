<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketCors;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketCorsTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketCors()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketCorsRequest();
            $input = BucketCors::fromPutBucketCors($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketCorsRequest('bucket-123');
            $input = BucketCors::fromPutBucketCors($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, corsConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketCorsRequest('bucket-123', new Models\CORSConfiguration(
            [
                new Models\CORSRule(
                    allowedHeaders: ['Authorization'],
                    allowedOrigins: ['*'],
                    allowedMethods: ['PUT', 'GET']
                ),
                new Models\CORSRule(
                    allowedHeaders: ['Authorization'],
                    exposeHeaders: ["x-oss-test", "x-oss-test1"],
                    maxAgeSeconds: 100,
                    allowedOrigins: ["http://example.com", "http://example.net"],
                    allowedMethods: ['GET'],
                ),
            ],
            responseVary: false
        ));
        $input = BucketCors::fromPutBucketCors($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CORSConfiguration><CORSRule><AllowedHeader>Authorization</AllowedHeader><AllowedOrigin>*</AllowedOrigin><AllowedMethod>PUT</AllowedMethod><AllowedMethod>GET</AllowedMethod></CORSRule><CORSRule><AllowedHeader>Authorization</AllowedHeader><ExposeHeader>x-oss-test</ExposeHeader><ExposeHeader>x-oss-test1</ExposeHeader><MaxAgeSeconds>100</MaxAgeSeconds><AllowedOrigin>http://example.com</AllowedOrigin><AllowedOrigin>http://example.net</AllowedOrigin><AllowedMethod>GET</AllowedMethod></CORSRule><ResponseVary>false</ResponseVary></CORSConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketCors()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCors::toPutBucketCors($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketCors::toPutBucketCors($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketCors()
    {
        // miss required field
        try {
            $request = new Models\GetBucketCorsRequest();
            $input = BucketCors::fromGetBucketCors($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketCorsRequest('bucket-123');
        $input = BucketCors::fromGetBucketCors($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketCors()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCors::toGetBucketCors($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration>
    <CORSRule>
      <AllowedOrigin>*</AllowedOrigin>
      <AllowedMethod>PUT</AllowedMethod>
      <AllowedMethod>GET</AllowedMethod>
      <AllowedHeader>Authorization</AllowedHeader>
    </CORSRule>
    <CORSRule>
      <AllowedOrigin>http://example.com</AllowedOrigin>
      <AllowedOrigin>http://example.net</AllowedOrigin>
      <AllowedMethod>GET</AllowedMethod>
      <AllowedHeader>Authorization</AllowedHeader>
      <ExposeHeader>x-oss-test</ExposeHeader>
      <ExposeHeader>x-oss-test1</ExposeHeader>
      <MaxAgeSeconds>100</MaxAgeSeconds>
    </CORSRule>
    <ResponseVary>false</ResponseVary>
</CORSConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketCors::toGetBucketCors($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(2, count($result->corsConfiguration->corsRules));
        $this->assertFalse($result->corsConfiguration->responseVary);
        $this->assertEquals($result->corsConfiguration->corsRules[0]->allowedOrigins[0], '*');
        $this->assertEquals(count($result->corsConfiguration->corsRules[0]->allowedMethods), 2);
        $this->assertEquals($result->corsConfiguration->corsRules[0]->allowedMethods[0], 'PUT');
        $this->assertEquals($result->corsConfiguration->corsRules[0]->allowedMethods[1], 'GET');
        $this->assertEquals(count($result->corsConfiguration->corsRules[0]->allowedHeaders), 1);
        $this->assertEquals($result->corsConfiguration->corsRules[0]->allowedHeaders[0], 'Authorization');

        $this->assertEquals($result->corsConfiguration->corsRules[1]->allowedOrigins[0], 'http://example.com');
        $this->assertEquals($result->corsConfiguration->corsRules[1]->allowedOrigins[1], 'http://example.net');
        $this->assertEquals(count($result->corsConfiguration->corsRules[1]->allowedMethods), 1);
        $this->assertEquals($result->corsConfiguration->corsRules[1]->allowedMethods[0], 'GET');
        $this->assertEquals(count($result->corsConfiguration->corsRules[1]->allowedHeaders), 1);
        $this->assertEquals($result->corsConfiguration->corsRules[1]->allowedHeaders[0], 'Authorization');
        $this->assertEquals(count($result->corsConfiguration->corsRules[1]->exposeHeaders), 2);
        $this->assertEquals($result->corsConfiguration->corsRules[1]->exposeHeaders[0], "x-oss-test");
        $this->assertEquals($result->corsConfiguration->corsRules[1]->exposeHeaders[1], "x-oss-test1");
        $this->assertEquals($result->corsConfiguration->corsRules[1]->maxAgeSeconds, 100);
    }

    public function testFromDeleteBucketCors()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketCorsRequest();
            $input = BucketCors::fromDeleteBucketCors($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketCorsRequest('bucket-123');
        $input = BucketCors::fromDeleteBucketCors($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketCors()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCors::toDeleteBucketCors($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketCors::toDeleteBucketCors($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromOptionObject()
    {
        // miss required field
        try {
            $request = new Models\OptionObjectRequest();
            $input = BucketCors::fromOptionObject($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\OptionObjectRequest('bucket-123');
            $input = BucketCors::fromOptionObject($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\OptionObjectRequest('bucket-123', 'key-123');
            $input = BucketCors::fromOptionObject($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, origin", (string)$e);
        }

        try {
            $request = new Models\OptionObjectRequest('bucket-123', 'key-123', 'http://www.example.com');
            $input = BucketCors::fromOptionObject($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessControlRequestMethod", (string)$e);
        }

        $request = new Models\OptionObjectRequest('bucket-123', 'key-123', 'http://www.example.com', 'PUT');
        $input = BucketCors::fromOptionObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToOptionObject()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCors::toOptionObject($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'Access-Control-Allow-Origin' => 'http://www.example.com', 'Access-Control-Allow-Methods' => 'PUT', 'Access-Control-Allow-Headers' => 'x-oss-allow', 'Access-Control-Expose-Headers' => 'x-oss-test1,x-oss-test2', 'Access-Control-Max-Age' => "60"]
        );
        $result = BucketCors::toOptionObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(6, count($result->headers));
        $this->assertEquals('http://www.example.com', $result->accessControlAllowOrigin);
        $this->assertEquals('x-oss-test1,x-oss-test2', $result->accessControlExposeHeaders);
        $this->assertEquals('x-oss-allow', $result->accessControlAllowHeaders);
        $this->assertEquals('PUT', $result->accessControlAllowMethods);
        $this->assertEquals(60, $result->accessControlMaxAge);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
