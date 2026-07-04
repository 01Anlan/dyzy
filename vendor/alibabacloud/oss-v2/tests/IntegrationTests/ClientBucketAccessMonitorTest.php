<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketAccessMonitorTest extends TestIntegration
{
    public function testBucketAccessMonitor()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketAccessMonitor
        $putResult = $client->putBucketAccessMonitor(new Oss\Models\PutBucketAccessMonitorRequest(
            $bucketName,
            new Oss\Models\AccessMonitorConfiguration(
                status: Oss\Models\AccessMonitorStatusType::ENABLED
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketAccessMonitor
        $getResult = $client->getBucketAccessMonitor(new Oss\Models\GetBucketAccessMonitorRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
    }

    public function testBucketAccessMonitorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketAccessMonitor
        try {
            $putResult = $client->putBucketAccessMonitor(new Oss\Models\PutBucketAccessMonitorRequest(
                $bucketName,
                new Oss\Models\AccessMonitorConfiguration(
                    status: Oss\Models\AccessMonitorStatusType::ENABLED
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketAccessMonitor', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketAccessMonitor
        try {
            $getResult = $client->getBucketAccessMonitor(new Oss\Models\GetBucketAccessMonitorRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketAccessMonitor', $e);
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