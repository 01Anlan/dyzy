<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketTransferAccelerationTest extends TestIntegration
{
    public function testBucketTransferAcceleration()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketTransferAcceleration
        $putResult = $client->putBucketTransferAcceleration(new Oss\Models\PutBucketTransferAccelerationRequest(
            $bucketName,
            new Oss\Models\TransferAccelerationConfiguration(
                true
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketTransferAcceleration
        $getResult = $client->getBucketTransferAcceleration(new Oss\Models\GetBucketTransferAccelerationRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
    }

    public function testBucketTransferAccelerationFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketTransferAcceleration
        try {
            $putResult = $client->putBucketTransferAcceleration(new Oss\Models\PutBucketTransferAccelerationRequest(
                $bucketName,
                new Oss\Models\TransferAccelerationConfiguration(
                    true
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketTransferAcceleration', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketTransferAcceleration
        try {
            $getResult = $client->getBucketTransferAcceleration(new Oss\Models\GetBucketTransferAccelerationRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketTransferAcceleration', $e);
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