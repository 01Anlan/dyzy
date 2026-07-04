<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketResourceGroupTest extends TestIntegration
{
    public function testBucketResourceGroup()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // GetBucketResourceGroup
        $getResult = $client->getBucketResourceGroup(new Oss\Models\GetBucketResourceGroupRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // PutBucketResourceGroup
        $putResult = $client->putBucketResourceGroup(new Oss\Models\PutBucketResourceGroupRequest(
            $bucketName,
            new Oss\Models\BucketResourceGroupConfiguration(
                $getResult->bucketResourceGroupConfiguration->resourceGroupId
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));
    }

    public function testBucketResourceGroupFail()
    {
        $client = $this->getInvalidAkClient();
        $bucketName = self::$bucketName;

        // PutBucketResourceGroup
        try {
            $putResult = $client->putBucketResourceGroup(new Oss\Models\PutBucketResourceGroupRequest(
                $bucketName,
                new Oss\Models\BucketResourceGroupConfiguration('rg-123')
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketResourceGroup', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketResourceGroup
        try {
            $getResult = $client->getBucketResourceGroup(new Oss\Models\GetBucketResourceGroupRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketResourceGroup', $e);
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