<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientAccessPointTest extends TestIntegration
{
    public function testAccessPoint()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        // CreateAccessPoint
        $accessPointName = 'ap-01-' . strval(rand(0, 100)) . '-' . strval(time());
        $createResult = $client->createAccessPoint(new Oss\Models\CreateAccessPointRequest(
            $bucketName,
            new Oss\Models\CreateAccessPointConfiguration(
                accessPointName: $accessPointName,
                networkOrigin: 'internet',
            )));
        $this->assertEquals(200, $createResult->statusCode);
        $this->assertEquals('OK', $createResult->status);
        $this->assertEquals(True, count($createResult->headers) > 0);
        $this->assertEquals(24, strlen($createResult->requestId));

        // GetAccessPoint
        $getResult = $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
            $bucketName, $accessPointName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // ListAccessPoints
        $listResult = $client->listAccessPoints(new Oss\Models\ListAccessPointsRequest());
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // PutAccessPointPolicy
        $policy = '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["' . self::$USER_ID . '"],"Resource":["acs:oss:' . self::$REGION . ':' . self::$USER_ID . ':accesspoint/' . $accessPointName . '","acs:oss:' . self::$REGION . ':' . self::$USER_ID . ':accesspoint/' . $accessPointName . '/object/*"]}]}';
        $putPolicyResult = $client->putAccessPointPolicy(new Oss\Models\PutAccessPointPolicyRequest(
            bucket: $bucketName,
            accessPointName: $accessPointName,
            body: Oss\Utils::streamFor($policy)
        ));
        $this->assertEquals(200, $putPolicyResult->statusCode);
        $this->assertEquals('OK', $putPolicyResult->status);
        $this->assertEquals(True, count($putPolicyResult->headers) > 0);
        $this->assertEquals(24, strlen($putPolicyResult->requestId));

        // GetAccessPointPolicy
        $getPolicyResult = $client->getAccessPointPolicy(new Oss\Models\GetAccessPointPolicyRequest(
            bucket: $bucketName,
            accessPointName: $accessPointName,
        ));
        $this->assertEquals(200, $getPolicyResult->statusCode);
        $this->assertEquals('OK', $getPolicyResult->status);
        $this->assertEquals(True, count($getPolicyResult->headers) > 0);
        $this->assertEquals(24, strlen($getPolicyResult->requestId));
        $this->assertEquals($policy, $getPolicyResult->body);

        // DeleteAccessPointPolicy
        $delPolicyResult = $client->deleteAccessPointPolicy(new Oss\Models\DeleteAccessPointPolicyRequest(
            bucket: $bucketName,
            accessPointName: $accessPointName,
        ));
        $this->assertEquals(204, $delPolicyResult->statusCode);
        $this->assertEquals('No Content', $delPolicyResult->status);
        $this->assertEquals(True, count($delPolicyResult->headers) > 0);
        $this->assertEquals(24, strlen($delPolicyResult->requestId));
        while (true) {
            $getResult = $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
                $bucketName, $accessPointName
            ));
            if ($getResult->getAccessPoint->status != 'creating') {
                break;
            } else {
                sleep(3);
            }
        }
        $delResult = $client->deleteAccessPoint(new Oss\Models\DeleteAccessPointRequest(
            bucket: $bucketName,
            accessPointName: $accessPointName,
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testAccessPointFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // CreateAccessPoint
        try {
            $accessPointName = 'ap-01-' . strval(rand(0, 100)) . '-' . strval(time());
            $createResult = $client->createAccessPoint(new Oss\Models\CreateAccessPointRequest(
                $bucketName,
                new Oss\Models\CreateAccessPointConfiguration(
                    accessPointName: $accessPointName,
                    networkOrigin: 'internet',
                )));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CreateAccessPoint', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetAccessPoint
        try {
            $getResult = $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
                $bucketName, $accessPointName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetAccessPoint', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // ListAccessPoints
        try {
            $listResult = $client->listAccessPoints(new Oss\Models\ListAccessPointsRequest());
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListAccessPoints', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // PutAccessPointPolicy
        try {
            $policy = '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["' . self::$USER_ID . '"],"Resource":["acs:oss:' . self::$REGION . ':' . self::$USER_ID . ':accesspoint/' . $accessPointName . '","acs:oss:' . self::$REGION . ':' . self::$USER_ID . ':accesspoint/' . $accessPointName . '/object/*"]}]}';
            $putPolicyResult = $client->putAccessPointPolicy(new Oss\Models\PutAccessPointPolicyRequest(
                bucket: $bucketName,
                accessPointName: $accessPointName,
                body: Oss\Utils::streamFor($policy)
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutAccessPointPolicy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetAccessPointPolicy
        try {
            $getPolicyResult = $client->getAccessPointPolicy(new Oss\Models\GetAccessPointPolicyRequest(
                bucket: $bucketName,
                accessPointName: $accessPointName,
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetAccessPointPolicy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteAccessPointPolicy
        try {
            $delPolicyResult = $client->deleteAccessPointPolicy(new Oss\Models\DeleteAccessPointPolicyRequest(
                bucket: $bucketName,
                accessPointName: $accessPointName,
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteAccessPointPolicy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetAccessPoint
        try {
            $getResult = $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
                $bucketName, $accessPointName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetAccessPoint', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteAccessPoint
        try {
            $delResult = $client->deleteAccessPoint(new Oss\Models\DeleteAccessPointRequest(
                bucket: $bucketName,
                accessPointName: $accessPointName,
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteAccessPoint', $e);
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