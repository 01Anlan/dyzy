<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use GuzzleHttp;
use AlibabaCloud\Oss\V2 as Oss;
use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\LazyOpenStream;


class EncryptionClientTest extends TestIntegration
{
    const RSA_PUBLIC_KEY = <<<BBB
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCokfiAVXXf5ImFzKDw+XO/UByW
6mse2QsIgz3ZwBtMNu59fR5zttSx+8fB7vR4CN3bTztrP9A6bjoN0FFnhlQ3vNJC
5MFO1PByrE/MNd5AAfSVba93I6sx8NSk5MzUCA4NJzAUqYOEWGtGBcom6kEF6MmR
1EKib1Id8hpooY5xaQIDAQAB
-----END PUBLIC KEY-----
BBB;

    const RSA_PRIVATE_KEY = <<<BBB
-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKiR+IBVdd/kiYXM
oPD5c79QHJbqax7ZCwiDPdnAG0w27n19HnO21LH7x8Hu9HgI3dtPO2s/0DpuOg3Q
UWeGVDe80kLkwU7U8HKsT8w13kAB9JVtr3cjqzHw1KTkzNQIDg0nMBSpg4RYa0YF
yibqQQXoyZHUQqJvUh3yGmihjnFpAgMBAAECgYA49RmCQ14QyKevDfVTdvYlLmx6
kbqgMbYIqk+7w611kxoCTMR9VMmJWgmk/Zic9mIAOEVbd7RkCdqT0E+xKzJJFpI2
ZHjrlwb21uqlcUqH1Gn+wI+jgmrafrnKih0kGucavr/GFi81rXixDrGON9KBE0FJ
cPVdc0XiQAvCBnIIAQJBANXu3htPH0VsSznfqcDE+w8zpoAJdo6S/p30tcjsDQnx
l/jYV4FXpErSrtAbmI013VYkdJcghNSLNUXppfk2e8UCQQDJt5c07BS9i2SDEXiz
byzqCfXVzkdnDj9ry9mba1dcr9B9NCslVelXDGZKvQUBqNYCVxg398aRfWlYDTjU
IoVVAkAbTyjPN6R4SkC4HJMg5oReBmvkwFCAFsemBk0GXwuzD0IlJAjXnAZ+/rIO
ItewfwXIL1Mqz53lO/gK+q6TR585AkB304KUIoWzjyF3JqLP3IQOxzns92u9EV6l
V2P+CkbMPXiZV6sls6I4XppJXX2i3bu7iidN3/dqJ9izQK94fMU9AkBZvgsIPCot
y1/POIbv9LtnviDKrmpkXgVQSU4BmTPvXwTJm8APC7P/horSh3SVf1zgmnsyjm9D
hO92gGc+4ajL
-----END PRIVATE KEY-----
BBB;

