<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientPaginatorTest extends TestIntegration
{
    public function testListObjectsPaginator()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectsPaginator($client);
        $iter = $paginator->iterPage(
            new Oss\Models\ListObjectsRequest($bucketName),
            ['limit' => 1]
        );

        foreach ($iter as $page) {
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }
        $result = $client->deleteObject(new Oss\Models\DeleteObjectRequest(
            $bucketName, $key
        ));
        $this->assertEquals(204, $result->statusCode);
    }

    public function testListObjectsPaginatorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectsPaginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListObjectsRequest($bucketName),
                ['limit' => 1]
            );
            foreach ($iter as $page) {
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListObjectsPaginatorAsync()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectsPaginator($client);
        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListObjectsRequest($bucketName),
            ['limit' => 10]
        );
        $this->assertTrue(is_object($iterPromise));
        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }
        $result = $client->deleteObject(new Oss\Models\DeleteObjectRequest(
            $bucketName, $key
        ));
        $this->assertEquals(204, $result->statusCode);
    }

    public function testListObjectsPaginatorAsyncFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectsPaginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListObjectsRequest($bucketName),
                ['limit' => 1]
            );
            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListObjectsV2Paginator()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectsV2Paginator($client);

        $iter = $paginator->iterPage(
            new Oss\Models\ListObjectsV2Request($bucketName),
            ['limit' => 1]
        );

        $this->assertTrue(is_object($iter));
        foreach ($iter as $page) {
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }
        $result = $client->deleteObject(new Oss\Models\DeleteObjectRequest(
            $bucketName, $key
        ));
        $this->assertEquals(204, $result->statusCode);
    }

    public function testListObjectsV2PaginatorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectsV2Paginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListObjectsV2Request($bucketName),
                ['limit' => 1]
            );
            foreach ($iter as $page) {
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListObjectsV2PaginatorAsync()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectsV2Paginator($client);

        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListObjectsV2Request($bucketName),
            ['limit' => 1]
        );
        $this->assertTrue(is_object($iterPromise));
        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }
        $result = $client->deleteObject(new Oss\Models\DeleteObjectRequest(
            $bucketName, $key
        ));
        $this->assertEquals(204, $result->statusCode);
    }

    public function testListObjectsV2PaginatorAsyncFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectsV2Paginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListObjectsV2Request($bucketName),
                ['limit' => 1]
            );
            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjects', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListObjectVersionsPaginator()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectVersionsPaginator($client);
        $iter = $paginator->iterPage(
            new Oss\Models\ListObjectVersionsRequest($bucketName),
            ['limit' => 1]
        );

        foreach ($iter as $page) {
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }

    }

    public function testListObjectVersionsPaginatorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectVersionsPaginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListObjectVersionsRequest($bucketName),
                ['limit' => 1]
            );
            foreach ($iter as $page) {
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjectVersions', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListObjectVersionsPaginatorAsync()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $body = 'hi oss';
        $putObjRequest->body = Oss\Utils::streamFor($body);
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);

        $paginator = new Oss\Paginator\ListObjectVersionsPaginator($client);
        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListObjectVersionsRequest($bucketName),
            ['limit' => 1]
        );
        $this->assertTrue(is_object($iterPromise));
        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->contents ?? [] as $content) {
                $this->assertEquals($key, $content->key);
            }
        }
    }

    public function testListObjectVersionsPaginatorAsyncFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListObjectVersionsPaginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListObjectVersionsRequest($bucketName),
                ['limit' => 1]
            );
            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->contents ?? [] as $content) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListObjectVersions', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }

    }

    public function testListMultipartUploadsPaginator()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = 'bigfile.tmp';
        $initResult = $client->initiateMultipartUpload(
            new Oss\Models\InitiateMultipartUploadRequest(
                self::$bucketName,
                $key,
            )
        );
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertNotEmpty($initResult->uploadId);

        $paginator = new Oss\Paginator\ListMultipartUploadsPaginator($client);
        $iter = $paginator->iterPage(
            new Oss\Models\ListMultipartUploadsRequest($bucketName),
            ['limit' => 1]
        );

        foreach ($iter as $page) {
            foreach ($page->uploads ?? [] as $upload) {
                $this->assertEquals($key, $upload->key);
                $this->assertEquals($initResult->uploadId, $upload->uploadId);
            }
        }
    }

    public function testListMultipartUploadsPaginatorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListMultipartUploadsPaginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListMultipartUploadsRequest($bucketName),
                ['limit' => 1]
            );

            foreach ($iter as $page) {
                foreach ($page->uploads ?? [] as $upload) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListMultipartUploads', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }
    }

    public function testListMultipartUploadsPaginatorAsync()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = 'bigfile.tmp';
        $initResult = $client->initiateMultipartUpload(
            new Oss\Models\InitiateMultipartUploadRequest(
                self::$bucketName,
                $key,
            )
        );
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertNotEmpty($initResult->uploadId);

        $paginator = new Oss\Paginator\ListObjectsV2Paginator($client);
        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListObjectsV2Request($bucketName),
            ['limit' => 1]
        );
        $this->assertTrue(is_object($iterPromise));
        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->uploads ?? [] as $upload) {
                $this->assertEquals($key, $upload->key);
                $this->assertEquals($initResult->uploadId, $upload->uploadId);
            }
        }
    }

    public function testListMultipartUploadsPaginatorAsyncFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListMultipartUploadsPaginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListMultipartUploadsRequest($bucketName),
                ['limit' => 1]
            );

            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->uploads ?? [] as $upload) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListMultipartUploads', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }
    }

    public function testListPartsPaginator()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $partSize = 200 * 1024;
        $bigFileName = self::getTempFileName() . "-bigfile.tmp";
        if (!file_exists($bigFileName)) {
            $this->generateFile($bigFileName, 500 * 1024);
        }
        $key = 'bigfile.tmp';
        $initResult = $client->initiateMultipartUpload(
            new Oss\Models\InitiateMultipartUploadRequest(
                self::$bucketName,
                $key,
            )
        );
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertNotEmpty($initResult->uploadId);

        $file = fopen($bigFileName, 'r');
        $parts = array();
        if ($file) {
            $i = 1;
            while (!feof($file)) {
                $chunk = fread($file, $partSize);
                $partResult = $client->uploadPart(
                    new Oss\Models\UploadPartRequest(
                        self::$bucketName,
                        $key,
                        $i,
                        $initResult->uploadId,
                        null,
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    )
                );
                $this->assertEquals(200, $partResult->statusCode);
                $part = new Oss\Models\UploadPart(
                    $i,
                    $partResult->etag,
                );
                array_push($parts, $part);
                $i++;
            }
            fclose($file);
        }

        $paginator = new Oss\Paginator\ListPartsPaginator($client);
        $iter = $paginator->iterPage(
            new Oss\Models\ListPartsRequest($bucketName, $key, $initResult->uploadId),
            ['limit' => 1]
        );

        foreach ($iter as $page) {
            foreach ($page->parts ?? [] as $part) {
                $this->assertNotEmpty($part->partNumber);
                $this->assertNotEmpty($part->etag);
                $this->assertNotEmpty($part->size);
            }
        }
    }

    public function testListPartsPaginatorFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListPartsPaginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListPartsRequest($bucketName, "not-exist", "not-exist"),
                ['limit' => 1]
            );

            foreach ($iter as $page) {
                foreach ($page->parts ?? [] as $part) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListParts', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }
    }

    public function testListPartsPaginatorAsync()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $partSize = 200 * 1024;
        $bigFileName = self::getTempFileName() . "-bigfile.tmp";
        if (!file_exists($bigFileName)) {
            $this->generateFile($bigFileName, 500 * 1024);
        }
        $key = 'bigfile.tmp';
        $initResult = $client->initiateMultipartUpload(
            new Oss\Models\InitiateMultipartUploadRequest(
                self::$bucketName,
                $key,
            )
        );
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertNotEmpty($initResult->uploadId);

        $file = fopen($bigFileName, 'r');
        $parts = array();
        if ($file) {
            $i = 1;
            while (!feof($file)) {
                $chunk = fread($file, $partSize);
                $partResult = $client->uploadPart(
                    new Oss\Models\UploadPartRequest(
                        self::$bucketName,
                        $key,
                        $i,
                        $initResult->uploadId,
                        null,
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    )
                );
                $this->assertEquals(200, $partResult->statusCode);
                $part = new Oss\Models\UploadPart(
                    $i,
                    $partResult->etag,
                );
                array_push($parts, $part);
                $i++;
            }
            fclose($file);
        }

        $paginator = new Oss\Paginator\ListPartsPaginator($client);
        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListPartsRequest($bucketName, $key, $initResult->uploadId),
            ['limit' => 1]
        );

        $resultParts = [];
        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->parts ?? [] as $part) {
                $resultParts[] = $part;
            }
        }
        $this->assertEquals(count($parts), count($resultParts));
    }

    public function testListPartsPaginatorAsyncFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . '-not-exist';
        try {
            $paginator = new Oss\Paginator\ListPartsPaginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListPartsRequest($bucketName, "not-exist", "not-exist"),
                ['limit' => 1]
            );

            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->parts ?? [] as $part) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListParts', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals('The specified bucket does not exist.', $se->getErrorMessage());
                $this->assertEquals('0015-00000101', $se->getEC());
            }
        }
    }

    public function testListBucketsPaginator()
    {
        $client = $this->getDefaultClient();

        $paginator = new Oss\Paginator\ListBucketsPaginator($client);
        $iter = $paginator->iterPage(
            new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX),
            ['limit' => 10]
        );

        foreach ($iter as $page) {
            foreach ($page->buckets ?? [] as $bucket) {
                $this->assertStringContainsString(self::$BUCKETNAME_PREFIX, $bucket->name);
            }
        }
    }

    public function testListBucketsPaginatorFail()
    {
        $client = $this->getInvalidAkClient();
        try {
            $paginator = new Oss\Paginator\ListBucketsPaginator($client);
            $iter = $paginator->iterPage(
                new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX),
                ['limit' => 10]
            );
            foreach ($iter as $page) {
                foreach ($page->buckets ?? [] as $bucket) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListBuckets', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals('The OSS Access Key Id you provided does not exist in our records.', $se->getErrorMessage());
                $this->assertEquals('0002-00000902', $se->getEC());
            }
        }

    }

    public function testListBucketsPaginatorAsync()
    {
        $client = $this->getDefaultClient();

        $paginator = new Oss\Paginator\ListBucketsPaginator($client);
        $iterPromise = $paginator->iterPageAsync(
            new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX),
            ['limit' => 10]
        );

        foreach ($iterPromise as $pagePromise) {
            $page = $pagePromise->wait();
            foreach ($page->buckets ?? [] as $bucket) {
                $this->assertStringContainsString(self::$BUCKETNAME_PREFIX, $bucket->name);
            }
        }
    }

    public function testListBucketsPaginatorAsyncFail()
    {
        $client = $this->getInvalidAkClient();
        try {
            $paginator = new Oss\Paginator\ListBucketsPaginator($client);
            $iterPromise = $paginator->iterPageAsync(
                new Oss\Models\ListBucketsRequest(self::$BUCKETNAME_PREFIX),
                ['limit' => 10]
            );

            foreach ($iterPromise as $pagePromise) {
                $page = $pagePromise->wait();
                foreach ($page->buckets ?? [] as $bucket) {
                    $this->assertTrue(false, 'should not here');
                }
            }
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListBuckets', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals('The OSS Access Key Id you provided does not exist in our records.', $se->getErrorMessage());
                $this->assertEquals('0002-00000902', $se->getEC());
            }
        }
    }
}
