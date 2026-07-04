<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketCorsTest extends TestIntegration
{
    public function testBucketCors()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketCors
        $putResult = $client->putBucketCors(new Oss\Models\PutBucketCorsRequest(
            $bucketName,
            corsConfiguration: new Oss\Models\CORSConfiguration(
                array(
                    new Oss\Models\CORSRule(
                        allowedOrigins: ['*'],
                        allowedMethods: ['PUT', 'GET']
                    )
                )
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // OptionObject
        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';
        $putObjResult = $client->putObject(new Oss\Models\PutObjectRequest(
            $bucketName,
            $objectName,
            body: Oss\Utils::streamFor('hi oss')
        ));
        $this->assertEquals(200, $putObjResult->statusCode);
        $this->assertEquals('OK', $putObjResult->status);
        $this->assertEquals(True, count($putObjResult->headers) > 0);
        $this->assertEquals(24, strlen($putObjResult->requestId));

        $optionObjResult = $client->optionObject(new Oss\Models\OptionObjectRequest(
            $bucketName,
            $objectName,
            origin: 'http://www.example.com',
            accessControlRequestMethod: 'PUT',
        ));
        $this->assertEquals(200, $optionObjResult->statusCode);
        $this->assertEquals('OK', $optionObjResult->status);
        $this->assertEquals(True, count($optionObjResult->headers) > 0);
        $this->assertEquals(24, strlen($optionObjResult->requestId));

        // GetBucketCors
        $getResult = $client->getBucketCors(new Oss\Models\GetBucketCorsRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketCors
        $delResult = $client->deleteBucketCors(new Oss\Models\DeleteBucketCorsRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketCorsFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketCors
        try {
            $putResult = $client->putBucketCors(new Oss\Models\PutBucketCorsRequest(
                $bucketName,
                corsConfiguration: new Oss\Models\CORSConfiguration(
                    array(
                        new Oss\Models\CORSRule(
                            allowedOrigins: ['*'],
                            allowedMethods: ['PUT', 'GET']
                        )
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketCors', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';
            $optionObjResult = $client->optionObject(new Oss\Models\OptionObjectRequest(
                $bucketName,
                $objectName,
                origin: 'http://www.example.com',
                accessControlRequestMethod: 'PUT',
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error OptionObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketCors
        try {
            $getResult = $client->getBucketCors(new Oss\Models\GetBucketCorsRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketCors', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketCors
        try {
            $delResult = $client->deleteBucketCors(new Oss\Models\DeleteBucketCorsRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketCors', $e);
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