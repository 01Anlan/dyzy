<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

class ClientBucketLifecycleTest extends TestIntegration
{
    public function testBucketLifecycle()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketLifecycle
        $putResult = $client->putBucketLifecycle(new Oss\Models\PutBucketLifecycleRequest(
            $bucketName,
            lifecycleConfiguration: new LifecycleConfiguration(
                array(new Oss\Models\LifecycleRule(
                    prefix: 'log/',
                    transitions: array(
                    new Oss\Models\LifecycleRuleTransition(
                        days: 30,
                        storageClass: 'IA'
                    )
                ),
                    id: 'rule',
                    status: 'Enabled'
                ))
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketLifecycle
        $getResult = $client->getBucketLifecycle(new Oss\Models\GetBucketLifecycleRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketLifecycle
        $delResult = $client->deleteBucketLifecycle(new Oss\Models\DeleteBucketLifecycleRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketLifecycleFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketLifecycle
        try {
            $putResult = $client->putBucketLifecycle(new Oss\Models\PutBucketLifecycleRequest(
                $bucketName,
                lifecycleConfiguration: new LifecycleConfiguration(
                    array(new Oss\Models\LifecycleRule(
                        prefix: 'log/',
                        transitions: array(
                        new Oss\Models\LifecycleRuleTransition(
                            days: 30,
                            storageClass: 'IA'
                        )
                    ),
                        id: 'rule',
                        status: 'Enabled'
                    ))
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketLifecycle', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketLifecycle
        try {
            $getResult = $client->getBucketLifecycle(new Oss\Models\GetBucketLifecycleRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketLifecycle', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketLifecycle
        try {
            $delResult = $client->deleteBucketLifecycle(new Oss\Models\DeleteBucketLifecycleRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketLifecycle', $e);
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