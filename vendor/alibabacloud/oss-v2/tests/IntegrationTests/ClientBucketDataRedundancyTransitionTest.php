<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketDataRedundancyTransitionTest extends TestIntegration
{
    public function testBucketDataRedundancyTransition()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // CreateBucketDataRedundancyTransition
        $createResult = $client->createBucketDataRedundancyTransition(new Oss\Models\CreateBucketDataRedundancyTransitionRequest(
            $bucketName,
            targetRedundancyType: 'ZRS'
        ));
        $this->assertEquals(200, $createResult->statusCode);
        $this->assertEquals('OK', $createResult->status);
        $this->assertEquals(True, count($createResult->headers) > 0);
        $this->assertEquals(24, strlen($createResult->requestId));

        // GetBucketDataRedundancyTransition
        $getResult = $client->getBucketDataRedundancyTransition(new Oss\Models\GetBucketDataRedundancyTransitionRequest(
            $bucketName,
            redundancyTransitionTaskid: $createResult->bucketDataRedundancyTransition->taskId
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // ListBucketDataRedundancyTransition
        $listResult = $client->listBucketDataRedundancyTransition(new Oss\Models\ListBucketDataRedundancyTransitionRequest(
            $bucketName,
        ));
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // ListUserDataRedundancyTransition
        $listResult = $client->listUserDataRedundancyTransition(new Oss\Models\ListUserDataRedundancyTransitionRequest());
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // DeleteBucketDataRedundancyTransition
        $delResult = $client->deleteBucketDataRedundancyTransition(new Oss\Models\DeleteBucketDataRedundancyTransitionRequest(
            $bucketName, $createResult->bucketDataRedundancyTransition->taskId
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketDataRedundancyTransitionFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        try {
            $createResult = $client->createBucketDataRedundancyTransition(new Oss\Models\CreateBucketDataRedundancyTransitionRequest(
                $bucketName,
                targetRedundancyType: 'ZRS'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CreateBucketDataRedundancyTransition', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getResult = $client->getBucketDataRedundancyTransition(new Oss\Models\GetBucketDataRedundancyTransitionRequest(
                $bucketName, '123'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketDataRedundancyTransition', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteBucketDataRedundancyTransition(new Oss\Models\DeleteBucketDataRedundancyTransitionRequest(
                $bucketName, '123'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketDataRedundancyTransition', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $listResult = $client->listBucketDataRedundancyTransition(new Oss\Models\ListBucketDataRedundancyTransitionRequest(
                $bucketName,
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListBucketDataRedundancyTransition', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $client = $this->getInvalidAkClient();
            $listResult = $client->listUserDataRedundancyTransition(new Oss\Models\ListUserDataRedundancyTransitionRequest());
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListUserDataRedundancyTransition', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }
}