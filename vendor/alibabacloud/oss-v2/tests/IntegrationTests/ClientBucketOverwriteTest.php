<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketOverwriteTest extends TestIntegration
{
    public function testBucketOverwriteConfig()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketOverwriteConfig
        $putResult = $client->putBucketOverwriteConfig(new Oss\Models\PutBucketOverwriteConfigRequest(
            $bucketName,
            new Oss\Models\OverwriteConfiguration(
                array(
                    new Oss\Models\OverwriteRule(
                        'forbid', null, null, null, 'rule-001'
                    )
                )
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketOverwriteConfig
        $getResult = $client->getBucketOverwriteConfig(new Oss\Models\GetBucketOverwriteConfigRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketOverwriteConfig
        $delResult = $client->deleteBucketOverwriteConfig(new Oss\Models\DeleteBucketOverwriteConfigRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketOverwriteConfigFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        try {
            $putResult = $client->putBucketOverwriteConfig(new Oss\Models\PutBucketOverwriteConfigRequest(
                $bucketName,
                new Oss\Models\OverwriteConfiguration(
                    array(
                        new Oss\Models\OverwriteRule(
                            'forbid', null, null, null, 'rule-001'
                        )
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketOverwriteConfig', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('MalformedXML', $se->getErrorCode());
                $this->assertEquals(400, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getResult = $client->getBucketOverwriteConfig(new Oss\Models\GetBucketOverwriteConfigRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketOverwriteConfig', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteBucketOverwriteConfig(new Oss\Models\DeleteBucketOverwriteConfigRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketOverwriteConfig', $e);
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