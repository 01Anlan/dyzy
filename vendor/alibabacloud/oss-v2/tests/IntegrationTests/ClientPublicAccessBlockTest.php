<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientPublicAccessBlockTest extends TestIntegration
{
    public function testPublicAccessBlock()
    {
        $client = $this->getDefaultClient();
        // PutPublicAccessBlock
        $putResult = $client->putPublicAccessBlock(new Oss\Models\PutPublicAccessBlockRequest(
            new Oss\Models\PublicAccessBlockConfiguration(true)
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetPublicAccessBlock
        $getResult = $client->getPublicAccessBlock(new Oss\Models\GetPublicAccessBlockRequest());
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeletePublicAccessBlock
        $delResult = $client->deletePublicAccessBlock(new Oss\Models\DeletePublicAccessBlockRequest());
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testPublicAccessBlockFail()
    {
        $client = $this->getInvalidAkClient();
        // PutPublicAccessBlock
        try {
            $putResult = $client->putPublicAccessBlock(new Oss\Models\PutPublicAccessBlockRequest(
                new Oss\Models\PublicAccessBlockConfiguration(true)
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutPublicAccessBlock', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetPublicAccessBlock
        try {
            $getResult = $client->getPublicAccessBlock(new Oss\Models\GetPublicAccessBlockRequest());
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetPublicAccessBlock', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeletePublicAccessBlock
        try {
            $delResult = $client->deletePublicAccessBlock(new Oss\Models\DeletePublicAccessBlockRequest());
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeletePublicAccessBlock', $e);
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