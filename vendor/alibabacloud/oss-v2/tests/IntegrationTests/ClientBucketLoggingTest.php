<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketLoggingTest extends TestIntegration
{
    public function testBucketLogging()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketLogging
        $putResult = $client->putBucketLogging(new Oss\Models\PutBucketLoggingRequest(
            $bucketName,
            new Oss\Models\BucketLoggingStatus(
                new Oss\Models\LoggingEnabled(
                    targetBucket: $bucketName, targetPrefix: 'TargetPrefix', loggingRole: 'AliyunOSSLoggingDefaultRole'
                )
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketLogging
        $getResult = $client->getBucketLogging(new Oss\Models\GetBucketLoggingRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketLogging
        $delResult = $client->deleteBucketLogging(new Oss\Models\DeleteBucketLoggingRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));

        // PutUserDefinedLogFieldsConfig
        $putResult = $client->putUserDefinedLogFieldsConfig(new Oss\Models\PutUserDefinedLogFieldsConfigRequest(
            $bucketName,
            new Oss\Models\UserDefinedLogFieldsConfiguration(
                paramSet: new Oss\Models\LoggingParamSet(['param1', 'param2']), headerSet: new Oss\Models\LoggingHeaderSet(['header1', 'header2'])
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetUserDefinedLogFieldsConfig
        $getResult = $client->getUserDefinedLogFieldsConfig(new Oss\Models\GetUserDefinedLogFieldsConfigRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteUserDefinedLogFieldsConfig
        $delResult = $client->deleteUserDefinedLogFieldsConfig(new Oss\Models\DeleteUserDefinedLogFieldsConfigRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketLoggingFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        try {
            $putResult = $client->putBucketLogging(new Oss\Models\PutBucketLoggingRequest(
                $bucketName,
                new Oss\Models\BucketLoggingStatus(
                    new Oss\Models\LoggingEnabled(
                        targetBucket: $bucketName, targetPrefix: 'TargetPrefix'
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketLogging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getResult = $client->getBucketLogging(new Oss\Models\GetBucketLoggingRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketLogging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteBucketLogging(new Oss\Models\DeleteBucketLoggingRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketLogging', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $putResult = $client->putUserDefinedLogFieldsConfig(new Oss\Models\PutUserDefinedLogFieldsConfigRequest(
                $bucketName,
                new Oss\Models\UserDefinedLogFieldsConfiguration(
                    paramSet: new Oss\Models\LoggingParamSet(['param1', 'param2']), headerSet: new Oss\Models\LoggingHeaderSet(['header1', 'header2'])
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutUserDefinedLogFieldsConfig', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getResult = $client->getUserDefinedLogFieldsConfig(new Oss\Models\GetUserDefinedLogFieldsConfigRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetUserDefinedLogFieldsConfig', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteUserDefinedLogFieldsConfig(new Oss\Models\DeleteUserDefinedLogFieldsConfigRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteUserDefinedLogFieldsConfig', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }
}