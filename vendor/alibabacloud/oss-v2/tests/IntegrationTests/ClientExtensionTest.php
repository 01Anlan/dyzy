<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\NoSeekStream;
use GuzzleHttp\Psr7\BufferStream;

class ClientExtensionTest extends TestIntegration
{
    public function testIsBucketExist()
    {
        $client = self::getDefaultClient();
        $noPermClient = self::getInvalidAkClient();
        $errClient = self::getClient(self::$ACCESS_ID, self::$ACCESS_KEY, '', '');
        $bucketName = self::$bucketName;
        $bucketNameNoExist = self::$bucketName . "-no-exist";

        $exist = $client->isBucketExist($bucketName);
        $this->assertTrue($exist);

        $exist = $client->isBucketExist($bucketNameNoExist);
        $this->assertFalse($exist);

        $exist = $noPermClient->isBucketExist($bucketName);
        $this->assertTrue($exist);

        $exist = $noPermClient->isBucketExist($bucketNameNoExist);
        $this->assertFalse($exist);

        try {
            $exist = $errClient->isBucketExist($bucketName);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('InvalidArgumentException: endpoint is invalid', $e);
        }
    }

    public function testIsObjectExist()
    {
        $client = self::getDefaultClient();
        $noPermClient = self::getInvalidAkClient();
        $errClient = self::getClient(self::$ACCESS_ID, self::$ACCESS_KEY, '', '');
        $bucketName = self::$bucketName;
        $bucketNameNoExist = self::$bucketName . "-no-exist";
        $objectName = 'object-exist';
        $objectNameNoExist = 'object-exist-no-exist';

        $result = $client->putObject(new Oss\Models\PutObjectRequest($bucketName, $objectName));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // normal
        $exist = $client->isObjectExist($bucketName, $objectName);
        $this->assertTrue($exist);

        $exist = $client->isObjectExist($bucketName, $objectNameNoExist);
        $this->assertFalse($exist);

        // no exist bucket
        try {
            $exist = $client->isObjectExist($bucketNameNoExist, $objectName);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('NoSuchBucket', $e);
        }

        try {
            $exist = $client->isObjectExist($bucketNameNoExist, $objectNameNoExist);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('NoSuchBucket', $e);
        }

        // no perm client
        try {
            $exist = $noPermClient->isObjectExist($bucketName, $objectName);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('InvalidAccessKeyId', $e);
        }

        try {
            $exist = $noPermClient->isObjectExist($bucketNameNoExist, $objectNameNoExist);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('NoSuchBucket', $e);
        }

        // invalid client
        try {
            $exist = $errClient->isObjectExist($bucketName, $objectName);
            $this->assertTrue(false, 'shoud not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('InvalidArgumentException: endpoint is invalid', $e);
        }
    }

    public function testUploader(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $fileMd5 = md5(file_get_contents($filename), true);
        $uploader = $client->newUploader(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // multi-part
        $result = $uploader->uploadFile(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                $key
            ),
            $filename,
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-3"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, $key), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // single-part
        $result = $uploader->uploadFile(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "single-part-$key"
            ),
            $filename,
            [
                'part_size' => $totalSize * 2,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "single-part-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // upload from multi-part
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "multi-part-from-$key"

            ),
            new LazyOpenStream($filename, 'rb'),
            [
                'part_size' => 200 * 1024,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-2"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "multi-part-from-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // upload from single-part
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "single-part-from-$key"

            ),
            new LazyOpenStream($filename, 'rb'),
            [
                'part_size' => $totalSize * 2,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "single-part-from-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // upload from multi-part with no-seekable stream
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "multi-part-from-$key"

            ),
            new NoSeekStream(new LazyOpenStream($filename, 'rb')),
            [
                'part_size' => 200 * 1024,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-2"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "multi-part-from-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // upload from multi-part, and start from 100 
        $stream = new LazyOpenStream($filename, 'rb');
        $stream->seek(100);
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "multi-part-from-100-$key"

            ),
            $stream,
            [
                'part_size' => 200 * 1024,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-2"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $partFileMd5 = md5(substr(file_get_contents($filename), 100), true);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "multi-part-from-100-$key"), true);
        $this->assertEquals($partFileMd5, $objectMd5);
    }

    public function testUploaderDetectContentType(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName() . '.zip';
        $this->generateFile($filename, $totalSize);
        $fileMd5 = md5(file_get_contents($filename), true);
        $uploader = $client->newUploader(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // multi-part
        $result = $uploader->uploadFile(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                $key
            ),
            $filename,
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-3"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, $key), true);
        $this->assertEquals($fileMd5, $objectMd5);
        $contentType = $this->object_get_content_type($client, $bucketName, $key);
        $this->assertEquals("application/zip", $contentType);
        
        // single-part
        $result = $uploader->uploadFile(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "single-part-$key"
            ),
            $filename,
            [
                'part_size' => $totalSize * 2,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "single-part-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);
        $contentType = $this->object_get_content_type($client, $bucketName, "single-part-$key");
        $this->assertEquals("application/zip", $contentType);

        // upload from multi-part
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "multi-part-from-$key"

            ),
            new LazyOpenStream($filename, 'rb'),
            [
                'part_size' => 200 * 1024,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringEndsWith('-2"', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "multi-part-from-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);
        $contentType = $this->object_get_content_type($client, $bucketName, "multi-part-from-$key");
        $this->assertEquals("application/octet-stream", $contentType);

        // upload from single-part
        $result = $uploader->uploadFrom(
            new Oss\Models\PutObjectRequest(
                $bucketName,
                "single-part-from-$key"

            ),
            new LazyOpenStream($filename, 'rb'),
            [
                'part_size' => $totalSize * 2,
            ]
        );

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $bucketName, "single-part-from-$key"), true);
        $this->assertEquals($fileMd5, $objectMd5);
        $contentType = $this->object_get_content_type($client, $bucketName, "single-part-from-$key");
        $this->assertEquals("application/zip", $contentType);
    }

    public function testUploaderFail(): void
    {
        $noPermClient = self::getInvalidAkClient();
        $bucketName = self::$bucketName;

        $key = self::randomObjectName();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);

        $uploader = $noPermClient->newUploader(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // multi-part
        try {
            $result = $uploader->uploadFile(
                new Oss\Models\PutObjectRequest(
                    $bucketName,
                    $key
                ),
                $filename,
            );
        } catch (Oss\Exception\UploadException $e) {
            $this->assertStringContainsString('upload failed', $e);
            $this->assertStringContainsString($filename, $e);
            $this->assertStringContainsString('Operation error InitiateMultipartUpload', $e);
            $se = $e->getPrevious()->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // single-part
        try {
            $result = $uploader->uploadFile(
                new Oss\Models\PutObjectRequest(
                    $bucketName,
                    $key
                ),
                $filename,
                [
                    'part_size' => $totalSize * 2,
                ]
            );
        } catch (Oss\Exception\UploadException $e) {
            $this->assertStringContainsString('upload failed', $e);
            $this->assertStringContainsString($filename, $e);
            $this->assertStringContainsString('Operation error PutObject', $e);
            $se = $e->getPrevious()->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testDownloader(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 25 * 1024 + 123;
        $partSize = 10 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $content = \file_get_contents($filename);

        $request = new Oss\Models\PutObjectRequest($bucketName, $key);
        $request->body = Oss\Utils::streamFor($content);

        $result = $client->putObject($request);

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $saveToFilename = self::getTempFileName();
        $this->assertFileNotEquals($filename, $saveToFilename);
        $downloader = $client->newDownloader(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // multipart download
        $result = $downloader->downloadFile(
            new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            ),
            $saveToFilename,
        );
        $this->assertEquals($totalSize, $result->written);
        $this->assertFileEquals($filename, $saveToFilename);

        // multipart download + parallel
        $saveToFilename = self::getTempFileName();
        $this->assertFileNotEquals($filename, $saveToFilename);

        $result = $downloader->downloadFile(
            new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            ),
            $saveToFilename,
            [
                'parallel_num' => 2,
            ]
        );
        $this->assertEquals($totalSize, $result->written);
        $this->assertFileEquals($filename, $saveToFilename);

        // single part download + parallel
        $saveToFilename = self::getTempFileName();
        $this->assertFileNotEquals($filename, $saveToFilename);

        $result = $downloader->downloadFile(
            new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            ),
            $saveToFilename,
            [
                'part_size' => $totalSize * 2,
                'parallel_num' => 2,
            ]
        );
        $this->assertEquals($totalSize, $result->written);
        $this->assertFileEquals($filename, $saveToFilename);
    }

    public function testDownloaderDownloadTo(): void
    {
        $client = self::getDefaultClient();
        $buckeName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 25 * 1024 + 123;
        $partSize = 10 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $content = \file_get_contents($filename);

        $request = new Oss\Models\PutObjectRequest($buckeName, $key);
        $request->body = Oss\Utils::streamFor($content);

        $result = $client->putObject($request);

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $downloader = $client->newDownloader(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // multipart download
        $stream = new BufferStream(1 * 1024 * 1024);
        $result = $downloader->downloadTo(
            new Oss\Models\GetObjectRequest(
                $buckeName,
                $key
            ),
            $stream,
        );
        $gotContent = $stream->getContents();
        $this->assertEquals($totalSize, $result->written);
        $this->assertEquals($totalSize, strlen($gotContent));
        $this->assertEquals($content, $gotContent);

        // multipart download + parallel
        $stream = new BufferStream(1 * 1024 * 1024);
        $result = $downloader->downloadTo(
            new Oss\Models\GetObjectRequest(
                $buckeName,
                $key
            ),
            $stream,
            [
                'parallel_num' => 2,
            ]
        );
        $gotContent = $stream->getContents();
        $this->assertEquals($totalSize, $result->written);
        $this->assertEquals($totalSize, strlen($gotContent));
        $this->assertEquals($content, $gotContent);

        // single part download + parallel
        $stream = new BufferStream(1 * 1024 * 1024);
        $result = $downloader->downloadTo(
            new Oss\Models\GetObjectRequest(
                $buckeName,
                $key
            ),
            $stream,
            [
                'part_size' => $totalSize * 2,
                'parallel_num' => 2,
            ]
        );
        $gotContent = $stream->getContents();
        $this->assertEquals($totalSize, $result->written);
        $this->assertEquals($totalSize, strlen($gotContent));
        $this->assertEquals($content, $gotContent);
    }

    public function testCopier(): void
    {
        $client = self::getDefaultClient();
        $buckeName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $content = \file_get_contents($filename);
        $fileMd5 = md5(file_get_contents($filename), true);

        $request = new Oss\Models\PutObjectRequest($buckeName, $key);
        $request->body = Oss\Utils::streamFor($content);

        $result = $client->putObject($request);

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $sourceBucket = $buckeName;
        $sourceKey = $key;

        $copier = $client->newCopier(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // single copy
        $dstBucket = $buckeName;
        $dstKey = "single-copy-$key";
        $result = $copier->copy(
            new Oss\Models\CopyObjectRequest(
                $dstBucket,
                $dstKey,
                $sourceBucket,
                $sourceKey
            ),
            [
                'part_size' => $totalSize * 2,
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $dstBucket, $dstKey), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // Shallow copy mode, use single copy
        $dstBucket = $buckeName;
        $dstKey = "shallow-single-copy-$key";
        $result = $copier->copy(
            new Oss\Models\CopyObjectRequest(
                $dstBucket,
                $dstKey,
                $sourceBucket,
                $sourceKey
            ),
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNull($result->uploadId);
        $this->assertStringNotContainsString('-', $result->etag);
        $this->assertNotNull($result->hashCrc64);
        $objectMd5 = md5($this->object_get_contents($client, $dstBucket, $dstKey), true);
        $this->assertEquals($fileMd5, $objectMd5);

        // shllow copy mode, use multipart copy

        // multipart case
        $request = new Oss\Models\PutObjectRequest($buckeName, $sourceKey);
        $request->contentType = 'text/js';
        $request->cacheControl = 'no-cache';
        $request->metadata = ['key1' => 'value1', 'key2' => 'value2'];
        $request->tagging = 'k1=v1&k2=v2';
        $request->body = Oss\Utils::streamFor($content);
        $result = $client->putObject($request);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        // multipart copy, 'replace' directive
        // case 1, empty metadata & tagging
        $dstBucket = $buckeName;
        $dstKey = "multipart-copy-repalce-empty-metadata-and-tagging-$key.js";
        $copyRequest = new Oss\Models\CopyObjectRequest(
            $dstBucket,
            $dstKey,
            $sourceBucket,
            $sourceKey
        );
        $copyRequest->metadataDirective = 'REPLACE';
        $copyRequest->taggingDirective = 'REPLACE';
        $result = $copier->copy(
            $copyRequest,
            [
                'multipart_copy_threshold' => 0,
                'disable_shallow_copy' => true
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringContainsString('-3', $result->etag);
        $objectMd5 = md5($this->object_get_contents($client, $dstBucket, $dstKey), true);
        $this->assertEquals($fileMd5, $objectMd5);

        $metaProp = $client->headObject(new Oss\Models\HeadObjectRequest($buckeName, $dstKey));
        $tagProp = $client->getObjectTagging(new Oss\Models\GetObjectTaggingRequest($buckeName, $dstKey));
        $this->assertEquals('application/octet-stream', $metaProp->contentType);
        $this->assertNull($metaProp->contentEncoding);
        $this->assertNull($metaProp->contentDisposition);
        $this->assertNull($metaProp->cacheControl);
        $this->assertNull($metaProp->expires);
        $this->assertNull($metaProp->metadata);
        $this->assertNull($metaProp->taggingCount);
        $this->assertEmpty($tagProp->tagSet->tags);

        // case 2, has metadata & tagging
        $dstBucket = $buckeName;
        $dstKey = "multipart-copy-repalce-metadata-and-tagging-$key.js";
        $copyRequest = new Oss\Models\CopyObjectRequest(
            $dstBucket,
            $dstKey,
            $sourceBucket,
            $sourceKey
        );
        $copyRequest->metadataDirective = 'REPLACE';
        $copyRequest->taggingDirective = 'REPLACE';
        $copyRequest->contentType = 'text/txt';
        $copyRequest->cacheControl = 'cache-123';
        $copyRequest->contentDisposition = 'contentDisposition';
        $copyRequest->expires = '123';
        $copyRequest->metadata = ['test1' => 'val1', 'test2' => 'value2'];
        $copyRequest->tagging = 'k3=v3&k4=v4';
        $result = $copier->copy(
            $copyRequest,
            [
                'multipart_copy_threshold' => 0,
                'disable_shallow_copy' => true
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringContainsString('-3', $result->etag);
        $objectMd5 = md5($this->object_get_contents($client, $dstBucket, $dstKey), true);
        $this->assertEquals($fileMd5, $objectMd5);

        $metaProp = $client->headObject(new Oss\Models\HeadObjectRequest($buckeName, $dstKey));
        $tagProp = $client->getObjectTagging(new Oss\Models\GetObjectTaggingRequest($buckeName, $dstKey));
        $this->assertEquals('text/txt', $metaProp->contentType);
        $this->assertEquals('cache-123', $metaProp->cacheControl);
        $this->assertEquals('contentDisposition', $metaProp->contentDisposition);
        $this->assertEquals('123', $metaProp->expires);
        $this->assertNull($metaProp->contentEncoding);
        $this->assertEquals('val1', $metaProp->metadata['test1']);
        $this->assertEquals('value2', $metaProp->metadata['test2']);
        $this->assertEquals(2, $metaProp->taggingCount);
        $this->assertEquals('k3', $tagProp->tagSet->tags[0]->key);
        $this->assertEquals('v3', $tagProp->tagSet->tags[0]->value);
        $this->assertEquals('k4', $tagProp->tagSet->tags[1]->key);
        $this->assertEquals('v4', $tagProp->tagSet->tags[1]->value);

        // multipart copy, 'copy' directive
        $dstBucket = $buckeName;
        $dstKey = "multipart-copy-copy-metadata-and-tagging-$key.js";
        $copyRequest = new Oss\Models\CopyObjectRequest(
            $dstBucket,
            $dstKey,
            $sourceBucket,
            $sourceKey
        );
        $copyRequest->contentType = 'text/txt';
        $copyRequest->cacheControl = 'cache-123';
        $copyRequest->contentDisposition = 'contentDisposition';
        $copyRequest->expires = '123';
        $copyRequest->metadata = ['test1' => 'val1', 'test2' => 'value2'];
        $copyRequest->tagging = 'k3=v3&k4=v4';
        $result = $copier->copy(
            $copyRequest,
            [
                'multipart_copy_threshold' => 0,
                'disable_shallow_copy' => true
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(32, strlen($result->uploadId));
        $this->assertStringContainsString('-3', $result->etag);
        $objectMd5 = md5($this->object_get_contents($client, $dstBucket, $dstKey), true);
        $this->assertEquals($fileMd5, $objectMd5);

        $metaProp = $client->headObject(new Oss\Models\HeadObjectRequest($buckeName, $dstKey));
        $tagProp = $client->getObjectTagging(new Oss\Models\GetObjectTaggingRequest($buckeName, $dstKey));
        $this->assertEquals('text/js', $metaProp->contentType);
        $this->assertEquals('no-cache', $metaProp->cacheControl);
        $this->assertNull($metaProp->contentDisposition);
        $this->assertNull($metaProp->expires);
        $this->assertNull($metaProp->contentEncoding);
        $this->assertEquals('value1', $metaProp->metadata['key1']);
        $this->assertEquals('value2', $metaProp->metadata['key2']);
        $this->assertEquals(2, $metaProp->taggingCount);
        $this->assertEquals('k1', $tagProp->tagSet->tags[0]->key);
        $this->assertEquals('v1', $tagProp->tagSet->tags[0]->value);
        $this->assertEquals('k2', $tagProp->tagSet->tags[1]->key);
        $this->assertEquals('v2', $tagProp->tagSet->tags[1]->value);
    }

    public function testCopierFail(): void
    {
        $client = self::getDefaultClient();
        $noPermClient = self::getInvalidAkClient();
        $buckeName = self::$bucketName;
        $key = self::randomObjectName();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $content = \file_get_contents($filename);

        $request = new Oss\Models\PutObjectRequest($buckeName, $key);
        $request->body = Oss\Utils::streamFor($content);

        $result = $client->putObject($request);

        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $metaProp = $client->headObject(new Oss\Models\HeadObjectRequest($buckeName, $key));

        $sourceBucket = $buckeName;
        $sourceKey = $key;

        $copier = $noPermClient->newCopier(
            [
                'part_size' => $partSize,
                'parallel_num' => 1,
            ]
        );

        // get object metafail
        try {
            $dstBucket = $buckeName;
            $dstKey = "single-copy-$key";
            $result = $copier->copy(
                new Oss\Models\CopyObjectRequest(
                    $dstBucket,
                    $dstKey,
                    $sourceBucket,
                    $sourceKey
                ),
                [
                    'part_size' => $totalSize * 2,
                ]
            );
        } catch (Oss\Exception\CopyException $e) {
            $this->assertStringContainsString('copy failed', $e);
            $this->assertStringContainsString("oss://$dstBucket/$dstKey", $e);
            $this->assertStringContainsString('Operation error HeadObject', $e);
            $se = $e->getPrevious()->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // single copy fail
        try {
            $dstBucket = $buckeName;
            $dstKey = "single-copy-$key";
            $result = $copier->copy(
                new Oss\Models\CopyObjectRequest(
                    $dstBucket,
                    $dstKey,
                    $sourceBucket,
                    $sourceKey
                ),
                [
                    'part_size' => $totalSize * 2,
                    'meta_prop' => $metaProp,
                ]
            );
        } catch (Oss\Exception\CopyException $e) {
            $this->assertStringContainsString('copy failed', $e);
            $this->assertStringContainsString("oss://$dstBucket/$dstKey", $e);
            $this->assertStringContainsString('Operation error CopyObject', $e);
            $se = $e->getPrevious()->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }

        // multipart copy fail
        try {
            $dstBucket = $buckeName;
            $dstKey = "multipart-copy-$key";
            $result = $copier->copy(
                new Oss\Models\CopyObjectRequest(
                    $dstBucket,
                    $dstKey,
                    $sourceBucket,
                    $sourceKey
                ),
                [
                    'multipart_copy_threshold' => 0,
                    'disable_shallow_copy' => true,
                    'meta_prop' => $metaProp,
                ]
            );
        } catch (Oss\Exception\CopyException $e) {
            $this->assertStringContainsString('copy failed', $e);
            $this->assertStringContainsString("oss://$dstBucket/$dstKey", $e);
            $this->assertStringContainsString('Operation error InitiateMultipartUpload', $e);
            $se = $e->getPrevious()->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
            }
        }
    }

    public function testGetObjectToFile(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $objectName = self::randomObjectName();
        $totalSize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $content = \file_get_contents($filename);

        $request = new Oss\Models\PutObjectRequest($bucketName, $objectName);
        $request->body = Oss\Utils::streamFor($content);
        $result = $client->putObject($request);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $saveToFilename = self::getTempFileName();
        $result = $client->getObjectToFile(new Oss\Models\GetObjectRequest(
            $bucketName,
            $objectName
        ), $saveToFilename);
        $this->assertEquals($totalSize, $result->contentLength);
        $this->assertFileEquals($filename, $saveToFilename);
    }

    public function testGetObjectToFileFail(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $objectName = self::randomObjectName();
        $filename = self::getTempFileName();
        try {
            $client->getObjectToFile(new Oss\Models\GetObjectRequest(
                $bucketName,
                $objectName
            ), $filename);
        } catch (\Exception $e) {
            $this->assertStringContainsString('Operation error GetObject', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchKey', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
            }
        }
    }

    public function testPutObjectFromFile(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $objectName = self::randomObjectName();
        $totalSize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);

        $request = new Oss\Models\PutObjectRequest($bucketName, $objectName);
        $result = $client->putObjectFromFile($request, $filename);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $saveToFilename = self::getTempFileName();
        $result = $client->getObjectToFile(new Oss\Models\GetObjectRequest(
            $bucketName,
            $objectName
        ), $saveToFilename);
        $this->assertEquals($totalSize, $result->contentLength);
        $this->assertFileEquals($filename, $saveToFilename);
    }

    public function testPutObjectFormFileFail(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $objectName = self::randomObjectName();
        $filename = self::getTempFileName() . '-not-exist';
        try {
            $client->putObjectFromFile(new Oss\Models\PutObjectRequest(
                $bucketName,
                $objectName
            ), $filename);
        } catch (\Exception $e) {
            $this->assertStringContainsString('to open', $e);
            $this->assertInstanceOf(\RuntimeException::class, $e);
        }
    }
}
