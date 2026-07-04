<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketEncryptionTest extends TestIntegration
{
    public function testBucketEncryption()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        // PutBucketEncryption
        $putResult = $client->putBucketEncryption(new Oss\Models\PutBucketEncryptionRequest(
            $bucketName,
            new Oss\Models\ServerSideEncryptionRule(
                new Oss\Models\ApplyServerSideEncryptionByDefault(
                    sseAlgorithm: 'KMS',
                    kmsDataEncryption: 'SM4'
                ))
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketEncryption
        $getResult = $client->getBucketEncryption(new Oss\Models\GetBucketEncryptionRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketEncryption
        $delResult = $client->deleteBucketEncryption(new Oss\Models\DeleteBucketEncryptionRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketEncryptionFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketEncryption
        try {
            $putResult = $client->putBucketEncryption(new Oss\Models\PutBucketEncryptionRequest(
                $bucketName,
                new Oss\Models\ServerSideEncryptionRule(
                    new Oss\Models\ApplyServerSideEncryptionByDefault(
                        sseAlgorithm: 'KMS',
                        kmsDataEncryption: 'SM4'
                    ))
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketEncryption', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketEncryption
        try {
            $getResult = $client->getBucketEncryption(new Oss\Models\GetBucketEncryptionRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketEncryption', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketEncryption
        try {
            $delResult = $client->deleteBucketEncryption(new Oss\Models\DeleteBucketEncryptionRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketEncryption', $e);
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