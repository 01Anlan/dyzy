<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketLogging;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketLoggingTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketLogging()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketLoggingRequest();
            $input = BucketLogging::fromPutBucketLogging($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketLoggingRequest('bucket-123');
            $input = BucketLogging::fromPutBucketLogging($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucketLoggingStatus", (string)$e);
        }

        $request = new Models\PutBucketLoggingRequest('bucket-123', new Models\BucketLoggingStatus(new Models\LoggingEnabled(
            targetBucket: 'TargetBucket', targetPrefix: 'TargetPrefix'
        )));
        $input = BucketLogging::fromPutBucketLogging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketLoggingStatus><LoggingEnabled><TargetBucket>TargetBucket</TargetBucket><TargetPrefix>TargetPrefix</TargetPrefix></LoggingEnabled></BucketLoggingStatus>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\PutBucketLoggingRequest('bucket-123', new Models\BucketLoggingStatus(new Models\LoggingEnabled(
            targetBucket: 'TargetBucket', targetPrefix: 'TargetPrefix', loggingRole: 'AliyunOSSLoggingDefaultRole'
        )));
        $input = BucketLogging::fromPutBucketLogging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketLoggingStatus><LoggingEnabled><TargetBucket>TargetBucket</TargetBucket><TargetPrefix>TargetPrefix</TargetPrefix><LoggingRole>AliyunOSSLoggingDefaultRole</LoggingRole></LoggingEnabled></BucketLoggingStatus>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketLogging()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toPutBucketLogging($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLogging::toPutBucketLogging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketLogging()
    {
        // miss required field
        try {
            $request = new Models\GetBucketLoggingRequest();
            $input = BucketLogging::fromGetBucketLogging($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketLoggingRequest('bucket-123');
        $input = BucketLogging::fromGetBucketLogging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketLogging()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toGetBucketLogging($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketLoggingStatus>
  <LoggingEnabled>
        <TargetBucket>bucket-log</TargetBucket>
        <TargetPrefix>prefix-access_log</TargetPrefix>
    </LoggingEnabled>
</BucketLoggingStatus>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketLogging::toGetBucketLogging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('bucket-log', $result->bucketLoggingStatus->loggingEnabled->targetBucket);
        $this->assertEquals('prefix-access_log', $result->bucketLoggingStatus->loggingEnabled->targetPrefix);


        $output = new OperationOutput();
        $result = BucketLogging::toGetBucketLogging($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketLoggingStatus>
  <LoggingEnabled>
        <TargetBucket>bucket-log</TargetBucket>
        <TargetPrefix>prefix-access_log</TargetPrefix>
        <LoggingRole>AliyunOSSLoggingDefaultRole</LoggingRole>
    </LoggingEnabled>
</BucketLoggingStatus>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketLogging::toGetBucketLogging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('bucket-log', $result->bucketLoggingStatus->loggingEnabled->targetBucket);
        $this->assertEquals('prefix-access_log', $result->bucketLoggingStatus->loggingEnabled->targetPrefix);
        $this->assertEquals('AliyunOSSLoggingDefaultRole', $result->bucketLoggingStatus->loggingEnabled->loggingRole);
    }

    public function testFromDeleteBucketLogging()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketLoggingRequest();
            $input = BucketLogging::fromDeleteBucketLogging($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketLoggingRequest('bucket-123');
        $input = BucketLogging::fromDeleteBucketLogging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketLogging()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toDeleteBucketLogging($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLogging::toDeleteBucketLogging($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromPutUserDefinedLogFieldsConfig()
    {
        // miss required field
        try {
            $request = new Models\PutUserDefinedLogFieldsConfigRequest();
            $input = BucketLogging::fromPutUserDefinedLogFieldsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutUserDefinedLogFieldsConfigRequest('bucket-123');
            $input = BucketLogging::fromPutUserDefinedLogFieldsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, userDefinedLogFieldsConfiguration", (string)$e);
        }

        $request = new Models\PutUserDefinedLogFieldsConfigRequest('bucket-123', new Models\UserDefinedLogFieldsConfiguration(
            new Models\LoggingParamSet(array("param1", "param2")),
            new Models\LoggingHeaderSet(array("header1", "header2", "header3"))
        ));
        $input = BucketLogging::fromPutUserDefinedLogFieldsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><UserDefinedLogFieldsConfiguration><ParamSet><parameter>param1</parameter><parameter>param2</parameter></ParamSet><HeaderSet><header>header1</header><header>header2</header><header>header3</header></HeaderSet></UserDefinedLogFieldsConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutUserDefinedLogFieldsConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toPutUserDefinedLogFieldsConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLogging::toPutUserDefinedLogFieldsConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetUserDefinedLogFieldsConfig()
    {
        // miss required field
        try {
            $request = new Models\GetUserDefinedLogFieldsConfigRequest();
            $input = BucketLogging::fromGetUserDefinedLogFieldsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetUserDefinedLogFieldsConfigRequest('bucket-123');
        $input = BucketLogging::fromGetUserDefinedLogFieldsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetUserDefinedLogFieldsConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toGetUserDefinedLogFieldsConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<UserDefinedLogFieldsConfiguration>
	<HeaderSet>
		<header>header1</header>
		<header>header2</header>
		<header>header3</header>
	</HeaderSet>
	<ParamSet>
		<parameter>param1</parameter>
		<parameter>param2</parameter>
	</ParamSet>
</UserDefinedLogFieldsConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketLogging::toGetUserDefinedLogFieldsConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('header1', $result->userDefinedLogFieldsConfiguration->headerSet->headers[0]);
        $this->assertEquals('header3', $result->userDefinedLogFieldsConfiguration->headerSet->headers[2]);
        $this->assertEquals('param2', $result->userDefinedLogFieldsConfiguration->paramSet->parameters[1]);
    }

    public function testFromUserDefinedLogFieldsConfig()
    {
        // miss required field
        try {
            $request = new Models\DeleteUserDefinedLogFieldsConfigRequest();
            $input = BucketLogging::fromDeleteUserDefinedLogFieldsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteUserDefinedLogFieldsConfigRequest('bucket-123');
        $input = BucketLogging::fromDeleteUserDefinedLogFieldsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToUserDefinedLogFieldsConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketLogging::toDeleteUserDefinedLogFieldsConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketLogging::toDeleteUserDefinedLogFieldsConfig($output);
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
