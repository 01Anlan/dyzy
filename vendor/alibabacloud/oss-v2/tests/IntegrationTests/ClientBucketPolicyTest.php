<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketPolicyTest extends TestIntegration
{
    public function testBucketPolicy()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $client->putBucketPublicAccessBlock(new Oss\Models\PutBucketPublicAccessBlockRequest(
            $bucketName,
            new Oss\Models\PublicAccessBlockConfiguration(
                false
            )
        ));

        // PutBucketPolicy
        $putResult = $client->putBucketPolicy(new Oss\Models\PutBucketPolicyRequest(
            $bucketName,
            '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Allow","Resource":["acs:oss:*:*:*"]}]}'
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketPolicy
        $getResult = $client->getBucketPolicy(new Oss\Models\GetBucketPolicyRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // GetBucketPolicyStatus
        $getResult2 = $client->getBucketPolicyStatus(new Oss\Models\GetBucketPolicyStatusRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult2->statusCode);
        $this->assertEquals('OK', $getResult2->status);
        $this->assertEquals(True, count($getResult2->headers) > 0);
        $this->assertEquals(24, strlen($getResult2->requestId));

        // DeleteBucketPolicy
        $delResult = $client->deleteBucketPolicy(new Oss\Models\DeleteBucketPolicyRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketTransferAccelerationFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketPolicy
        try {
            $putResult = $client->putBucketPolicy(new Oss\Models\PutBucketPolicyRequest(
                $bucketName,
                '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Allow","Resource":["acs:oss:*:*:*"]}]}'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketPolicy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketPolicy
        try {
            $getResult = $client->getBucketPolicy(new Oss\Models\GetBucketPolicyRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketPolicy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketPolicyStatus
        try {
            $getResult = $client->getBucketPolicyStatus(new Oss\Models\GetBucketPolicyStatusRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketPolicyStatus', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketPolicy
        try {
            $delResult = $client->deleteBucketPolicy(new Oss\Models\DeleteBucketPolicyRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketPolicy', $e);
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