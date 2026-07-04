<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketCnameTest extends TestIntegration
{
    public function testBucketCname()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // CreateCnameToken
        $createResult = $client->createCnameToken(new Oss\Models\CreateCnameTokenRequest(
            $bucketName,
            new Oss\Models\BucketCnameConfiguration(
                new Oss\Models\Cname(
                    'example.com'
                )
            )
        ));
        $this->assertEquals(200, $createResult->statusCode);
        $this->assertEquals('OK', $createResult->status);
        $this->assertEquals(True, count($createResult->headers) > 0);
        $this->assertEquals(24, strlen($createResult->requestId));

        // GetCnameToken
        $getResult = $client->getCnameToken(new Oss\Models\GetCnameTokenRequest(
            $bucketName,
            'example.com'
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // ListCname
        $listResult = $client->listCname(new Oss\Models\ListCnameRequest(
            $bucketName,
        ));
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // DeleteCname
        $delResult = $client->deleteCname(new Oss\Models\DeleteCnameRequest(
            $bucketName,
            new Oss\Models\BucketCnameConfiguration(
                new Oss\Models\Cname(
                    'example.com'
                )
            )
        ));
        $this->assertEquals(200, $delResult->statusCode);
        $this->assertEquals('OK', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));

    }

    public function testBucketCnameFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        try {
            $putResult = $client->putCname(new Oss\Models\PutCnameRequest(
                $bucketName,
                new Oss\Models\BucketCnameConfiguration(
                    new Oss\Models\Cname(
                        'example.com'
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutCname', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $createResult = $client->createCnameToken(new Oss\Models\CreateCnameTokenRequest(
                $bucketName,
                new Oss\Models\BucketCnameConfiguration(
                    new Oss\Models\Cname(
                        'example.com'
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CreateCnameToken', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getTokenResult = $client->getCnameToken(new Oss\Models\GetCnameTokenRequest(
                $bucketName,
                'example.com'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetCnameToken', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $listResult = $client->listCname(new Oss\Models\ListCnameRequest(
                $bucketName,
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListCname', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteCname(new Oss\Models\DeleteCnameRequest(
                $bucketName,
                new Oss\Models\BucketCnameConfiguration(
                    new Oss\Models\Cname(
                        'example.com'
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteCname', $e);
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