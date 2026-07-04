<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketWormTest extends TestIntegration
{
    public function testBucketWorm()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // InitiateBucketWorm
        $initResult = $client->initiateBucketWorm(new Oss\Models\InitiateBucketWormRequest(
            $bucketName,
            new Oss\Models\InitiateWormConfiguration(1)
        ));
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertEquals('OK', $initResult->status);
        $this->assertEquals(True, count($initResult->headers) > 0);
        $this->assertEquals(24, strlen($initResult->requestId));
        $this->assertNotEmpty($initResult->wormId);

        // GetBucketWorm
        $getResult = $client->getBucketWorm(new Oss\Models\GetBucketWormRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
        $this->assertEquals($initResult->wormId, $getResult->wormConfiguration->wormId);
        $this->assertNotEmpty($getResult->wormConfiguration->creationDate);
        $this->assertNotEmpty($getResult->wormConfiguration->retentionPeriodInDays);
        $this->assertNotEmpty($getResult->wormConfiguration->state);

        // AbortBucketWorm
        $abortResult = $client->abortBucketWorm(new Oss\Models\AbortBucketWormRequest(
            $bucketName
        ));
        $this->assertEquals(204, $abortResult->statusCode);
        $this->assertEquals('No Content', $abortResult->status);
        $this->assertEquals(True, count($abortResult->headers) > 0);
        $this->assertEquals(24, strlen($abortResult->requestId));

        // CompleteBucketWorm
        try {
            $result = $client->completeBucketWorm(new Oss\Models\CompleteBucketWormRequest(
                $bucketName,
                $initResult->wormId
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CompleteBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchWORMConfiguration', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // ExtendBucketWorm
        try {
            $result = $client->extendBucketWorm(new Oss\Models\ExtendBucketWormRequest(
                $bucketName,
                $initResult->wormId,
                new Oss\Models\ExtendWormConfiguration(2)
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ExtendBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchWORMConfiguration', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }

    public function testBucketWormFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // InitiateBucketWorm
        try {
            $initResult = $client->initiateBucketWorm(new Oss\Models\InitiateBucketWormRequest(
                $bucketName,
                new Oss\Models\InitiateWormConfiguration(1)
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error InitiateBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketWorm
        try {
            $getResult = $client->getBucketWorm(new Oss\Models\GetBucketWormRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // AbortBucketWorm
        try {
            $abortResult = $client->abortBucketWorm(new Oss\Models\AbortBucketWormRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error AbortBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        $wormId = 'not-exist-wormId';

        // CompleteBucketWorm
        try {
            $result = $client->completeBucketWorm(new Oss\Models\CompleteBucketWormRequest(
                $bucketName,
                $wormId
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CompleteBucketWorm', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // ExtendBucketWorm
        try {
            $result = $client->extendBucketWorm(new Oss\Models\ExtendBucketWormRequest(
                $bucketName,
                $wormId,
                new Oss\Models\ExtendWormConfiguration(2)
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ExtendBucketWorm', $e);
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