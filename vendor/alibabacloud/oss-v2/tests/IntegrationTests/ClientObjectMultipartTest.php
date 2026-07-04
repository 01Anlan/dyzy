<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientObjectMultipartTest extends TestIntegration
{
    public function testObjectMultipart()
    {
        $client = $this->getDefaultClient();
        // test initiateMultipartUpload, uploadPart, completeMultipartUpload
        $partSize = 200 * 1024;
        $key = 'bigfile.tmp';
        try {
            $bigFileName = self::getTempFileName() . "-bigfile.tmp";
            if (!file_exists($bigFileName)) {
                $this->generateFile($bigFileName, 500 * 1024);
            }
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
            $comResult = $client->completeMultipartUpload(
                new Oss\Models\CompleteMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                    $initResult->uploadId,
                    null,
                    new Oss\Models\CompleteMultipartUpload(
                        $parts
                    ),
                )
            );
            $this->assertEquals(200, $comResult->statusCode);
            $this->assertEquals(self::$bucketName, $comResult->bucket);
            $this->assertEquals($key, $comResult->key);
            $this->assertNotEmpty($comResult->etag);
            $this->assertNotEmpty($comResult->location);
            $getObj = $client->getObject(new Oss\Models\GetObjectRequest(
                self::$bucketName,
                $key,
            ));
            $this->assertEquals(base64_encode(md5(file_get_contents($bigFileName), true)), base64_encode(md5($getObj->body, true)));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        // test initiateMultipartUpload, uploadPartCopy, completeMultipartUpload
        try {
            $key1 = self::randomObjectName() . "\f\v\t";
            $initResult = $client->initiateMultipartUpload(
                new Oss\Models\InitiateMultipartUploadRequest(
                    self::$bucketName,
                    $key1,
                )
            );
            $this->assertEquals(200, $initResult->statusCode);
            $this->assertNotEmpty($initResult->uploadId);

            $partResult = $client->uploadPartCopy(
                new Oss\Models\UploadPartCopyRequest(
                    self::$bucketName,
                    $key1,
                    1,
                    $initResult->uploadId,
                    self::$bucketName,
                    $key,
                )
            );
            $this->assertEquals(200, $partResult->statusCode);
            $comResult = $client->completeMultipartUpload(
                new Oss\Models\CompleteMultipartUploadRequest(
                    self::$bucketName,
                    $key1,
                    $initResult->uploadId,
                    null,
                    null,
                    'yes',
                )
            );
            $this->assertEquals(200, $comResult->statusCode);
            $this->assertEquals(self::$bucketName, $comResult->bucket);
            $this->assertEquals($key1, $comResult->key);
            $this->assertNotEmpty($comResult->etag);
            $this->assertNotEmpty($comResult->location);
            $client->deleteObject(new Oss\Models\DeleteObjectRequest(
                self::$bucketName,
                $key1,
            ));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        $client->deleteObject(new Oss\Models\DeleteObjectRequest(
            self::$bucketName,
            $key,
        ));

        // test listParts and listMultipartUploads
        $objectPrefix = self::randomObjectName();
        try {
            for ($i = 0; $i < 3; $i++) {
                $key = $objectPrefix . $i;
                $initResult = $client->initiateMultipartUpload(
                    new Oss\Models\InitiateMultipartUploadRequest(
                        self::$bucketName,
                        $key,
                    )
                );
                $this->assertEquals(200, $initResult->statusCode);
                $this->assertNotEmpty($initResult->uploadId);
                $file = fopen($bigFileName, 'r');
                if ($file) {
                    $n = 1;
                    while (!feof($file)) {
                        $chunk = fread($file, $partSize);
                        $partResult = $client->uploadPart(
                            new Oss\Models\UploadPartRequest(
                                self::$bucketName,
                                $key,
                                $n,
                                $initResult->uploadId,
                                null,
                                null,
                                null,
                                null,
                                Oss\Utils::streamFor($chunk)
                            )
                        );
                        $this->assertEquals(200, $partResult->statusCode);
                        $n++;
                    }
                    fclose($file);
                }
            }
            $listUploads = $client->listMultipartUploads(
                new Oss\Models\ListMultipartUploadsRequest(
                    self::$bucketName,
                )
            );
            $this->assertEquals(200, $listUploads->statusCode);
            $this->assertCount(3, $listUploads->uploads);

            $listParts = $client->listParts(
                new Oss\Models\ListPartsRequest(
                    self::$bucketName,
                    $listUploads->uploads[0]->key,
                    $listUploads->uploads[0]->uploadId,
                )
            );
            $this->assertEquals(200, $listParts->statusCode);
            $this->assertCount(3, $listParts->parts);

        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
        // test abortMultipartUpload
        try {
            foreach ($listUploads->uploads as $k => $upload) {
                $abort = $client->abortMultipartUpload(
                    new Oss\Models\AbortMultipartUploadRequest(
                        self::$bucketName,
                        $upload->key,
                        $upload->uploadId,
                    )
                );
                $this->assertEquals(204, $abort->statusCode);
            }
            $listUploads = $client->listMultipartUploads(
                new Oss\Models\ListMultipartUploadsRequest(
                    self::$bucketName,
                )
            );
            $this->assertEquals(200, $listUploads->statusCode);
            $this->assertNull($listUploads->uploads);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

    }

    public function testObjectMultipartFail()
    {
        $client = $this->getDefaultClient();
        $invalidAkClient = $this->getInvalidAkClient();
        $partSize = 200 * 1024;
        $key = 'bigfile.tmp';
        $bigFileName = self:: getTempFileName() . "-bigfile.tmp";
        if (!file_exists($bigFileName)) {
            $this->generateFile($bigFileName, 500 * 1024);
        }

        // test initiateMultipartUpload
        try {
            $invalidAkClient->initiateMultipartUpload(
                new Oss\Models\InitiateMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                )
            );
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error InitiateMultipartUpload', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // test uploadPart
        try {
            $initResult = $client->initiateMultipartUpload(
                new Oss\Models\InitiateMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                )
            );
            $this->assertEquals(200, $initResult->statusCode);
            $this->assertNotEmpty($initResult->uploadId);
            $invalidAkClient->uploadPart(
                new Oss\Models\UploadPartRequest(
                    self::$bucketName,
                    $key,
                    1,
                    $initResult->uploadId,
                    null,
                    null,
                    null,
                    null,
                    Oss\Utils::streamFor($bigFileName)
                )
            );
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Operation error UploadPart', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // test uploadPartCopy
        try {
            $initResult = $client->initiateMultipartUpload(
                new Oss\Models\InitiateMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                )
            );
            $this->assertEquals(200, $initResult->statusCode);
            $this->assertNotEmpty($initResult->uploadId);
            $client->uploadPart(
                new Oss\Models\UploadPartRequest(
                    self::$bucketName,
                    $key,
                    1,
                    $initResult->uploadId,
                    null,
                    null,
                    null,
                    null,
                    Oss\Utils::streamFor($bigFileName)
                )
            );
            $invalidAkClient->uploadPartCopy(
                new Oss\Models\UploadPartCopyRequest(
                    self::$bucketName,
                    $key,
                    1,
                    $initResult->uploadId,
                    self::$bucketName,
                    $key,
                )
            );
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Operation error UploadPartCopy', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
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
            $invalidAkClient->completeMultipartUpload(
                new Oss\Models\CompleteMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                    $initResult->uploadId,
                    null,
                    new Oss\Models\CompleteMultipartUpload(
                        $parts
                    ),
                )
            );
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Operation error CompleteMultipartUpload', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $invalidAkClient->listMultipartUploads(
                new Oss\Models\ListMultipartUploadsRequest(
                    self::$bucketName,
                )
            );
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Operation error ListMultipartUploads', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        try {
            $invalidAkClient->listParts(
                new Oss\Models\ListPartsRequest(
                    self::$bucketName,
                    $initResult->uploadId,
                    $initResult->key,
                )
            );
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Operation error ListParts', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        $listUploads = $client->listMultipartUploads(
            new Oss\Models\ListMultipartUploadsRequest(
                self::$bucketName,
            )
        );

        foreach ($listUploads->uploads as $k => $upload) {
            $abort = $client->abortMultipartUpload(
                new Oss\Models\AbortMultipartUploadRequest(
                    self::$bucketName,
                    $upload->key,
                    $upload->uploadId,
                )
            );
            $this->assertEquals(204, $abort->statusCode);
        }
    }
}
