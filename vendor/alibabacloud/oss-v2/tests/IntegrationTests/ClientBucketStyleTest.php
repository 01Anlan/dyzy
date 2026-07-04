<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketStyleTest extends TestIntegration
{
    public function testBucketStyle()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutStyle
        $putResult = $client->putStyle(new Oss\Models\PutStyleRequest(
            $bucketName,
            styleName: 'test',
            style: new Oss\Models\StyleContent('image/resize,p_50')
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetStyle
        $getResult = $client->getStyle(new Oss\Models\GetStyleRequest(
            $bucketName, 'test'
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // ListStyle
        $listResult = $client->listStyle(new Oss\Models\ListStyleRequest(
            $bucketName
        ));
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // DeleteStyle
        $delResult = $client->deleteStyle(new Oss\Models\DeleteStyleRequest(
            $bucketName, 'test'
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketStyleFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutStyle
        try {
            $putResult = $client->putStyle(new Oss\Models\PutStyleRequest(
                $bucketName,
                styleName: 'test',
                style: new Oss\Models\StyleContent('image/resize,p_50')
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutStyle', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetStyle
        try {
            $getResult = $client->getStyle(new Oss\Models\GetStyleRequest(
                $bucketName, 'test'
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetStyle', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // ListStyle
        try {
            $listResult = $client->listStyle(new Oss\Models\ListStyleRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListStyle', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteStyle
        try {
            $delResult = $client->deleteStyle(new Oss\Models\DeleteStyleRequest(
                $bucketName, 'test'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteStyle', $e);
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