<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use GuzzleHttp\Promise as P;
use GuzzleHttp\Psr7\LimitStream;
use GuzzleHttp\Psr7\LazyOpenStream;
use AlibabaCloud\Oss\V2 as Oss;

class ClientMiscTest extends TestIntegration
{
    public function testPutObjectProgress()
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $filename = self::getTempFileName();
        $size = 512 * 1024 + 1234;
        $this->generateFile($filename, $size);
        $handle = fopen($filename, 'rb');
        $reqeust = new Oss\Models\PutObjectRequest($bucketName, 'key-123+-/123%/345');
        $reqeust->body = Oss\Utils::streamFor($handle);
        $gotIncrement = 0;
        $gotSize = 0;
        $gotTotal = 0;
        $reqeust->progressFn = static function (int $increment, int $transferred, int $total) use (&$gotIncrement, &$gotSize, &$gotTotal) {
            $gotIncrement += $increment;
            $gotSize = $transferred;
            $gotTotal = $total;
        };
        $client->putObject($reqeust);
        fclose($handle);
        $this->assertEquals($size, $gotIncrement);
        $this->assertEquals($size, $gotSize);
        $this->assertEquals($size, $gotTotal);
    }

    public function testAppendObjectProgress()
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $filename = self::getTempFileName();
        $size = 512 * 1024 + 1234;
        $this->generateFile($filename, $size);
        $handle = fopen($filename, 'rb');
        $reqeust = new Oss\Models\AppendObjectRequest($bucketName, 'append-key-123.txt');
        $reqeust->position = 0;
        $reqeust->body = Oss\Utils::streamFor($handle);
        $gotIncrement = 0;
        $gotSize = 0;
        $gotTotal = 0;
        $reqeust->progressFn = static function (int $increment, int $transferred, int $total) use (&$gotIncrement, &$gotSize, &$gotTotal) {
            $gotIncrement += $increment;
            $gotSize = $transferred;
            $gotTotal = $total;
        };
        $client->appendObject($reqeust);
        fclose($handle);
        $this->assertEquals($size, $gotIncrement);
        $this->assertEquals($size, $gotSize);
        $this->assertEquals($size, $gotTotal);

        $reqeust = new Oss\Models\AppendObjectRequest($bucketName, 'append-key-123.txt');
        $reqeust->body = Oss\Utils::streamFor('hello world');
        $reqeust->position = $size;
        $gotIncrement = 0;
        $gotSize = 0;
        $gotTotal = 0;
        $reqeust->progressFn = static function (int $increment, int $transferred, int $total) use (&$gotIncrement, &$gotSize, &$gotTotal) {
            $gotIncrement += $increment;
            $gotSize = $transferred;
            $gotTotal = $total;
        };
        $client->appendObject($reqeust);
        $this->assertEquals(11, $gotIncrement);
        $this->assertEquals(11, $gotSize);
        $this->assertEquals(11, $gotTotal);
    }

    public function testUploadPartProgress()
    {
        $client = $this->getDefaultClient();
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $key = 'bigfile.tmp';
        $initResult = $client->initiateMultipartUpload(
            new Oss\Models\InitiateMultipartUploadRequest(
                self::$bucketName,
                $key,
            )
        );
        $this->assertEquals(200, $initResult->statusCode);
        $this->assertNotEmpty($initResult->uploadId);
        $file = fopen($filename, 'rb');
        $parts = [];
        $fileStream = Oss\Utils::streamFor($file);
        $gotIncrement = 0;
        for ($i = 0, $partNum = 1; $i < $totalSize; $i += $partSize, $partNum++) {
            $gotSize = 0;
            $gotTotal = 0;
            $body = new LimitStream($fileStream, $partSize, $i);
            $sendSize = $body->getSize();
            $partResult = $client->uploadPart(
                new Oss\Models\UploadPartRequest(
                    self::$bucketName,
                    $key,
                    $partNum,
                    $initResult->uploadId,
                    null,
                    null,
                    null,
                    null,
                    $body,
                    static function (int $increment, int $transferred, int $total) use (&$gotIncrement, &$gotSize, &$gotTotal) {
                        $gotIncrement += $increment;
                        $gotSize = $transferred;
                        $gotTotal = $total;
                    }
                )
            );
            $this->assertEquals(200, $partResult->statusCode);
            $this->assertEquals($sendSize, $gotSize);
            $this->assertEquals($sendSize, $gotTotal);
            $part = new Oss\Models\UploadPart(
                $partNum,
                $partResult->etag,
            );
            $parts[] = $part;
        }
        //$this->assertEquals($totalSize, $gotIncrement);
        fclose($file);
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
        $objMd5 = base64_encode(md5($getObj->body, true));
        $fileMd5 = base64_encode(md5(file_get_contents($filename), true));
        $this->assertEquals($fileMd5, $objMd5);
    }

    public function testUploadPartUseCoroutine(): void
    {
        $totalSize = 250 * 1024 + 123;
        $partSize = 100 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $totalSize);
        $key = 'coroutine.tmp';

        $bucketName = self::$bucketName;

        // upload context
        $context['client'] = self::getDefaultClient();
        $context['request'] = new Oss\Models\PutObjectRequest($bucketName, $key);
        $context['filename'] = $filename;
        $context['parallel_num'] = 2;
        $context['part_size'] = $partSize;

        // use coroutine
        $promisor = P\Coroutine::of(function () use (&$context) {
            $client = $context['client'];

            // initiate multipart upload
            $request = new Oss\Models\InitiateMultipartUploadRequest();
            Oss\Utils::copyRequest($request, $context['request']);
            yield $client->initiateMultipartUploadAsync($request)->then(
                function (Oss\Models\InitiateMultipartUploadResult $result) use (&$context) {
                    $context['upload_id'] = $result->uploadId;
                },
            );

            // upload part
            $context['errors'] = [];
            $context['parts'] = [];
            $uploadFns = function () use (&$context) {
                $client = $context['client'];
                $part_size = intval($context['part_size']);
                $filepath = strval($context['filename']);
                $source = new LazyOpenStream($filepath, 'rb');
                $source->seek(0);

                for ($partNumber = 1; $source->tell() < $source->getSize(); $partNumber++) {
                    $body = new LimitStream(
                        new LazyOpenStream($source->getMetadata('uri'), 'rb'),
                        $part_size,
                        $source->tell()
                    );
                    $source->seek(min($source->tell() + $part_size, $source->getSize()));

                    $request = new Oss\Models\UploadPartRequest();
                    Oss\Utils::copyRequest($request, $context['request']);
                    $request->partNumber = $partNumber;
                    $request->uploadId = $context['upload_id'];
                    $request->body = $body;
                    yield $partNumber => $client->uploadPartAsync($request)->otherwise(
                        function ($reason) use (&$context) {
                            $context['errors'][] = $reason;
                            return P\Create::rejectionFor($reason);
                        },
                    );
                    if (!empty($context['errors'])) {
                        break;
                    }
                }
            };

            $each = new P\EachPromise(
                $uploadFns(),
                [
                    'concurrency' => $context['parallel_num'],
                    'fulfilled' => function (Oss\Models\UploadPartResult $result, $key) use (&$context) {
                        $context['parts'][] = new Oss\Models\UploadPart($key, $result->etag);
                        return $result;
                    }
                ]
            );
            yield $each->promise();

            if (!empty($context['errors'])) {
                throw end($context['errors']);
            }

            $parts = $context['parts'];
            usort($parts, function ($a, $b) {
                if ($a->partNumber == $b->partNumber)
                    return 0;
                return $a->partNumber < $b->partNumber ? -1 : 1;
            });
            $request = new Oss\Models\CompleteMultipartUploadRequest();
            Oss\Utils::copyRequest($request, $context['request']);
            $request->uploadId = $context['upload_id'];
            $request->completeMultipartUpload = new Oss\Models\CompleteMultipartUpload($parts);
            yield $client->completeMultipartUploadAsync($request)->then(
                function (Oss\Models\CompleteMultipartUploadResult $result) use (&$context) {
                    $context['upload_result'] = $result;
                    return $result;
                },
            );
        });

        // wait finish
        $promisor->wait();

        // check result
        $result = $context['upload_result'];
        $this->assertInstanceOf(Oss\Models\CompleteMultipartUploadResult::class, $result);
        if ($result instanceof Oss\Models\CompleteMultipartUploadResult) {
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(True, count($result->headers) > 0);
            $this->assertEquals(24, strlen($result->requestId));
            $this->assertStringEndsWith('-3"', $result->etag);
        }

        $fileMd5 = md5(file_get_contents($filename), true);
        $objectMd5 = md5($this->object_get_contents(self::getDefaultClient(), $bucketName, $key), true);
        $this->assertEquals($fileMd5, $objectMd5);
    }

    public function testGetObjectIntoSink(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $filename = self::getTempFileName();
        $key = self::randomObjectName();
        $size = 123 * 1024 + 1234;
        $this->generateFile($filename, $size);
        $content = file_get_contents($filename);
        $handle = fopen($filename, 'rb');
        $request = new Oss\Models\PutObjectRequest($bucketName, $key);
        $request->body = Oss\Utils::streamFor($handle);
        $result = $client->putObject($request);
        fclose($handle);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        // use sink to save object into file
        $filenameSave = self::getTempFileName();
        $this->assertFileNotEquals($filename, $filenameSave);
        $request = new Oss\Models\GetObjectRequest($bucketName, $key);
        $result = $client->getObject(
            $request,
            [
                'request_options' =>
                    ['sink' => $filenameSave]
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertFileEquals($filename, $filenameSave);
        $this->assertNull($result->body);

        // use sink to save object into memeroy
        $sink = \GuzzleHttp\Psr7\Utils::tryFopen('php://temp', 'w+');
        $request = new Oss\Models\GetObjectRequest($bucketName, $key);
        $result = $client->getObject(
            $request,
            [
                'request_options' =>
                    ['sink' => $sink]
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertNotNull($result->body);
        $this->assertEquals($content, $result->body->getContents());
    }

    public function testGetObjectStreamMode(): void
    {
        $client = self::getDefaultClient();
        $bucketName = self::$bucketName;
        $filename = self::getTempFileName();
        $key = self::randomObjectName();
        $size = 123 * 1024 + 1234;
        $this->generateFile($filename, $size);
        $content = file_get_contents($filename);
        $handle = fopen($filename, 'rb');
        $request = new Oss\Models\PutObjectRequest($bucketName, $key);
        $request->body = Oss\Utils::streamFor($handle);
        $result = $client->putObject($request);
        fclose($handle);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        // use non stream
        $request = new Oss\Models\GetObjectRequest($bucketName, $key);
        $result = $client->getObject(
            $request,
            [
                'request_options' =>
                    ['stream' => false]
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertNotNull($result->body);
        $this->assertTrue($result->body->isSeekable());
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals($size, $result->body->getSize());
        $this->assertEquals('', $result->body->read(100));

        // use stream
        $request = new Oss\Models\GetObjectRequest($bucketName, $key);
        $result = $client->getObject(
            $request,
            [
                'request_options' =>
                    ['stream' => true]
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertNotNull($result->body);
        $this->assertFalse($result->body->isSeekable());
        $this->assertNull($result->body->getSize());
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals('', $result->body->read(100));
    }

}