    const RSA_PUBLIC_KEY1 = <<<BBB
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzHhuPkhigHeYG0eOFjuX
nkMP1llge5NlTYxLyU1/jjuWnIgOyZKQHIdIyS2elnzGPFiEY60ywK4R8/erj/Er
rzY+J3UM4ppgZUuLM+o4i+kYImWX0/ebwjVYpllJMgGJHfcL7tgj1fNvj09WS7WU
y3TfciDl+Wj13oEnVRwEAiM8+gvqWgOsuu//JbecX73qn/ZPPyQoyylhucpk+y90
pg80rLlSCeh1odP5IbvGqSudOxemjJq1ChPMu5qOh+QFNPHWh5zrrDRYKdSU4tRq
FIvmOXSg2Gdp7oyKCxoiDxSJOgAAcPL0pWRyW4Zhhpfc0YU1mRw3LWfXVVgwnmN1
iQIDAQAB
-----END PUBLIC KEY-----
BBB;
    const RSA_PRIVATE_KEY1 = <<<BBB
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAzHhuPkhigHeYG0eOFjuXnkMP1llge5NlTYxLyU1/jjuWnIgO
yZKQHIdIyS2elnzGPFiEY60ywK4R8/erj/ErrzY+J3UM4ppgZUuLM+o4i+kYImWX
0/ebwjVYpllJMgGJHfcL7tgj1fNvj09WS7WUy3TfciDl+Wj13oEnVRwEAiM8+gvq
WgOsuu//JbecX73qn/ZPPyQoyylhucpk+y90pg80rLlSCeh1odP5IbvGqSudOxem
jJq1ChPMu5qOh+QFNPHWh5zrrDRYKdSU4tRqFIvmOXSg2Gdp7oyKCxoiDxSJOgAA
cPL0pWRyW4Zhhpfc0YU1mRw3LWfXVVgwnmN1iQIDAQABAoIBAQCf98SAU89EpMxK
42OFf1/ygJL+ZvR2Ge4SiqWsO0aFN5dwpX20NEctGqZWRquhHsNU6QfCl/lyB32i
Om1t8wfzT2O3KPtIufCar0yb9C4DP/0SxBrRyhGBEo1lr8r1JYBqAiLC3TTEKW1p
WG+yUcC0oJ5EQvrJc1WQm8jy7DUymYpLTkj+wW5N7qnS7SLI5L2rHfpxMNGIHAs5
P4gac2E7pQ4f3iLLn2eEx7UZQ1IinW9TAll2QBMZa6ZOVnQsFO3PlnsNAxZ+lfvY
gqbuhjoYLlAeQkSxsBPqLXss87w2Qw4+NuF0J/ogXSePjv4PFEPIXWYACiCd1Y/x
47VGprg1AoGBAPSajNcTi0gZGqGvp6ymSNx7i9VHao4gh9Tfyz0bDBiuLOqOXs3N
4zswcZTfXeK+h95smuudQfRsSK29MR+3kOwN4cG+bKZre5AI1/9t8AkYiZepjhbr
1z370a2tS5l3tqgZx/zW7tJt0SvYwknqO5ySnvJTPRmuYXg9y2LjtPWnAoGBANX/
M2bUuGgSCJz81x0njsF7dWXnyps3UpTE0Ck3/Y4DBtBIXMUfhmnUVQa7lPMmcQcn
/cJGx4NC6g2nUTW49RkKSEr4nsfJ0F73YOTB9iAdZ4QeLkWPz8IMQa1ENCchV/ms
rEUc7/S9f3la0K+p47LS/g6Z0PO+8+3JSdA1egFPAoGAXLXXfA2kVQdu2KnDW+UK
6MbLEWOoN4aM9Vp9pgOCajhaPe0Icej/n4eVBWBELZUZ2mw/q95HCWWhhniXDfZ9
r3rzfoO2mr1ScB1qAR6iRFBQlnNlr7pkMtInfzSX2utNCBn9ew/cJVYKWhwmR+3H
+mh4ZlC2b+1wdCq31BuKkzECgYEAwFubus1vzayYLXVhcAWE3wq45pdKmedKxgt8
CfEYbDTwRP0m1tKVoj+JBnpLU520b/hUs/Onl6fod8l0yFOvjYienzWIlJImSZcY
c8ieExQbXrk6YrD40bbuum7aamogiH/cgmuWjmpgUZd+isitsqrSUBGXr+JvpckQ
HqZTOyUCgYEArE6eV+kUT/cjY+cbJhHx4VT/+gtPjxncDZayRjdxcHRBn1MLHqHQ
Db/hHAmtLkR2HWA9DRPhFxflrffBNtJnlOtmA3KL/5a2AdP6hROM8SMe1+DKrOtR
3JiszMbraILeDMuUD5tk8lwd487gYSxKWAO1NZjGvsum376kn5rUKYM=
-----END RSA PRIVATE KEY-----
BBB;

