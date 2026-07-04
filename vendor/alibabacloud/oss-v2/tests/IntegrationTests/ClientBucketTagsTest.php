<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketTagsTest extends TestIntegration
{
    public function testBucketTags()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketTags
        $putResult = $client->putBucketTags(new Oss\Models\PutBucketTagsRequest(
            $bucketName,
            tagging: new Oss\Models\Tagging(
                tagSet: new Oss\Models\TagSet(
                    [new Oss\Models\Tag(key: 'key1', value: 'value1'), new Oss\Models\Tag(key: 'key2', value: 'value2')])
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketTags
        $getResult = $client->getBucketTags(new Oss\Models\GetBucketTagsRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketTags
        $delResult = $client->deleteBucketTags(new Oss\Models\DeleteBucketTagsRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketTagsFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketTags
        try {
            $putResult = $client->putBucketTags(new Oss\Models\PutBucketTagsRequest(
                $bucketName,
                tagging: new Oss\Models\Tagging(
                    tagSet: new Oss\Models\TagSet(
                        [new Oss\Models\Tag(key: 'key1', value: 'value1'), new Oss\Models\Tag(key: 'key2', value: 'value2')])
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketTags', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketTags
        try {
            $getResult = $client->getBucketTags(new Oss\Models\GetBucketTagsRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketTags', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketTags
        try {
            $delResult = $client->deleteBucketTags(new Oss\Models\DeleteBucketTagsRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketTags', $e);
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