<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketPolicy;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketPolicyTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketPolicy()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketPolicyRequest();
            $input = BucketPolicy::fromPutBucketPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketPolicyRequest('bucket-123');
            $input = BucketPolicy::fromPutBucketPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, body.", (string)$e);
        }

        // all settings
        $body = '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["1234567890"],"Resource":["acs:oss:*:1234567890:*/*"]}]}';
        $request = new Models\PutBucketPolicyRequest(
            bucket: 'bucket-123',
            body: $body
        );
        $json = <<<BBB
{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["1234567890"],"Resource":["acs:oss:*:1234567890:*/*"]}]}
BBB;
        $input = BucketPolicy::fromPutBucketPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals($json, $input->getBody()->getContents());
        $this->assertEquals(base64_encode(md5($input->getBody(), true)), $input->getHeaders()['content-md5']);
    }

    public function testToPutBucketPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPolicy::toPutBucketPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketPolicy::toPutBucketPolicy($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketPolicy()
    {
        // miss required field
        try {
            $request = new Models\GetBucketPolicyRequest();
            $input = BucketPolicy::fromGetBucketPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketPolicyRequest('bucket-123');
        $input = BucketPolicy::fromGetBucketPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPolicy::toGetBucketPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor('{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["1234567890"],"Resource":["acs:oss:*:1234567890:*/*"]}]}')
        );
        $result = BucketPolicy::toGetBucketPolicy($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertCount(2, $result->headers);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["1234567890"],"Resource":["acs:oss:*:1234567890:*/*"]}]}', $result->body);
    }

    public function testFromGetBucketPolicyStatus()
    {
        // miss required field
        try {
            $request = new Models\GetBucketPolicyStatusRequest();
            $input = BucketPolicy::fromGetBucketPolicyStatus($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketPolicyStatusRequest('bucket-123');
        $input = BucketPolicy::fromGetBucketPolicyStatus($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketPolicyStatus()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPolicy::toGetBucketPolicyStatus($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<PolicyStatus>
   <IsPublic>true</IsPublic>
</PolicyStatus>')
        );
        $result = BucketPolicy::toGetBucketPolicyStatus($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertCount(2, $result->headers);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertTrue($result->policyStatus->isPublic);
    }

    public function testFromDeleteBucketPolicy()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketPolicyRequest();
            $input = BucketPolicy::fromDeleteBucketPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketPolicyRequest('bucket-123');
        $input = BucketPolicy::fromDeleteBucketPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketPolicy::toDeleteBucketPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketPolicy::toDeleteBucketPolicy($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }
}
