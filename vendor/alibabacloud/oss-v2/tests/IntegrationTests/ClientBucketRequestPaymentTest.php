<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketRequestPaymentTest extends TestIntegration
{
    public function testBucketRequestPayment()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketRequestPayment
        $putResult = $client->putBucketRequestPayment(new Oss\Models\PutBucketRequestPaymentRequest(
            $bucketName,
            new Oss\Models\RequestPaymentConfiguration(
                'Requester'
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketRequestPayment
        $getResult = $client->getBucketRequestPayment(new Oss\Models\GetBucketRequestPaymentRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));
    }

    public function testBucketRequestPaymentFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketRequestPayment
        try {
            $putResult = $client->putBucketRequestPayment(new Oss\Models\PutBucketRequestPaymentRequest(
                $bucketName,
                new Oss\Models\RequestPaymentConfiguration(
                    'Requester'
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketRequestPayment', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketRequestPayment
        try {
            $getResult = $client->getBucketRequestPayment(new Oss\Models\GetBucketRequestPaymentRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketRequestPayment', $e);
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