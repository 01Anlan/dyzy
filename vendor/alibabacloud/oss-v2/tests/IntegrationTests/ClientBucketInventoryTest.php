<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketInventoryTest extends TestIntegration
{
    public function testBucketInventory()
    {
        if (self::$USER_ID === false) {
            throw new \Exception('user id can not be empty!');
        }
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $id = 'report1' . rand(0, 100) . '-' . time();
        // PutBucketInventory
        $putResult = $client->putBucketInventory(new Oss\Models\PutBucketInventoryRequest(
            $bucketName,
            inventoryId: $id,
            inventoryConfiguration: new Oss\Models\InventoryConfiguration(
            isEnabled: true,
            destination: new Oss\Models\InventoryDestination(
            ossBucketDestination: new Oss\Models\InventoryOSSBucketDestination(
                bucket: 'acs:oss:::' . $bucketName,
                prefix: 'prefix1',
                format: Oss\Models\InventoryFormatType::CSV,
                accountId: self::$USER_ID,
                roleArn: 'acs:ram::' . self::$USER_ID . ':role/AliyunOSSRole'
            )
        ),
            schedule: new Oss\Models\InventorySchedule(
            frequency: Oss\Models\InventoryFrequencyType::DAILY
        ),
            filter: new Oss\Models\InventoryFilter(
            prefix: 'filterPrefix',
            lastModifyBeginTimeStamp: 1637883649,
            lastModifyEndTimeStamp: 1638347592,
            lowerSizeBound: 1024,
            upperSizeBound: 1048576,
            storageClass: 'Standard,IA'
        ),
            includedObjectVersions: 'All',
            optionalFields: new Oss\Models\OptionalFields(
            fields: array(
                Oss\Models\InventoryOptionalFieldType::STORAGE_CLASS
            )
        ),
            id: $id
        )));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketInventory
        $getResult = $client->getBucketInventory(new Oss\Models\GetBucketInventoryRequest(
            $bucketName, $id
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // ListBucketInventory
        $listResult = $client->listBucketInventory(new Oss\Models\ListBucketInventoryRequest(
            $bucketName
        ));
        $this->assertEquals(200, $listResult->statusCode);
        $this->assertEquals('OK', $listResult->status);
        $this->assertEquals(True, count($listResult->headers) > 0);
        $this->assertEquals(24, strlen($listResult->requestId));

        // DeleteBucketInventory
        $delResult = $client->deleteBucketInventory(new Oss\Models\DeleteBucketInventoryRequest(
            $bucketName, $id
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketInventoryFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";
        $id = 'report1' . rand(0, 100) . '-' . time();
        // PutBucketInventory
        try {
            $putResult = $client->putBucketInventory(new Oss\Models\PutBucketInventoryRequest(
                $bucketName,
                inventoryId: $id,
                inventoryConfiguration: new Oss\Models\InventoryConfiguration(
                isEnabled: true,
                destination: new Oss\Models\InventoryDestination(
                ossBucketDestination: new Oss\Models\InventoryOSSBucketDestination(
                    bucket: 'acs:oss:::' . $bucketName,
                    prefix: 'prefix1',
                    format: Oss\Models\InventoryFormatType::CSV,
                    accountId: self::$USER_ID,
                    roleArn: 'acs:ram::' . self::$USER_ID . ':role/AliyunOSSRole'
                )
            ),
                schedule: new Oss\Models\InventorySchedule(
                frequency: Oss\Models\InventoryFrequencyType::DAILY
            ),
                filter: new Oss\Models\InventoryFilter(
                prefix: 'filterPrefix',
                lastModifyBeginTimeStamp: 1637883649,
                lastModifyEndTimeStamp: 1638347592,
                lowerSizeBound: 1024,
                upperSizeBound: 1048576,
                storageClass: 'Standard,IA'
            ),
                includedObjectVersions: 'All',
                optionalFields: new Oss\Models\OptionalFields(
                fields: array(
                    Oss\Models\InventoryOptionalFieldType::STORAGE_CLASS
                )
            ),
                id: $id
            )));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketInventory', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketInventory
        try {
            $getResult = $client->getBucketInventory(new Oss\Models\GetBucketInventoryRequest(
                $bucketName, $id
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketInventory', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // ListBucketInventory
        try {
            $getResult = $client->listBucketInventory(new Oss\Models\ListBucketInventoryRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListBucketInventory', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketInventory
        try {
            $delResult = $client->deleteBucketInventory(new Oss\Models\DeleteBucketInventoryRequest(
                $bucketName, $id
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketInventory', $e);
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