    public function testPutAndGetObject()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->contentMd5 = base64_encode(md5_file($filename, true));
        $putObjRequest->contentLength = $filesize;
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        // undec content
        $unDecContent = $this->object_get_contents($client, $bucketName, $key);
        $this->assertEquals($filesize, strlen($unDecContent));
        $this->assertNotEquals($content, $unDecContent);

        // get object
        $result = $eclient->getObject(new Oss\Models\GetObjectRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals($filesize, $result->contentLength);
        $this->assertEquals(strval($filesize), $result->headers['Content-Length']);
        $this->assertNull($result->contentRange);

        foreach ([0, 1, 2, 3, 4, 5, 7, 16, 17, 32, 63, 64, 79] as $offset) {
            $request = new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            );
            $request->rangeHeader = "bytes=$offset-";
            $result = $eclient->getObject($request);
            $this->assertEquals(206, $result->statusCode);
            $this->assertEquals('Partial Content', $result->status);
            $this->assertEquals(substr($content, $offset), $result->body->getContents());
            $this->assertEquals($filesize - $offset, $result->contentLength);
            $this->assertEquals(strval($filesize - $offset), $result->headers['Content-Length']);
            $contentRange = sprintf("bytes %d-%d/%d", $offset, $filesize - 1, $filesize);
            $this->assertEquals($contentRange, $result->contentRange);
            $this->assertEquals($contentRange, $result->headers['Content-Range']);
        }

