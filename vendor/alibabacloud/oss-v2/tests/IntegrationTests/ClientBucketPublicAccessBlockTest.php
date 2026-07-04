<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketPublicAccessBlockTest extends TestIntegration
{
    public function testBucketPublicAccessBlock()
    {
        $client = $this->getDefaultClient();
        $bucket = self::$bucketName;
        // PutBucketPublicAccessBlock
        $putResult = $client->putBucketPublicAccessBlock(new Oss\Models\PutBucketPublicAccessBlockRequest(
            $bucket, new Oss\Models\PublicAccessBlockConfiguration(true)
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketPublicAccessBlock
        $getResult = $client->getBucketPublicAccessBlock(new Oss\Models\GetBucketPublicAccessBlockRequest($bucket));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketPublicAccessBlock
        $delResult = $client->deleteBucketPublicAccessBlock(new Oss\Models\DeleteBucketPublicAccessBlockRequest($bucket));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketPublicAccessBlockFail()
    {
        $client = $this->getInvalidAkClient();
        $bucketName = self::$bucketName;
        // PutBucketPublicAccessBlock
        try {
            $putResult = $client->putBucketPublicAccessBlock(new Oss\Models\PutBucketPublicAccessBlockRequest(
                $bucketName, new Oss\Models\PublicAccessBlockConfiguration(true)
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketPublicAccessBlock', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketPublicAccessBlock
        try {
            $getResult = $client->getBucketPublicAccessBlock(new Oss\Models\GetBucketPublicAccessBlockRequest($bucketName));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketPublicAccessBlock', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketPublicAccessBlock
        try {
            $delResult = $client->deleteBucketPublicAccessBlock(new Oss\Models\DeleteBucketPublicAccessBlockRequest($bucketName));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketPublicAccessBlock', $e);
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