<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketBasicTest extends TestIntegration
{
    public function testBucketBasic()
    {
        $client = $this->getDefaultClient();

        //default
        $bucketName = self::randomBucketName() . "-default";
        $result = $client->putBucket(new Oss\Models\PutBucketRequest(
            $bucketName
        ));

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get stat
        $result = $client->getBucketStat(new Oss\Models\GetBucketStatRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(0, $result->storage);

        // get location
        $result = $client->getBucketLocation(new Oss\Models\GetBucketLocationRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $region = self::$REGION;
        $this->assertEquals("oss-$region", $result->location);

        // get info
        $result = $client->getBucketInfo(new Oss\Models\GetBucketInfoRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals('private', $result->bucketInfo->acl);

        //delete
        $result = $client->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $bucketName
        ));
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // create bucket with acl & storage class & data redundancy
        $bucketName = self::randomBucketName() . '-acl-storage';
        $result = $client->putBucket(new Oss\Models\PutBucketRequest(
            $bucketName,
            'public-read',
            null,
            new Oss\Models\CreateBucketConfiguration(
                'IA',
                'ZRS'
            )
        ));

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get info
        $result = $client->getBucketInfo(new Oss\Models\GetBucketInfoRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals('public-read', $result->bucketInfo->acl);
        $this->assertEquals('IA', $result->bucketInfo->storageClass);
        $this->assertEquals('ZRS', $result->bucketInfo->dataRedundancyType);

        $result = $client->listObjects(new Oss\Models\ListObjectsRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        $result = $client->listObjectsV2(new Oss\Models\ListObjectsV2Request(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        //delete
        $result = $client->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $bucketName
        ));
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
    }

    public function testBucketAcl()
    {
        $client = $this->getDefaultClient();

        //default
        $bucketName = self::randomBucketName();
        $result = $client->putBucket(new Oss\Models\PutBucketRequest(
            $bucketName
        ));

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // set acl
        $result = $client->putBucketAcl(new Oss\Models\PutBucketAclRequest(
            $bucketName,
            'private'
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get acl
        $result = $client->getBucketAcl(new Oss\Models\GetBucketAclRequest(
            $bucketName
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals('private', $result->accessControlList->grant);

        $client->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $bucketName
        ));
    }

    public function testBucketVersioning()
    {
        $client = $this->getDefaultClient();

        //default
        $bucketName = self::randomBucketName();
        $result = $client->putBucket(new Oss\Models\PutBucketRequest(
            $bucketName
        ));

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        $result = $client->putBucketVersioning(new Oss\Models\PutBucketVersioningRequest(
            $bucketName,
            new Oss\Models\VersioningConfiguration(
                Oss\Models\BucketVersioningStatusType::ENABLED
            )
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        $result = $client->getBucketVersioning(new Oss\Models\GetBucketVersioningRequest(
            $bucketName,
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(Oss\Models\BucketVersioningStatusType::ENABLED, $result->versioningConfiguration->status);

        $result = $client->listObjectVersions(new Oss\Models\ListObjectVersionsRequest(
            $bucketName,
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->versions);

        $this->waitFor(3);
        $client->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $bucketName
        ));
    }

    public function testListBuckets()
    {
        $client = $this->getDefaultClient();

        //default
        $bucketName = self::randomBucketName();
        $result = $client->putBucket(new Oss\Models\PutBucketRequest(
            $bucketName
        ));

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        $result = $client->listBuckets(new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(True, count($result->buckets) > 0);

        $request = new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX);
        $request->tagging = 'k:v';
        $result = $client->listBuckets($request);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->buckets);

        $request = new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX);
        $request->tagKey = 'k';
        $request->tagValue = 'v';
        $result = $client->listBuckets($request);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->buckets);

        $this->waitFor(3);
        $client->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $bucketName
        ));
    }

    public function testDescribeRegions()
    {
        $client = $this->getDefaultClient();
        $result = $client->describeRegions(new Oss\Models\DescribeRegionsRequest());
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(true, count($result->regionInfos) > 1);

        $result = $client->describeRegions(new Oss\Models\DescribeRegionsRequest("oss-cn-hangzhou"));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(1, count($result->regionInfos));
    }

    public function testPutBucketFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::randomBucketName();
            $client->putBucket(new Oss\Models\PutBucketRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucket', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testPutBucketAclFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->putBucketAcl(new Oss\Models\PutBucketAclRequest(
                $bucketName,
                'private'
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketAcl', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetBucketAclFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->getBucketAcl(new Oss\Models\GetBucketAclRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketAcl', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetBucketStatFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->getBucketStat(new Oss\Models\GetBucketStatRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketStat', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetBucketLocationFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->getBucketLocation(new Oss\Models\GetBucketLocationRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketLocation', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetBucketInfoFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->getBucketInfo(new Oss\Models\GetBucketInfoRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketInfo', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testPutBucketVersioningFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->putBucketVersioning(new Oss\Models\PutBucketVersioningRequest(
                $bucketName,
                new Oss\Models\VersioningConfiguration('Enabled')
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketVersioning', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetBucketVersioningFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->GetBucketVersioning(new Oss\Models\GetBucketVersioningRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketVersioning', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testListObjectVersionsFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->listObjectVersions(new Oss\Models\ListObjectVersionsRequest(
                $bucketName
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjectVersions', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testListObjectsFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->listObjects(new Oss\Models\ListObjectsRequest(
                $bucketName,
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testListObjectsV2Fail(): void
    {
        $client = $this->getInvalidAkClient();
        try {
            $bucketName = self::$bucketName;
            $client->listObjectsV2(new Oss\Models\ListObjectsV2Request(
                $bucketName,
            ));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjectsV2', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testListBucketsFail(): void
    {
        $client = $this->getInvalidAkClient();

        try {
            $bucketName = self::$bucketName;
            $client->listBuckets(new Oss\Models\ListBucketsRequest());
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListBuckets', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }
}
