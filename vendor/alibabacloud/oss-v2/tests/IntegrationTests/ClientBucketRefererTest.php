<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketRefererTest extends TestIntegration
{
    public function testBucketReferer()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketReferer
        $putResult = $client->putBucketReferer(new Oss\Models\PutBucketRefererRequest(
            $bucketName,
            refererConfiguration: new Oss\Models\RefererConfiguration(
                allowEmptyReferer: false,
                refererList: new Oss\Models\RefererList([""]),
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketReferer
        $getResult = $client->getBucketReferer(new Oss\Models\GetBucketRefererRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
    }

    public function testBucketRefererFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketReferer
        try {
            $putResult = $client->putBucketReferer(new Oss\Models\PutBucketRefererRequest(
                $bucketName,
                refererConfiguration: new Oss\Models\RefererConfiguration(
                    allowEmptyReferer: false,
                    refererList: new Oss\Models\RefererList([""]),
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketReferer', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketReferer
        try {
            $getResult = $client->getBucketReferer(new Oss\Models\GetBucketRefererRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketReferer', $e);
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