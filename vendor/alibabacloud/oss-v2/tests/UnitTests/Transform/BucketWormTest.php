<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketWorm;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketWormTest extends \PHPUnit\Framework\TestCase
{
    public function testFromInitiateBucketWorm()
    {
        // miss required field 
        try {
            $request = new Models\InitiateBucketWormRequest();
            $input = BucketWorm::fromInitiateBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\InitiateBucketWormRequest('bucket-123');
            $input = BucketWorm::fromInitiateBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, initiateWormConfiguration", (string)$e);
        }

        // all settings
        $request = new Models\InitiateBucketWormRequest(
            bucket: 'bucket-123',
            initiateWormConfiguration: new Models\InitiateWormConfiguration(retentionPeriodInDays: 3)
        );
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><InitiateWormConfiguration><RetentionPeriodInDays>3</RetentionPeriodInDays></InitiateWormConfiguration>
BBB;

        $input = BucketWorm::fromInitiateBucketWorm($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals(base64_encode(md5($input->getBody(), true)), $input->getHeaders()['content-md5']);
    }

    public function testToInitiateBucketWorm()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketWorm::toInitiateBucketWorm($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'x-oss-worm-id' => '234']
        );
        $result = BucketWorm::toInitiateBucketWorm($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('234', $result->wormId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromAbortBucketWorm()
    {
        // miss required field
        try {
            $request = new Models\AbortBucketWormRequest();
            $input = BucketWorm::fromAbortBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\AbortBucketWormRequest('bucket-123');
        $input = BucketWorm::fromAbortBucketWorm($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToAbortBucketWorm()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketWorm::toAbortBucketWorm($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketWorm::toAbortBucketWorm($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromCompleteBucketWorm()
    {
        // miss required field
        try {
            $request = new Models\CompleteBucketWormRequest();
            $input = BucketWorm::fromCompleteBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CompleteBucketWormRequest('bucket-123');
            $input = BucketWorm::fromCompleteBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, wormId", (string)$e);
        }

        $request = new Models\CompleteBucketWormRequest('bucket-123', 'wormId-123');
        $input = BucketWorm::fromCompleteBucketWorm($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('wormId-123', $input->getParameters()['wormId']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToCompleteBucketWorm()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketWorm::toCompleteBucketWorm($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketWorm::toCompleteBucketWorm($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromExtendBucketWorm()
    {
        // miss required field
        try {
            $request = new Models\ExtendBucketWormRequest();
            $input = BucketWorm::fromExtendBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\ExtendBucketWormRequest('bucket-123');
            $input = BucketWorm::fromExtendBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, wormId", (string)$e);
        }

        try {
            $request = new Models\ExtendBucketWormRequest('bucket-123', 'wormId-123');
            $input = BucketWorm::fromExtendBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString($e->getMessage(), "missing required field, extendWormConfiguration.", (string)$e);
        }

        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ExtendWormConfiguration><RetentionPeriodInDays>3</RetentionPeriodInDays></ExtendWormConfiguration>
BBB;
        $request = new Models\ExtendBucketWormRequest('bucket-123', 'wormId-123', new Models\ExtendWormConfiguration(3));
        $input = BucketWorm::fromExtendBucketWorm($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('wormId-123', $input->getParameters()['wormId']);
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
        $this->assertEquals(base64_encode(md5($input->getBody(), true)), $input->getHeaders()['content-md5']);
    }

    public function testToExtendBucketWorm()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketWorm::toExtendBucketWorm($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketWorm::toCompleteBucketWorm($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketWorm()
    {
        // miss required field
        try {
            $request = new Models\GetBucketWormRequest();
            $input = BucketWorm::fromGetBucketWorm($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketWormRequest('bucket-123');
        $input = BucketWorm::fromGetBucketWorm($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketWorm()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketWorm::toGetBucketWorm($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor('<WormConfiguration>
  <WormId>1666E2CFB2B3418****</WormId>
  <State>Locked</State>
  <RetentionPeriodInDays>1</RetentionPeriodInDays>
  <CreationDate>2020-10-15T15:50:32</CreationDate>
</WormConfiguration>')
        );
        $result = BucketWorm::toGetBucketWorm($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertCount(2, $result->headers);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('1666E2CFB2B3418****', $result->wormConfiguration->wormId);
        $this->assertEquals('Locked', $result->wormConfiguration->state);
        $this->assertEquals(1, $result->wormConfiguration->retentionPeriodInDays);
        $this->assertEquals("2020-10-15T15:50:32", $result->wormConfiguration->creationDate);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