        foreach ([0, 1, 2, 17, 32, 63, 79] as $offset) {
            $gotSize = 1234;
            $rangeEnd = $offset + $gotSize - 1;
            $request = new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            );
            $request->rangeHeader = "bytes=$offset-$rangeEnd";
            $result = $eclient->getObject($request);
            $this->assertEquals(206, $result->statusCode);
            $this->assertEquals('Partial Content', $result->status);
            $this->assertEquals(substr($content, $offset, $gotSize), $result->body->getContents());
            $this->assertEquals($gotSize, $result->contentLength);
            $this->assertEquals(strval($gotSize), $result->headers['Content-Length']);
            $contentRange = sprintf("bytes %d-%d/%d", $offset, $rangeEnd, $filesize);
            $this->assertEquals($contentRange, $result->contentRange);
            $this->assertEquals($contentRange, $result->headers['Content-Range']);
        }
    }

    public function testPutAndGetObjectWithManyRsa()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['demo1']
        );

        $masterCipher2 = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY1,
            self::RSA_PRIVATE_KEY1,
            ['demo2']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher2, [$masterCipher, $masterCipher2]);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->contentMd5 = base64_encode(md5_file($filename, true));
        $putObjRequest->contentLength = $filesize;
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        // undec content
        $unDecContent = $this->object_get_contents($client, $bucketName, $key);
        $this->assertEquals($filesize, strlen($unDecContent));
        $this->assertNotEquals($content, $unDecContent);

        // get object
        $result = $eclient->getObject(new Oss\Models\GetObjectRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals($filesize, $result->contentLength);
        $this->assertEquals(strval($filesize), $result->headers['Content-Length']);
        $this->assertNull($result->contentRange);

        foreach ([0, 1, 2, 3, 4, 5, 7, 16, 17, 32, 63, 64, 79] as $offset) {
            $request = new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            );
            $request->rangeHeader = "bytes=$offset-";
            $result = $eclient->getObject($request);
            $this->assertEquals(206, $result->statusCode);
            $this->assertEquals('Partial Content', $result->status);
            $this->assertEquals(substr($content, $offset), $result->body->getContents());
            $this->assertEquals($filesize - $offset, $result->contentLength);
            $this->assertEquals(strval($filesize - $offset), $result->headers['Content-Length']);
            $contentRange = sprintf("bytes %d-%d/%d", $offset, $filesize - 1, $filesize);
            $this->assertEquals($contentRange, $result->contentRange);
            $this->assertEquals($contentRange, $result->headers['Content-Range']);
        }

        foreach ([0, 1, 2, 17, 32, 63, 79] as $offset) {
            $gotSize = 1234;
            $rangeEnd = $offset + $gotSize - 1;
            $request = new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            );
            $request->rangeHeader = "bytes=$offset-$rangeEnd";
            $result = $eclient->getObject($request);
            $this->assertEquals(206, $result->statusCode);
            $this->assertEquals('Partial Content', $result->status);
            $this->assertEquals(substr($content, $offset, $gotSize), $result->body->getContents());
            $this->assertEquals($gotSize, $result->contentLength);
            $this->assertEquals(strval($gotSize), $result->headers['Content-Length']);
            $contentRange = sprintf("bytes %d-%d/%d", $offset, $rangeEnd, $filesize);
            $this->assertEquals($contentRange, $result->contentRange);
            $this->assertEquals($contentRange, $result->headers['Content-Range']);
        }
    }

    public function testGetObjectStreamMode()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 250 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNotEquals($content, $this->object_get_contents($client, $bucketName, $key));

        // get object
        $args = [
            'request_options' =>
                ['stream' => true]
        ];
        $result = $eclient->getObject(
            new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            ),
            $args
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertFalse($result->body->isSeekable());
        $this->assertInstanceOf(Oss\Crypto\ReadDecryptStream::class, $result->body);
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals($filesize, $result->contentLength);
        $this->assertEquals(strval($filesize), $result->headers['Content-Length']);
        $this->assertNull($result->contentRange);
        $this->assertCount(1, $args['request_options']);

        // range get
        $args = [
            'request_options' =>
                ['stream' => true]
        ];
        $request = new Oss\Models\GetObjectRequest(
            $bucketName,
            $key
        );
        $offset = 12345;
        $gotSize = 22234;
        $rangeEnd = $offset + $gotSize - 1;
        $request->rangeHeader = sprintf("bytes=%d-%d", $offset, $rangeEnd);
        $result = $eclient->getObject($request, $args);
        $this->assertEquals(206, $result->statusCode);
        $this->assertEquals('Partial Content', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertFalse($result->body->isSeekable());
        $this->assertInstanceOf(Oss\Crypto\ReadDecryptStream::class, $result->body);
        $this->assertEquals(substr($content, $offset, $gotSize), $result->body->getContents());
        $this->assertEquals($gotSize, $result->contentLength);
        $this->assertEquals(strval($gotSize), $result->headers['Content-Length']);
        $contentRange = sprintf("bytes %d-%d/%d", $offset, $rangeEnd, $filesize);
        $this->assertEquals($contentRange, $result->contentRange);
        $this->assertCount(1, $args['request_options']);
    }

    public function testHeadObject()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // head object
        $result = $eclient->headObject(new Oss\Models\HeadObjectRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertNotEmpty($result->metadata['client-side-encryption-key']);
        $this->assertNotEmpty($result->metadata['client-side-encryption-start']);
        $this->assertEquals('AES/CTR/NoPadding', $result->metadata['client-side-encryption-cek-alg']);
        $this->assertEquals('RSA/NONE/PKCS1Padding', $result->metadata['client-side-encryption-wrap-alg']);
        $this->assertEquals('{"key":"value"}', $result->metadata['client-side-encryption-matdesc']);
    }

    public function testGetObjectMeta()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object meta
        $result = $eclient->getObjectMeta(new Oss\Models\GetObjectMetaRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals(strlen($content), $result->contentLength);
    }

    public function testObjectMultipart()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . '-multipart';
        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );
        $eclient = new Oss\EncryptionClient($client, $masterCipher);
        $partSize = 200 * 1024;
        $length = 500 * 1024;
        $bigFileName = self::getTempFileName() . "-bigfile.tmp";
        $this->generateFile($bigFileName, $length);
        try {
            $initRequest = new Oss\Models\InitiateMultipartUploadRequest(
                $bucketName,
                $key,
            );
            $initRequest->cseDataSize = $length;
            $initRequest->csePartSize = $partSize;
            $initRequest->contentMd5 = base64_encode(md5_file($bigFileName, true));
            $initRequest->contentLength = $length;
            $initResult = $eclient->initiateMultipartUpload(
                $initRequest
            );
            $this->assertEquals(200, $initResult->statusCode);
            $this->assertNotEmpty($initResult->uploadId);
            $file = fopen($bigFileName, 'r');
            $parts = array();
            if ($file) {
                $i = 1;
                while (!feof($file)) {
                    $chunk = fread($file, $partSize);
                    $partRequest = new Oss\Models\UploadPartRequest(
                        $bucketName,
                        $key,
                        $i,
                        $initResult->uploadId,
                        strlen($chunk),
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    );
                    $partRequest->encryptionMultipartContext = $initResult->encryptionMultipartContext;
                    $partResult = $eclient->uploadPart($partRequest);
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
            $comResult = $eclient->completeMultipartUpload(
                new Oss\Models\CompleteMultipartUploadRequest(
                    $bucketName,
                    $key,
                    $initResult->uploadId,
                    null,
                    new Oss\Models\CompleteMultipartUpload(
                        $parts
                    ),
                )
            );
            $this->assertEquals(200, $comResult->statusCode);
            $this->assertEquals($bucketName, $comResult->bucket);
            $this->assertEquals($key, $comResult->key);
            $this->assertNotEmpty($comResult->etag);
            $this->assertNotEmpty($comResult->location);
            $getObj = $eclient->getObject(new Oss\Models\GetObjectRequest(
                $bucketName,
                $key,
            ));
            $this->assertEquals(base64_encode(md5(file_get_contents($bigFileName), true)), base64_encode(md5($getObj->body, true)));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $initRequest = new Oss\Models\InitiateMultipartUploadRequest(
                $bucketName,
                $key,
            );
            $initRequest->cseDataSize = $length;
            $initRequest->csePartSize = $partSize;
            $initResult = $eclient->initiateMultipartUpload(
                $initRequest
            );
            $this->assertEquals(200, $initResult->statusCode);
            $this->assertNotEmpty($initResult->uploadId);
            $file = fopen($bigFileName, 'r');
            $parts = array();
            if ($file) {
                $i = 1;
                while (!feof($file)) {
                    $chunk = fread($file, $partSize);
                    $partRequest = new Oss\Models\UploadPartRequest(
                        $bucketName,
                        $key,
                        $i,
                        $initResult->uploadId,
                        strlen($chunk),
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    );
                    $partRequest->contentMd5 = base64_encode(md5($chunk, true));
                    $partRequest->encryptionMultipartContext = $initResult->encryptionMultipartContext;
                    $partResult = $eclient->uploadPart($partRequest);
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
            $listResult = $eclient->listParts(
                new Oss\Models\ListPartsRequest(
                    $bucketName,
                    $key,
                    $initResult->uploadId,
                )
            );
            $this->assertEquals(200, $listResult->statusCode);
            $this->assertEquals($bucketName, $listResult->bucket);
            $this->assertEquals($key, $listResult->key);
            $this->assertCount(3, $listResult->parts);

            $abort = $eclient->abortMultipartUpload(
                new Oss\Models\AbortMultipartUploadRequest(
                    self::$bucketName,
                    $key,
                    $initResult->uploadId,
                )
            );
            $this->assertEquals(204, $abort->statusCode);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testUploader()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . '-multipart-uploader';
        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);
        $partSize = 200 * 1024;
        $length = 500 * 1024;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $length);
        try {
            $u = $eclient->newUploader();
            $u->uploadFile(new \AlibabaCloud\Oss\V2\Models\PutObjectRequest(
                $bucketName,
                $key,
            ), $filename);
            $getObj = $eclient->getObject(new Oss\Models\GetObjectRequest(
                $bucketName,
                $key,
            ));
            $this->assertEquals(base64_encode(md5(file_get_contents($filename), true)), base64_encode(md5($getObj->body, true)));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $u = $eclient->newUploader(array(
                'part_size' => $partSize,
                'total_size' => $length,
            ));
            $u->uploadFile(new \AlibabaCloud\Oss\V2\Models\PutObjectRequest(
                $bucketName,
                $key,
            ), $filename);
            $getObj = $eclient->getObject(new Oss\Models\GetObjectRequest(
                $bucketName,
                $key,
            ));
            $this->assertEquals(base64_encode(md5(file_get_contents($filename), true)), base64_encode(md5($getObj->body, true)));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $u = $eclient->newUploader(array(
                'part_size' => $partSize,
                'total_size' => $length,
            ));
            $u->uploadFrom(new \AlibabaCloud\Oss\V2\Models\PutObjectRequest(
                $bucketName,
                $key,
            ),
                new LazyOpenStream($filename, 'rb'),
                [
                    'part_size' => $partSize,
                ]);
            $getObj = $eclient->getObject(new Oss\Models\GetObjectRequest(
                $bucketName,
                $key,
            ));
            $this->assertEquals(base64_encode(md5(file_get_contents($filename), true)), base64_encode(md5($getObj->body, true)));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        unlink($filename);
    }

    public function testDownloader()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . '-multipart-uploader';
        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
        );
        $length = 500 * 1024;
        $partSize = 200 * 1024;
        $eclient = new Oss\EncryptionClient($client, $masterCipher);
        $downFileName = self::getTempFileName() . "-download.tmp";
        $bigFileName = self::getTempFileName() . "-bigfile.tmp";
        $this->generateFile($bigFileName, $length);
        $content = \file_get_contents($bigFileName);
        $u = $eclient->newUploader();
        $u->uploadFile(new \AlibabaCloud\Oss\V2\Models\PutObjectRequest(
            $bucketName,
            $key,
        ), $bigFileName);

        try {
            $d = $eclient->newDownloader();
            $d->downloadFile(new Oss\Models\GetObjectRequest($bucketName, $key), $downFileName);
            $this->assertEquals(md5_file($bigFileName), md5_file($downFileName));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $d = $eclient->newDownloader(
                [
                    'part_size' => $partSize,
                    'parallel_num' => 1,
                ]
            );
            $d->downloadFile(new Oss\Models\GetObjectRequest($bucketName, $key), $downFileName);
            $this->assertEquals(md5_file($bigFileName), md5_file($downFileName));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $d = $eclient->newDownloader(
                [
                    'part_size' => $partSize,
                    'parallel_num' => 2,
                ]
            );
            $d->downloadFile(new Oss\Models\GetObjectRequest($bucketName, $key), $downFileName);
            $this->assertEquals(md5_file($bigFileName), md5_file($downFileName));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $d = $eclient->newDownloader(
                [
                    'part_size' => $partSize,
                    'parallel_num' => 1,
                ]
            );
            $stream = new BufferStream(1 * 1024 * 1024);
            $result = $d->downloadTo(
                new Oss\Models\GetObjectRequest(
                    $bucketName,
                    $key
                ),
                $stream,
            );
            $gotContent = $stream->getContents();
            $this->assertEquals($length, $result->written);
            $this->assertEquals($length, strlen($gotContent));
            $this->assertEquals($content, $gotContent);
            $this->assertEquals(md5_file($bigFileName), md5_file($downFileName));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testEncryptionCompatibility()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = 'example.jpg';

        $filename = self::getDataPath() . DIRECTORY_SEPARATOR . 'example.jpg';
        $filesize = filesize($filename);
        $content = file_get_contents($filename);

        $encFilename = self::getDataPath() . DIRECTORY_SEPARATOR . 'enc-example.jpg';
        $encFilesize = filesize($encFilename);
        $encContent = file_get_contents($encFilename);

        $this->assertEquals($filesize, $encFilesize);
        $this->assertNotEquals($content, $encContent);

        // decrypt only
        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            null,
            self::RSA_PRIVATE_KEY,
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put test encrypted data
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->body = Oss\Utils::streamFor($encContent);
        $putObjRequest->metadata = [
            "client-side-encryption-key" => "nyXOp7delQ/MQLjKQMhHLaT0w7u2yQoDLkSnK8MFg/MwYdh4na4/LS8LLbLcM18m8I/ObWUHU775I50sJCpdv+f4e0jLeVRRiDFWe+uo7Puc9j4xHj8YB3QlcIOFQiTxHIB6q+C+RA6lGwqqYVa+n3aV5uWhygyv1MWmESurppg=",
            "client-side-encryption-start" => "De/S3T8wFjx7QPxAAFl7h7TeI2EsZlfCwox4WhLGng5DK2vNXxULmulMUUpYkdc9umqmDilgSy5Z3Foafw+v4JJThfw68T/9G2gxZLrQTbAlvFPFfPM9Ehk6cY4+8WpY32uN8w5vrHyoSZGr343NxCUGIp6fQ9sSuOLMoJg7hNw=",
            "client-side-encryption-cek-alg" => "AES/CTR/NoPadding",
            "client-side-encryption-wrap-alg" => "RSA/NONE/PKCS1Padding",
        ];
        $result = $client->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // use non-encryption client to get object
        $result = $client->getObject(new Oss\Models\GetObjectRequest($bucketName, $key));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals($encContent, $result->body->getContents());

        // use encryption client to get object
        $result = $eclient->getObject(new Oss\Models\GetObjectRequest($bucketName, $key));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals($content, $result->body->getContents());
        $this->assertInstanceOf(GuzzleHttp\Psr7\Stream::class, $result->body);

        // use encryption client to get object into filepath
        $filenameSave = self::getTempFileName();
        $result = $eclient->getObject(
            new Oss\Models\GetObjectRequest($bucketName, $key),
            [
                'request_options' =>
                    ['sink' => $filenameSave]
            ]
        );
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertFileEquals($filename, $filenameSave);
        $this->assertNull($result->body);
    }

    public function testGetObjectInvalidArgument()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();
        $filesize = 25 * 1024 + 123;
        $filename = self::getTempFileName();
        $this->generateFile($filename, $filesize);
        $content = file_get_contents($filename);

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);

        // put object
        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $key,
        );
        $putObjRequest->contentMd5 = base64_encode(md5_file($filename, true));
        $putObjRequest->contentLength = $filesize;
        $putObjRequest->body = Oss\Utils::streamFor($content);
        $result = $eclient->putObject($putObjRequest);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));

        // get object
        $result = $eclient->getObject(new Oss\Models\GetObjectRequest(
            $bucketName,
            $key
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(True, count($result->headers) > 0);
        $this->assertEquals(24, strlen($result->requestId));
        $this->assertEquals($content, $result->body->getContents());
        $this->assertEquals($filesize, $result->contentLength);
        $this->assertEquals(strval($filesize), $result->headers['Content-Length']);

        try {
            $offset = 0;
            $request = new Oss\Models\GetObjectRequest(
                $bucketName,
                $key
            );
            $request->rangeHeader = "2bytes=$offset-";
            $result = $eclient->getObject($request);
            $this->assertTrue(false, 'should not here');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('request.rangeHeader is invalid, got', $e->getMessage());
        }

    }

    public function testInitiateMultipartUploadInvalidArgument()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName();

        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);
        try {
            $result = $eclient->initiateMultipartUpload(new Oss\Models\InitiateMultipartUploadRequest(
                $bucketName,
                $key
            ));
            $this->assertTrue(false, 'should not here');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('request.csePartSize is invalid.', $e->getMessage());
        }

        try {
            $request = new Oss\Models\InitiateMultipartUploadRequest(
                $bucketName,
                $key
            );
            $request->csePartSize = 1;
            $result = $eclient->initiateMultipartUpload($request);
            $this->assertTrue(false, 'should not here');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('request.cseDataSize is invalid.', $e->getMessage());
        }

        try {
            $request = new Oss\Models\InitiateMultipartUploadRequest(
                $bucketName,
                $key
            );
            $request->csePartSize = 1;
            $request->cseDataSize = 200;
            $result = $eclient->initiateMultipartUpload($request);
            $this->assertTrue(false, 'should not here');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('request.csePartSize must be aligned to', $e->getMessage());
        }

    }

    public function testUploadPartInvalidArgument()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = self::randomObjectName() . '-multipart';
        $masterCipher = new Oss\Crypto\MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );

        $eclient = new Oss\EncryptionClient($client, $masterCipher);
        $partSize = 200 * 1024;
        $length = 500 * 1024;
        $bigFileName = self::getTempFileName() . "-bigfile.tmp";
        $this->generateFile($bigFileName, $length);
        try {
            $file = fopen($bigFileName, 'r');
            $parts = array();
            if ($file) {
                $i = 1;
                while (!feof($file)) {
                    $chunk = fread($file, $partSize);
                    $partRequest = new Oss\Models\UploadPartRequest(
                        $bucketName,
                        $key,
                        $i,
                        '123',
                        strlen($chunk),
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    );
                    $partResult = $eclient->uploadPart($partRequest);
                    $part = new Oss\Models\UploadPart(
                        $i,
                        $partResult->etag,
                    );
                    array_push($parts, $part);
                    $i++;
                }
                fclose($file);
            }
            $this->assertTrue(false, "should not here");
        } catch (\Throwable $e) {
            $this->assertStringContainsString('request.encryptionMultiPart is null.', $e->getMessage());
        }

        try {
            $file = fopen($bigFileName, 'r');
            $parts = array();
            if ($file) {
                $i = 1;
                while (!feof($file)) {
                    $chunk = fread($file, $partSize);
                    $partRequest = new Oss\Models\UploadPartRequest(
                        $bucketName,
                        $key,
                        $i,
                        '123',
                        strlen($chunk),
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    );
                    $partRequest->encryptionMultipartContext = new Oss\Models\EncryptionMultipartContext();
                    $partResult = $eclient->uploadPart($partRequest);
                    $part = new Oss\Models\UploadPart(
                        $i,
                        $partResult->etag,
                    );
                    array_push($parts, $part);
                    $i++;
                }
                fclose($file);
            }
            $this->assertTrue(false, "should not here");
        } catch (\Throwable $e) {
            $this->assertStringContainsString('request.encryptionMultiPart is invalid.', $e->getMessage());
        }

        try {
            $file = fopen($bigFileName, 'r');
            $builder = new Oss\Crypto\AesCtrCipherBuilder($masterCipher);
            $cc = $builder->fromCipherData();
            $cc->getCipherData();
            $parts = array();
            if ($file) {
                $i = 1;
                while (!feof($file)) {
                    $chunk = fread($file, $partSize);
                    $partRequest = new Oss\Models\UploadPartRequest(
                        $bucketName,
                        $key,
                        $i,
                        '123',
                        strlen($chunk),
                        null,
                        null,
                        null,
                        Oss\Utils::streamFor($chunk)
                    );
                    $partRequest->encryptionMultipartContext = new Oss\Models\EncryptionMultipartContext($cc, 100, 2);
                    $partResult = $eclient->uploadPart($partRequest);
                    $part = new Oss\Models\UploadPart(
                        $i,
                        $partResult->etag,
                    );
                    array_push($parts, $part);
                    $i++;
                }
                fclose($file);
            }
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('request.encryptionMultiPart.partSize must be aligned to', $e->getMessage());
        }
    }
}
