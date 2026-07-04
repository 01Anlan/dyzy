<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2\Client;
use AlibabaCloud\Oss\V2\Models\CompleteMultipartUpload;
use AlibabaCloud\Oss\V2\Transform\Functions;
use AlibabaCloud\Oss\V2\Utils;
use DateInterval;
use GuzzleHttp;
use AlibabaCloud\Oss\V2 as Oss;


class ClientPresignerTest extends TestIntegration
{
    public function testGetObject()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $key = 'key-123+-/123%/345';
        $content = 'hello world';
        $request = new Oss\Models\PutObjectRequest($bucketName, $key);
        $request->body = Oss\Utils::streamFor($content);
        $result = $client->putObject($request);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);

        $request = new Oss\Models\GetObjectRequest($bucketName, $key);
        $result = $client->presign($request);
        $uri = GuzzleHttp\Psr7\Utils::uriFor($result->url);
        $this->assertEquals('/key-123%2B-/123%25/345', $uri->getPath());
        $query = GuzzleHttp\Psr7\Query::parse($uri->getQuery());
        $this->assertArrayHasKey('x-oss-credential', $query);
        $this->assertArrayHasKey('x-oss-date', $query);
        $this->assertArrayHasKey('x-oss-expires', $query);
        $this->assertEquals('900', $query['x-oss-expires']);
        $this->assertArrayHasKey('x-oss-signature', $query);
        $this->assertArrayHasKey('x-oss-signature-version', $query);
        $this->assertEquals('OSS4-HMAC-SHA256', $query['x-oss-signature-version']);

        $httpClient = new GuzzleHttp\Client();
        $response = $httpClient->get($result->url);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($content, $response->getBody()->getContents());
    }

    public function testPresign()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';
        $body = 'hi oss';
        $httpClient = new GuzzleHttp\Client();
        // PutObjRequest
        try {
            $request = new Oss\Models\PutObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url, ['body' => $body]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        // GetObjRequest
        try {
            $request = new Oss\Models\GetObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($body, $response->getBody()->getContents());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        // HeadObjRequest
        try {
            $request = new Oss\Models\HeadObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals(strlen($body), $response->getHeaderLine('content-length'));
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        // UploadPart
        $objectNameMultipart = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part';
        try {
            $request = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipart);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        $body = $response->getBody()->getContents();
        $xml = Utils::parseXml($body);
        $uploadId = Functions::tryToString($xml->UploadId);
        try {
            $partRequest = new Oss\Models\UploadPartRequest($bucketName, $objectNameMultipart);
            $partRequest->partNumber = 1;
            $partRequest->uploadId = $uploadId;
            $result = $client->presign($partRequest);
            $response = $httpClient->request($result->method, $result->url, ['body' => $body, ['headers' => $result->signedHeaders]]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        try {
            $request = new Oss\Models\CompleteMultipartUploadRequest($bucketName, $objectNameMultipart);
            $request->completeMultipartUpload = new CompleteMultipartUpload(
                [new Oss\Models\UploadPart(
                    $partRequest->partNumber, $response->getHeaderLine('ETag')
                )]
            );
            $request->uploadId = $uploadId;
            $result = $client->presign($request);
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload></CompleteMultipartUpload>');
            if (isset($request->completeMultipartUpload->parts)) {
                foreach ($request->completeMultipartUpload->parts as $part) {
                    $xmlPart = $xml->addChild('Part');
                    $xmlPart->addChild('PartNumber', strval($part->partNumber));
                    $xmlPart->addChild('ETag', $part->etag);
                }
            }
            $response = $httpClient->request($result->method, $result->url, ['body' => $xml->asXML(), 'headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $headRequest = new Oss\Models\HeadObjectRequest($bucketName, $objectNameMultipart);
            $headResult = $client->headObject($headRequest);
            $this->assertEquals(200, $headResult->statusCode);
            $this->assertEquals(strlen($body), $headResult->contentLength);
            $this->assertEquals('Multipart', $headResult->objectType);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        $objectNameMultipartCopy = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part-copy';

        try {
            $initRequest = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipartCopy);
            $initResult = $client->presign($initRequest);
            $response = $httpClient->request($initResult->method, $initResult->url, ['headers' => $initResult->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $body = $response->getBody()->getContents();
            $xml = Utils::parseXml($body);
            $uploadId = Functions::tryToString($xml->UploadId);

            $abortRequest = new Oss\Models\AbortMultipartUploadRequest($bucketName, $objectNameMultipartCopy, $uploadId);
            $result = $client->presign($abortRequest);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(204, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

    }

    public function testPresignExtra()
    {
        $bucketName = self::$bucketName;
        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';
        $body = 'hi oss';
        $httpClient = new GuzzleHttp\Client();
        $cfg = Oss\Config::loadDefault();
        $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
            self::$ACCESS_ID,
            self::$ACCESS_KEY
        ));
        $cfg->setRegion(self::$REGION);
        $cfg->setEndpoint(self::$ENDPOINT);
        $cfg->setSignatureVersion('v1');
        $cfg->setAdditionalHeaders(['email', 'name']);
        $clientV1 = new Client($cfg);
        $cfg->setSignatureVersion('v4');
        $clientV4 = new Client($cfg);

        $putObjRequest = new Oss\Models\PutObjectRequest(
            $bucketName,
            $objectName,
        );
        $putObjRequest->body = Utils::streamFor($body);
        $putObjResult = $clientV1->putObject($putObjRequest);
        $this->assertEquals(200, $putObjResult->statusCode);
        $this->assertEquals('OK', $putObjResult->status);


        // GetObjRequest
        try {
            $request = new Oss\Models\GetObjectRequest($bucketName, $objectName, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, ['headers' => ['email' => 'demo@aliyun.com']]);
            $options['expiration'] = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1M'));
            $result = $clientV1->presign($request, $options);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($body, $response->getBody()->getContents());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $options = null;
            $request = new Oss\Models\GetObjectRequest($bucketName, $objectName, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, ['headers' => ['email' => 'demo@aliyun.com']]);
            $options['expiration'] = (new \DateTime('now', new \DateTimeZone('UTC')))->modify('-10 second');
            $result = $clientV1->presign($request, $options);
            $response = $httpClient->request($result->method, $result->url);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            //$this->assertEquals(403, $e->getMessage());
            $this->assertStringContainsString("403 Forbidden", $e->getMessage());
            $this->assertStringContainsString("Request has expired.", $e->getMessage());
            $this->assertInstanceOf(GuzzleHttp\Exception\ClientException::class, $e);
        }

        try {
            $options = null;
            $request = new Oss\Models\GetObjectRequest($bucketName, $objectName);
            $options['expiration'] = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT192H'));
            $result = $clientV4->presign($request, $options);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Expires should not be greater than 604800 (seven days)', $e->getMessage());
        }

        $options = null;
        $request = new Oss\Models\GetObjectRequest($bucketName, $objectName, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, ['headers' => ['email' => 'demo@aliyun.com']]);
        $options['expires'] = new DateInterval('PT1H');
        $result = $clientV4->presign($request, $options);
        $response = $httpClient->request($result->method, $result->url, ['headers' => ['email' => 'demo@aliyun.com']]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPresignWithStsToken()
    {
        $client = $this->getClientUseStsToken();
        $bucketName = self::$bucketName;

        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';
        $body = 'hi oss';
        $httpClient = new GuzzleHttp\Client();
        // PutObjRequest
        try {
            $request = new Oss\Models\PutObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url, ['body' => $body]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        // GetObjRequest
        try {
            $request = new Oss\Models\GetObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($body, $response->getBody()->getContents());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        // HeadObjRequest
        try {
            $request = new Oss\Models\HeadObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals(strlen($body), $response->getHeaderLine('content-length'));
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        // UploadPart
        $objectNameMultipart = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part';
        try {
            $request = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipart);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        $body = $response->getBody()->getContents();
        $xml = Utils::parseXml($body);
        $uploadId = Functions::tryToString($xml->UploadId);
        try {
            $partRequest = new Oss\Models\UploadPartRequest($bucketName, $objectNameMultipart);
            $partRequest->partNumber = 1;
            $partRequest->uploadId = $uploadId;
            $result = $client->presign($partRequest);

            $response = $httpClient->request($result->method, $result->url, ['body' => $body, ['headers' => $result->signedHeaders]]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $request = new Oss\Models\CompleteMultipartUploadRequest($bucketName, $objectNameMultipart);
            $request->completeMultipartUpload = new CompleteMultipartUpload(
                [new Oss\Models\UploadPart(
                    $partRequest->partNumber, $response->getHeaderLine('ETag')
                )]
            );
            $request->uploadId = $uploadId;
            $result = $client->presign($request);
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload></CompleteMultipartUpload>');
            if (isset($request->completeMultipartUpload->parts)) {
                foreach ($request->completeMultipartUpload->parts as $part) {
                    $xmlPart = $xml->addChild('Part');
                    $xmlPart->addChild('PartNumber', strval($part->partNumber));
                    $xmlPart->addChild('ETag', $part->etag);
                }
            }
            $response = $httpClient->request($result->method, $result->url, ['body' => $xml->asXML(), 'headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $headRequest = new Oss\Models\HeadObjectRequest($bucketName, $objectNameMultipart);
            $headResult = $client->headObject($headRequest);
            $this->assertEquals(200, $headResult->statusCode);
            $this->assertEquals(strlen($body), $headResult->contentLength);
            $this->assertEquals('Multipart', $headResult->objectType);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        $objectNameMultipartCopy = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part-copy';

        try {
            $initRequest = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipartCopy);
            $initResult = $client->presign($initRequest);
            $response = $httpClient->request($initResult->method, $initResult->url, ['headers' => $initResult->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $body = $response->getBody()->getContents();
            $xml = Utils::parseXml($body);
            $uploadId = Functions::tryToString($xml->UploadId);

            $abortRequest = new Oss\Models\AbortMultipartUploadRequest($bucketName, $objectNameMultipartCopy, $uploadId);
            $result = $client->presign($abortRequest);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(204, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

    }

    public function testPresignError()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        try {
            $request = new Oss\Models\ListObjectsRequest($bucketName);
            $result = $client->presign($request);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString("Invalid request type: AlibabaCloud\Oss\V2\Models\ListObjectsRequest", $e);
        }

        try {
            $result = $client->presign(null);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString("is not subclass of RequestModel, got NULL", $e);
        }
    }

    public function testPresignWithContentType()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object.zip';
        $body = 'hi oss';
        $httpClient = new GuzzleHttp\Client();
        // PutObjRequest
        try {
            $request = new Oss\Models\PutObjectRequest($bucketName, $objectName);
            $result = $client->presign($request);
            $response = $httpClient->request($result->method, $result->url, ['body' => $body]);
            $this->assertCount(0, $result->signedHeaders);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        // UploadPart
        $objectNameMultipart = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part.zip';
        try {
            $request = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipart);
            $result = $client->presign($request);
            $this->assertCount(1, $result->signedHeaders);
            $this->assertArrayHasKey('content-md5', $result->signedHeaders);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        $body = $response->getBody()->getContents();
        $xml = Utils::parseXml($body);
        $uploadId = Functions::tryToString($xml->UploadId);
        try {
            $partRequest = new Oss\Models\UploadPartRequest($bucketName, $objectNameMultipart);
            $partRequest->partNumber = 1;
            $partRequest->uploadId = $uploadId;
            $result = $client->presign($partRequest);
            $response = $httpClient->request($result->method, $result->url, ['body' => $body, ['headers' => $result->signedHeaders]]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        try {
            $request = new Oss\Models\CompleteMultipartUploadRequest($bucketName, $objectNameMultipart);
            $request->completeMultipartUpload = new CompleteMultipartUpload(
                [new Oss\Models\UploadPart(
                    $partRequest->partNumber, $response->getHeaderLine('ETag')
                )]
            );
            $request->uploadId = $uploadId;
            $result = $client->presign($request);
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload></CompleteMultipartUpload>');
            if (isset($request->completeMultipartUpload->parts)) {
                foreach ($request->completeMultipartUpload->parts as $part) {
                    $xmlPart = $xml->addChild('Part');
                    $xmlPart->addChild('PartNumber', strval($part->partNumber));
                    $xmlPart->addChild('ETag', $part->etag);
                }
            }
            $response = $httpClient->request($result->method, $result->url, ['body' => $xml->asXML(), 'headers' => $result->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $headRequest = new Oss\Models\HeadObjectRequest($bucketName, $objectNameMultipart);
            $headResult = $client->headObject($headRequest);
            $this->assertEquals(200, $headResult->statusCode);
            $this->assertEquals(strlen($body), $headResult->contentLength);
            $this->assertEquals('Multipart', $headResult->objectType);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        $objectNameMultipartCopy = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-multi-part-copy';

        try {
            $initRequest = new Oss\Models\InitiateMultipartUploadRequest($bucketName, $objectNameMultipartCopy);
            $initResult = $client->presign($initRequest);
            $response = $httpClient->request($initResult->method, $initResult->url, ['headers' => $initResult->signedHeaders]);
            $this->assertEquals(200, $response->getStatusCode());
            $body = $response->getBody()->getContents();
            $xml = Utils::parseXml($body);
            $uploadId = Functions::tryToString($xml->UploadId);

            $abortRequest = new Oss\Models\AbortMultipartUploadRequest($bucketName, $objectNameMultipartCopy, $uploadId);
            $result = $client->presign($abortRequest);
            $response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
            $this->assertEquals(204, $response->getStatusCode());
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }
    }

    public function testPresignWithAnonymous()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName.'-pub';
        $objectName = self::$OBJECTNAME_PREFIX . self::randomLowStr() . '-put-object';

        $client->putBucket(new Oss\Models\PutBucketRequest($bucketName));
        $client->putBucketPublicAccessBlock(new Oss\Models\PutBucketPublicAccessBlockRequest(
            $bucketName,
            new Oss\Models\PublicAccessBlockConfiguration(
                false
            )
        ));

        $content = 'hi oss';
        $putObjectRequest = new Oss\Models\PutObjectRequest(
            $bucketName, $objectName
        );
        $putObjectRequest->acl = 'public-read';
        $putObjectRequest->body = Utils::streamFor($content);
        $client->putObject($putObjectRequest);

        $request = new Oss\Models\GetObjectRequest($bucketName, $objectName);
        $options['credentials_provider'] = new Oss\Credentials\AnonymousCredentialsProvider();
        $result = $client->presign($request, $options);
        $this->assertNotEmpty($result->url);
        $this->assertNotEmpty($result->method);
        $this->assertStringNotContainsString('?', $result->url);
        $httpClient = new GuzzleHttp\Client();
        $response = $httpClient->get($result->url);
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getBody()->getContents(), $content);
    }
}
