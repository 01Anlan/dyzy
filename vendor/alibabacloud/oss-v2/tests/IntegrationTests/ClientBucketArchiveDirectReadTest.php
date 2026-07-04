<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketArchiveDirectReadTest extends TestIntegration
{
    public function testBucketArchiveDirectRead()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketArchiveDirectRead
        $putResult = $client->putBucketArchiveDirectRead(new Oss\Models\PutBucketArchiveDirectReadRequest(
            $bucketName,
            new Oss\Models\ArchiveDirectReadConfiguration(
                true
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketArchiveDirectRead
        $getResult = $client->getBucketArchiveDirectRead(new Oss\Models\GetBucketArchiveDirectReadRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
    }

    public function testBucketArchiveDirectReadFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketArchiveDirectRead
        try {
            $putResult = $client->putBucketArchiveDirectRead(new Oss\Models\PutBucketArchiveDirectReadRequest(
                $bucketName,
                new Oss\Models\ArchiveDirectReadConfiguration(
                    true
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketArchiveDirectRead', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketArchiveDirectRead
        try {
            $getResult = $client->getBucketArchiveDirectRead(new Oss\Models\GetBucketArchiveDirectReadRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketArchiveDirectRead', $e);
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