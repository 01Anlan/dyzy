<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Models\RestoreRequest;
use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\Transform\ObjectBasic;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Exception;
use AlibabaCloud\Oss\V2\Utils;

class ObjectBasicTest extends \PHPUnit\Framework\TestCase
{
    public function testFromDeleteMultipleObjects()
    {
        // miss required field 
        try {
            $request = new Models\DeleteMultipleObjectsRequest();
            $input = ObjectBasic::fromDeleteMultipleObjects($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteMultipleObjectsRequest('bucket-123');
            $input = ObjectBasic::fromDeleteMultipleObjects($request);
        } catch (\InvalidArgumentException $e) {
             $this->assertStringContainsString("missing required field, objects", (string)$e);
        }

        try {
            $request = new Models\DeleteMultipleObjectsRequest('bucket-123');
            $request->objects = [
                new Models\DeleteObject('key1'),
                new Models\DeleteObject('key2', 'version-id-2'),
            ];
            $request->delete = new Models\Delete([
                new Models\ObjectIdentifier('key1'),
                new Models\ObjectIdentifier('key2', 'version-id-2'),
            ], true);
            $input = ObjectBasic::fromDeleteMultipleObjects($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("InvalidArgumentException: The objects and delete parameters cannot be set simultaneously", (string)$e);
        }

        try {
            $request = new Models\DeleteMultipleObjectsRequest('bucket-123');
            $request->delete = new Models\Delete();
            $request->delete->quiet = true;
            $input = ObjectBasic::fromDeleteMultipleObjects($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, delete.objects", (string)$e);
        }


        // bucket only
        $request = new Models\DeleteMultipleObjectsRequest('bucket-123', []);
        $input = ObjectBasic::fromDeleteMultipleObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('D0iFyCSGo62Kd/zfca7aPg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('url', $input->getParameters()['encoding-type']);

        // no quiet
        $request = new Models\DeleteMultipleObjectsRequest(
            'bucket-123',
            [
                new Models\DeleteObject('key1'),
                new Models\DeleteObject('key2', 'version-id-2'),
            ],
            'url1',
            null,
            'request-payer'
        );
        $input = ObjectBasic::fromDeleteMultipleObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('CNLPZjtD0MZNPvjKGf3yBg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('request-payer', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('url1', $input->getParameters()['encoding-type']);

        $str = $input->getBody()->getContents();
        $this->assertStringContainsString('<Delete>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(2, $xml->count());
        $this->assertEquals(false, isset($xml->Quiet));
        $this->assertEquals(2, $xml->Object->count());
        $this->assertEquals('key1', $xml->Object[0]->Key);
        $this->assertEquals(false, isset($xml->Object[0]->VersionId));
        $this->assertEquals('key2', $xml->Object[1]->Key);
        $this->assertEquals('version-id-2', $xml->Object[1]->VersionId);


        // all settings
        $xmlStr = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><Delete><Quiet>true</Quiet><Object><Key>key1</Key></Object><Object><Key>key2</Key><VersionId>version-id-2</VersionId></Object></Delete>
BBB;

        $request = new Models\DeleteMultipleObjectsRequest(
            'bucket-123',
            [
                new Models\DeleteObject('key1'),
                new Models\DeleteObject('key2', 'version-id-2'),
            ],
            'url1',
            true,
            'request-payer'
        );
        $input = ObjectBasic::fromDeleteMultipleObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('BesWzihB4UGzEXQLrXWg0w==', $input->getHeaders()['content-md5']);
        $this->assertEquals('request-payer', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('url1', $input->getParameters()['encoding-type']);

        $str = $input->getBody()->getContents();
        $this->assertEquals($xmlStr, $this->cleanXml($str));
        $this->assertStringContainsString('<Delete>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(3, $xml->count());
        $this->assertEquals('true', $xml->Quiet);
        $this->assertEquals(2, $xml->Object->count());
        $this->assertEquals('key1', $xml->Object[0]->Key);
        $this->assertEquals(false, isset($xml->Object[0]->VersionId));
        $this->assertEquals('key2', $xml->Object[1]->Key);
        $this->assertEquals('version-id-2', $xml->Object[1]->VersionId);

        // extend header & parameters
        $request = new Models\DeleteMultipleObjectsRequest(
            'bucket-123',
            [],
            null,
            null,
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ],
            null,
        );
        $input = ObjectBasic::fromDeleteMultipleObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('D0iFyCSGo62Kd/zfca7aPg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);

        $request = new Models\DeleteMultipleObjectsRequest(
            'bucket-123',
        );
        $request->delete = new Models\Delete(
            [
                new Models\ObjectIdentifier('key1'),
                new Models\ObjectIdentifier('key2', 'version-id-2'),
            ], true
        );
        $input = ObjectBasic::fromDeleteMultipleObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('BesWzihB4UGzEXQLrXWg0w==', $input->getHeaders()['content-md5']);
        $this->assertEquals($xmlStr, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToDeleteMultipleObjects()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toDeleteMultipleObjects($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <DeleteResult>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <DeleteResult></DeleteResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = ObjectBasic::toDeleteMultipleObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->deletedObjects);
        $this->assertNull($result->encodingType);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
                <DeleteResult xmlns="http://doc.oss-cn-hangzhou.aliyuncs.com">
                    <Deleted>
                        <Key>multipart.data</Key>
                    </Deleted>
                    <Deleted>
                        <Key>test.jpg</Key>
                    </Deleted>
                    <Deleted>
                        <Key>demo.jpg</Key>
                    </Deleted>
                </DeleteResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = ObjectBasic::toDeleteMultipleObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(3, count($result->deletedObjects));
        $this->assertEquals('multipart.data', $result->deletedObjects[0]->key);
        $this->assertNull($result->deletedObjects[0]->versionId);
        $this->assertNull($result->deletedObjects[0]->deleteMarker);
        $this->assertNull($result->deletedObjects[0]->deleteMarkerVersionId);

        $this->assertEquals('test.jpg', $result->deletedObjects[1]->key);
        $this->assertEquals('demo.jpg', $result->deletedObjects[2]->key);

        // encodingType
        $str = '<?xml version="1.0" encoding="UTF-8"?>
                <DeleteResult>
                    <EncodingType>url</EncodingType>
                    <Deleted>
                        <Key>123%2Fmultipart.data</Key>
                    </Deleted>
                    <Deleted>
                        <Key>135%2Ftest.jpg</Key>
                    </Deleted>
                    <Deleted>
                        <Key>136%2Fdemo.jpg</Key>
                    </Deleted>
                </DeleteResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = ObjectBasic::toDeleteMultipleObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(3, count($result->deletedObjects));
        $this->assertEquals('123/multipart.data', $result->deletedObjects[0]->key);
        $this->assertNull($result->deletedObjects[0]->versionId);
        $this->assertNull($result->deletedObjects[0]->deleteMarker);
        $this->assertNull($result->deletedObjects[0]->deleteMarkerVersionId);

        $this->assertEquals('135/test.jpg', $result->deletedObjects[1]->key);
        $this->assertEquals('136/demo.jpg', $result->deletedObjects[2]->key);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
                <DeleteResult>
                    <Deleted>
                        <Key>multipart.data</Key>
                        <VersionId>CAEQNRiBgIDyz.6C0BYiIGQ2NWEwNmVhNTA3ZTQ3MzM5ODliYjM1ZTdjYjA4****</VersionId>
                    </Deleted>
                    <Deleted>
                        <Key>demo.jpg</Key>
                        <VersionId>CAEQNRiBgICEoPiC0BYiIGMxZWJmYmMzYjE0OTQ0ZmZhYjgzNzkzYjc2NjZk****</VersionId>
                        <DeleteMarker>true</DeleteMarker>
                        <DeleteMarkerVersionId>THUQNRiBgICEoPiC0BYiIGMxZWJmYmMzYjE0OTQ0ZmZhYjgzNzkzYjc2NjZk****</DeleteMarkerVersionId>
                    </Deleted>
                </DeleteResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = ObjectBasic::toDeleteMultipleObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(2, count($result->deletedObjects));
        $this->assertEquals('multipart.data', $result->deletedObjects[0]->key);
        $this->assertEquals('CAEQNRiBgIDyz.6C0BYiIGQ2NWEwNmVhNTA3ZTQ3MzM5ODliYjM1ZTdjYjA4****', $result->deletedObjects[0]->versionId);
        $this->assertNull($result->deletedObjects[0]->deleteMarker);
        $this->assertNull($result->deletedObjects[0]->deleteMarkerVersionId);

        $this->assertEquals('demo.jpg', $result->deletedObjects[1]->key);
        $this->assertEquals('CAEQNRiBgICEoPiC0BYiIGMxZWJmYmMzYjE0OTQ0ZmZhYjgzNzkzYjc2NjZk****', $result->deletedObjects[1]->versionId);
        $this->assertEquals(true, $result->deletedObjects[1]->deleteMarker);
        $this->assertEquals('THUQNRiBgICEoPiC0BYiIGMxZWJmYmMzYjE0OTQ0ZmZhYjgzNzkzYjc2NjZk****', $result->deletedObjects[1]->deleteMarkerVersionId);
    }

    public function testFromPutObject()
    {
        // miss required field
        try {
            $request = new Models\PutObjectRequest();
            $input = ObjectBasic::fromPutObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutObjectRequest('bucket-123');
            $input = ObjectBasic::fromPutObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $request->cacheControl = 'no-cache';
        $request->contentDisposition = 'attachment';
        $request->contentEncoding = 'utf-8';
        $request->contentMd5 = 'eB5eJF1ptWaXm4bijSPyxw==';
        $request->contentLength = 100;
        $request->expires = '2022-10-12T00:00:00.000Z';
        $request->forbidOverwrite = true;
        $request->serverSideEncryption = 'AES256';
        $request->acl = Models\ObjectACLType::PRIVATE;
        $request->storageClass = Models\StorageClassType::STANDARD;
        $request->metadata = array(
            "location" => "demo",
            "user" => "walker",
        );
        $request->tagging = 'TagA=A&TagB=B';
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('utf-8', $input->getHeaders()['content-encoding']);
        $this->assertEquals('100', $input->getHeaders()['content-length']);
        $this->assertEquals('eB5eJF1ptWaXm4bijSPyxw==', $input->getHeaders()['content-md5']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-user']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-location']);
        $this->assertEquals('AES256', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
        $this->assertEquals('TagA=A&TagB=B', $input->getHeaders()['x-oss-tagging']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $request->cacheControl = 'no-cache';
        $request->contentDisposition = 'attachment';
        $request->contentEncoding = 'utf-8';
        $request->contentMd5 = 'eB5eJF1ptWaXm4bijSPyxw==';
        $request->contentLength = 1000;
        $request->expires = '2022-10-12T00:00:00.000Z';
        $request->forbidOverwrite = true;
        $request->serverSideEncryption = 'KMS';
        $request->serverSideDataEncryption = 'SM4';
        $request->serverSideEncryptionKeyId = '9468da86-3509-4f8d-a61e-6eab1eac****';
        $request->acl = Models\ObjectACLType::PRIVATE;
        $request->storageClass = Models\StorageClassType::STANDARD;
        $request->metadata = array(
            "name" => "walker",
            "email" => "demo@aliyun.com",
        );
        $request->tagging = 'TagA=B&TagC=D';
        $body = random_bytes(1000);
        $request->body = Utils::streamFor($body);
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('utf-8', $input->getHeaders()['content-encoding']);
        $this->assertEquals('1000', $input->getHeaders()['content-length']);
        $this->assertEquals('eB5eJF1ptWaXm4bijSPyxw==', $input->getHeaders()['content-md5']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
        $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
        $this->assertEquals('TagA=B&TagC=D', $input->getHeaders()['x-oss-tagging']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $callback = base64_encode('{
                "callbackUrl":"www.aliyuncs.com", 
                "callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}",
                "callbackBodyType":"application/x-www-form-urlencoded",
            }');
        $callbackVar = base64_encode('{"x:a":"a", "x:b":"b"}');
        $request->callback = $callback;
        $request->callbackVar = $callbackVar;
        $request->body = Utils::streamFor($body);
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals($callback, $input->getHeaders()['x-oss-callback']);
        $this->assertEquals($callbackVar, $input->getHeaders()['x-oss-callback-var']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);

        $request = new Models\PutObjectRequest('bucket-123', 'key-123');
        $request->body = Utils::streamFor($body);
        $request->trafficLimit = 102400;
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals('102400', $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);

        $request = new Models\PutObjectRequest('bucket-123', 'key-123', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, Models\ObjectACLType::PUBLIC_READ);
        $request->body = Utils::streamFor($body);
        $request->trafficLimit = 102400;
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals('102400', $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);
        $this->assertEquals(true, $input->getHeaders()['x-oss-object-acl']);

        $request = new Models\PutObjectRequest('bucket-123', 'key-123', Models\ObjectACLType::PRIVATE, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, Models\ObjectACLType::PUBLIC_READ);
        $request->body = Utils::streamFor($body);
        $request->trafficLimit = 102400;
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals('102400', $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals([], $input->getParameters());
        $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);
        $this->assertEquals(true, $input->getHeaders()['x-oss-object-acl']);
    }

    public function testToPutObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toPutObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"7265F4D211B56873A381D321F586****"', 'Content-MD5' => '1B2M2Y8AsgTpgAmY7Ph****', 'x-oss-hash-crc64ecma' => '316181249502703*****'],
                null,
                new OperationInput('PutObject', 'PUT', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"7265F4D211B56873A381D321F586****"', 'Content-MD5' => '1B2M2Y8AsgTpgAmY7Ph****', 'x-oss-hash-crc64ecma' => '316181249502703*****'],
                ),
            );
            $result = ObjectBasic::toPutObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(4, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"7265F4D211B56873A381D321F586****"', $result->etag);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7Ph****', $result->contentMd5);
            $this->assertEquals('316181249502703*****', $result->hashCrc64);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $body = '{"filename":"object.txt","size":"100","mimeType":""}';
            $callback = base64_encode('{
                "callbackUrl":"www.aliyuncs.com", 
                "callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}",
                "callbackBodyType":"application/x-www-form-urlencoded",
            }');
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"7265F4D211B56873A381D321F586****"', 'Content-MD5' => '1B2M2Y8AsgTpgAmY7Ph****', 'x-oss-hash-crc64ecma' => '316181249502703*****', 'x-oss-version-id' => 'CAEQNhiBgMDJgZCA0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY0****'],
                Utils::streamFor($body),
                new OperationInput('PutObject', 'PUT', ['x-oss-callback' => $callback]),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"7265F4D211B56873A381D321F586****"', 'Content-MD5' => '1B2M2Y8AsgTpgAmY7Ph****', 'x-oss-hash-crc64ecma' => '316181249502703*****', 'x-oss-version-id' => 'CAEQNhiBgMDJgZCA0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY0****'],
                ),
            );
            $result = ObjectBasic::toPutObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(5, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"7265F4D211B56873A381D321F586****"', $result->etag);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7Ph****', $result->contentMd5);
            $this->assertEquals('316181249502703*****', $result->hashCrc64);
            $this->assertEquals('CAEQNhiBgMDJgZCA0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY0****', $result->versionId);
            $this->assertEquals($body, json_encode($result->callbackResult));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromGetObject()
    {
        // miss required field
        try {
            $request = new Models\GetObjectRequest();
            $input = ObjectBasic::fromGetObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetObjectRequest('bucket-123');
            $input = ObjectBasic::fromGetObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\GetObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromGetObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $request = new Models\GetObjectRequest('bucket-123', 'key-123');
        $request->trafficLimit = 102400;
        $request->ifMatch = '"D41D8CD98F00B204E9800998ECF8****"';
        $request->ifNoneMatch = '"D41D8CD98F00B204E9800998ECF9****"';
        $request->ifModifiedSince = 'Fri, 13 Nov 2023 14:47:53 GMT';
        $request->ifUnmodifiedSince = 'Fri, 13 Nov 2015 14:47:53 GMT';
        $request->rangeHeader = 'bytes 0~9/44';
        $request->responseCacheControl = 'gzip';
        $request->responseContentDisposition = 'attachment; filename=testing.txt';
        $request->responseContentEncoding = 'utf-8';
        $request->responseContentLanguage = '中文';
        $request->responseContentType = 'text';
        $request->responseExpires = 'Fri, 24 Feb 2012 17:00:00 GMT';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****';
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromGetObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $input->getHeaders()['if-match']);
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF9****"', $input->getHeaders()['if-none-match']);
        $this->assertEquals('Fri, 13 Nov 2023 14:47:53 GMT', $input->getHeaders()['if-modified-since']);
        $this->assertEquals('Fri, 13 Nov 2015 14:47:53 GMT', $input->getHeaders()['if-unmodified-since']);
        $this->assertEquals('bytes 0~9/44', $input->getHeaders()['range']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('102400', $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('gzip', $input->getParameters()['response-cache-control']);
        $this->assertEquals('attachment; filename=testing.txt', $input->getParameters()['response-content-disposition']);
        $this->assertEquals('utf-8', $input->getParameters()['response-content-encoding']);
        $this->assertEquals('中文', $input->getParameters()['response-content-language']);
        $this->assertEquals('Fri, 24 Feb 2012 17:00:00 GMT', $input->getParameters()['response-expires']);
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $input->getParameters()['versionId']);
        $this->assertEquals([], $input->getOpMetadata());
    }

    public function testToGetObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toGetObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        $length = 1000;
        $body = random_bytes($length);
        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"D41D8CD98F00B204E9800998ECF8****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length],
                Utils::streamFor($body),
                new OperationInput('GetObject', 'GET', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"D41D8CD98F00B204E9800998ECF8****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length],
                    $body
                ),
            );
            $result = ObjectBasic::toGetObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(6, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2012 06:07:48 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals('image/jpg', $result->contentType);
            $this->assertEquals($length, $result->contentLength);
            $this->assertEquals('Normal', $result->objectType);
            $this->assertEquals($body, $result->body->getContents());
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        $length2 = 800;
        $body2 = random_bytes($length2);
        try {
            $output = new OperationOutput(
                'Partial Content',
                206,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length2, 'Accept-Ranges' => 'bytes', 'Content-Range' => 'bytes 100-900/34460'],
                Utils::streamFor($body2),
                new OperationInput('GetObject', 'GET', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length2, 'Accept-Ranges' => 'bytes', 'Content-Range' => 'bytes 100-900/34460'],
                    $body2
                ),
            );
            $result = ObjectBasic::toGetObject($output);
            $this->assertEquals('Partial Content', $result->status);
            $this->assertEquals(206, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(8, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2012 06:07:48 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals('image/jpg', $result->contentType);
            $this->assertEquals($length2, $result->contentLength);
            $this->assertEquals('Normal', $result->objectType);
            $this->assertEquals('bytes 100-900/34460', $result->contentRange);
            $this->assertEquals($body2, $result->body->getContents());
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'Accept-Ranges' => 'bytes', 'Content-disposition' => 'attachment; filename=testing.txt', 'Cache-control' => 'no-cache', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-server-side-encryption' => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-tagging-count' => '2', 'Content-MD5' => 'si4Nw3Cn9wZ/rPX3XX+j****', 'x-oss-hash-crc64ecma' => '870718044876840****', 'x-oss-meta-name' => 'demo', 'x-oss-meta-email' => 'demo@aliyun.com'],
            Utils::streamFor($body),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'Accept-Ranges' => 'bytes', 'Content-disposition' => 'attachment; filename=testing.txt', 'Cache-control' => 'no-cache', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-server-side-encryption' => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-tagging-count' => '2', 'Content-MD5' => 'si4Nw3Cn9wZ/rPX3XX+j****', 'x-oss-hash-crc64ecma' => '870718044876840****', 'x-oss-meta-name' => 'demo', 'x-oss-meta-email' => 'demo@aliyun.com'],
                $body
            ),
        );
        $result = ObjectBasic::toGetObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('image/jpg', $result->contentType);
        $this->assertEquals('no-cache', $result->cacheControl);
        $this->assertEquals('attachment; filename=testing.txt', $result->contentDisposition);
        $this->assertEquals($length, $result->contentLength);
        $this->assertEquals('Normal', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals('KMS', $result->serverSideEncryption);
        $this->assertEquals($body, $result->body->getContents());
        $this->assertEquals('SM4', $result->serverSideDataEncryption);
        $this->assertEquals('12f8711f-90df-4e0d-903d-ab972b0f****', $result->serverSideEncryptionKeyId);
        $this->assertEquals(2, $result->taggingCount);
        $this->assertEquals('demo', $result->metadata['name']);
        $this->assertEquals('demo@aliyun.com', $result->metadata['email']);
        $this->assertEquals('si4Nw3Cn9wZ/rPX3XX+j****', $result->contentMd5);
        $this->assertEquals('870718044876840****', $result->hashCrc64);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-restore' => 'ongoing-request="false", expiry-date="Sun, 16 Apr 2017 08:12:33 GMT"'],
            Utils::streamFor($body),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-restore' => 'ongoing-request="false", expiry-date="Sun, 16 Apr 2017 08:12:33 GMT"'],
                $body
            ),
        );
        $result = ObjectBasic::toGetObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('image/jpg', $result->contentType);
        $this->assertEquals($length, $result->contentLength);
        $this->assertEquals('Normal', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals($body, $result->body->getContents());
        $this->assertEquals('ongoing-request="false", expiry-date="Sun, 16 Apr 2017 08:12:33 GMT"', $result->restore);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-version-id' => 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', 'x-oss-delete-marker' => 'true'],
            Utils::streamFor($body),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-version-id' => 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', 'x-oss-delete-marker' => 'true'],
                $body
            ),
        );
        $result = ObjectBasic::toGetObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('image/jpg', $result->contentType);
        $this->assertEquals($length, $result->contentLength);
        $this->assertEquals('Normal', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals($body, $result->body->getContents());
        $this->assertTrue($result->deleteMarker);
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $result->versionId);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Appendable', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'text', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-next-append-position' => '1001'],
            Utils::streamFor($body),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Appendable', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'text', 'Content-Length' => $length, 'X-Oss-Storage-Class' => 'Standard', 'x-oss-next-append-position' => '1001'],
                $body
            ),
        );
        $result = ObjectBasic::toGetObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('text', $result->contentType);
        $this->assertEquals($length, $result->contentLength);
        $this->assertEquals('Appendable', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals($body, $result->body->getContents());
        $this->assertEquals(1001, $result->nextAppendPosition);
    }

    public function testFromCopyObject()
    {
        // miss required field
        try {
            $request = new Models\CopyObjectRequest();
            $input = ObjectBasic::fromCopyObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CopyObjectRequest('bucket-123');
            $input = ObjectBasic::fromCopyObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\CopyObjectRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromCopyObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, sourceKey", (string)$e);
        }

        $request = new Models\CopyObjectRequest('bucket-123', 'key-123');
        $request->sourceKey = 'src-key';
        $request->trafficLimit = 245760;
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromCopyObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('/bucket-123/src-key', $input->getHeaders()['x-oss-copy-source']);
        $this->assertEquals(245760, $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        $request = new Models\CopyObjectRequest('bucket-123', 'key-123');
        $request->sourceBucket = 'src-bucket';
        $request->sourceKey = 'src-key';
        $request->trafficLimit = 102400;
        $request->ifMatch = '"D41D8CD98F00B204E9800998ECF8****"';
        $request->ifNoneMatch = '"D41D8CD98F00B204E9800998ECF9****"';
        $request->ifModifiedSince = 'Fri, 13 Nov 2023 14:47:53 GMT';
        $request->ifUnmodifiedSince = 'Fri, 13 Nov 2015 14:47:53 GMT';
        $request->sourceVersionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****';
        $input = ObjectBasic::fromCopyObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $input->getHeaders()['x-oss-copy-source-if-match']);
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF9****"', $input->getHeaders()['x-oss-copy-source-if-none-match']);
        $this->assertEquals('Fri, 13 Nov 2023 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-modified-since']);
        $this->assertEquals('Fri, 13 Nov 2015 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-unmodified-since']);
        $this->assertEquals('/src-bucket/src-key?versionId=CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $input->getHeaders()['x-oss-copy-source']);
        $this->assertEquals([], $input->getOpMetadata());

        $request = new Models\CopyObjectRequest('bucket-123', 'key-123');
        $request->sourceKey = 'src-key';
        $request->trafficLimit = 102400;
        $request->ifMatch = '"D41D8CD98F00B204E9800998ECF8****"';
        $request->ifNoneMatch = '"D41D8CD98F00B204E9800998ECF9****"';
        $request->ifModifiedSince = 'Fri, 13 Nov 2023 14:47:53 GMT';
        $request->ifUnmodifiedSince = 'Fri, 13 Nov 2015 14:47:53 GMT';
        $request->sourceVersionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****';
        $request->forbidOverwrite = true;
        $request->serverSideEncryption = 'KMS';
        $request->serverSideDataEncryption = 'SM4';
        $request->serverSideEncryptionKeyId = '9468da86-3509-4f8d-a61e-6eab1eac****';
        $request->metadataDirective = 'REPLACE';
        $request->taggingDirective = 'Replace';
        $request->acl = Models\ObjectACLType::PRIVATE;
        $request->storageClass = Models\StorageClassType::STANDARD;
        $request->metadata = array(
            "name" => "walker",
            "email" => "demo@aliyun.com",
        );
        $request->tagging = 'TagA=B&TagC=D';
        $input = ObjectBasic::fromCopyObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $input->getHeaders()['x-oss-copy-source-if-match']);
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF9****"', $input->getHeaders()['x-oss-copy-source-if-none-match']);
        $this->assertEquals('Fri, 13 Nov 2023 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-modified-since']);
        $this->assertEquals('Fri, 13 Nov 2015 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-unmodified-since']);
        $this->assertEquals('/bucket-123/src-key?versionId=CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $input->getHeaders()['x-oss-copy-source']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
        $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('TagA=B&TagC=D', $input->getHeaders()['x-oss-tagging']);
        $this->assertEquals('Replace', $input->getHeaders()['x-oss-tagging-directive']);
        $this->assertEquals('REPLACE', $input->getHeaders()['x-oss-metadata-directive']);
        $this->assertEquals([], $input->getOpMetadata());

        $request = new Models\CopyObjectRequest('bucket-123', 'key-123', null, 'src-key', 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', '"D41D8CD98F00B204E9800998ECF8****"', '"D41D8CD98F00B204E9800998ECF9****"', 'Fri, 13 Nov 2023 14:47:53 GMT', 'Fri, 13 Nov 2015 14:47:53 GMT', null, Models\StorageClassType::STANDARD, array(
            "name" => "walker",
            "email" => "demo@aliyun.com",
        ), null, null, null, null, null, null, null, 'REPLACE', 'KMS', 'SM4', '9468da86-3509-4f8d-a61e-6eab1eac****', 'TagA=B&TagC=D', 'Replace', true, 102400, null, null, null, Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromCopyObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $input->getHeaders()['x-oss-copy-source-if-match']);
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF9****"', $input->getHeaders()['x-oss-copy-source-if-none-match']);
        $this->assertEquals('Fri, 13 Nov 2023 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-modified-since']);
        $this->assertEquals('Fri, 13 Nov 2015 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-unmodified-since']);
        $this->assertEquals('/bucket-123/src-key?versionId=CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $input->getHeaders()['x-oss-copy-source']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
        $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('TagA=B&TagC=D', $input->getHeaders()['x-oss-tagging']);
        $this->assertEquals('Replace', $input->getHeaders()['x-oss-tagging-directive']);
        $this->assertEquals('REPLACE', $input->getHeaders()['x-oss-metadata-directive']);
        $this->assertEquals([], $input->getOpMetadata());

        $request = new Models\CopyObjectRequest('bucket-123', 'key-123', null, 'src-key', 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', '"D41D8CD98F00B204E9800998ECF8****"', '"D41D8CD98F00B204E9800998ECF9****"', 'Fri, 13 Nov 2023 14:47:53 GMT', 'Fri, 13 Nov 2015 14:47:53 GMT', Models\ObjectACLType::PUBLIC_READ, Models\StorageClassType::STANDARD, array(
            "name" => "walker",
            "email" => "demo@aliyun.com",
        ), null, null, null, null, null, null, null, 'REPLACE', 'KMS', 'SM4', '9468da86-3509-4f8d-a61e-6eab1eac****', 'TagA=B&TagC=D', 'Replace', true, 102400, null, null, null, Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromCopyObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF8****"', $input->getHeaders()['x-oss-copy-source-if-match']);
        $this->assertEquals('"D41D8CD98F00B204E9800998ECF9****"', $input->getHeaders()['x-oss-copy-source-if-none-match']);
        $this->assertEquals('Fri, 13 Nov 2023 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-modified-since']);
        $this->assertEquals('Fri, 13 Nov 2015 14:47:53 GMT', $input->getHeaders()['x-oss-copy-source-if-unmodified-since']);
        $this->assertEquals('/bucket-123/src-key?versionId=CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY*****', $input->getHeaders()['x-oss-copy-source']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
        $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('TagA=B&TagC=D', $input->getHeaders()['x-oss-tagging']);
        $this->assertEquals('Replace', $input->getHeaders()['x-oss-tagging-directive']);
        $this->assertEquals('REPLACE', $input->getHeaders()['x-oss-metadata-directive']);
        $this->assertEquals([], $input->getOpMetadata());
    }

    public function testToCopyObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toCopyObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }
        $body = '<?xml version="1.0" encoding="UTF-8"?>
<CopyObjectResult>
  <ETag>"F2064A169EE92E9775EE5324D0B1****"</ETag>
  <LastModified>2018-02-24T09:41:56.000Z</LastModified>
</CopyObjectResult>';
        $length = strlen($body);
        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'x-oss-hash-crc64ecma' => '1275300285919610****'],
                Utils::streamFor($body),
                new OperationInput('CopyObject', 'PUT', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'image/jpg', 'Content-Length' => $length, 'x-oss-hash-crc64ecma' => '1275300285919610****'],
                    $body
                ),
            );
            $result = ObjectBasic::toCopyObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"F2064A169EE92E9775EE5324D0B1****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2018-02-24T09:41:56.000Z',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals('1275300285919610****', $result->hashCrc64);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'text', 'Content-Length' => $length, 'x-oss-hash-crc64ecma' => '1275300285919610****', "x-oss-server-side-encryption" => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-copy-source-version-id' => 'CAEQHxiBgICDvseg3hgiIGZmOGNjNWJiZDUzNjQxNDM4MWM2NDc1YjhkYTk3****', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****'],
                Utils::streamFor($body),
                new OperationInput('CopyObject', 'PUT', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'text', 'Content-Length' => $length, 'x-oss-hash-crc64ecma' => '1275300285919610****', "x-oss-server-side-encryption" => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-copy-source-version-id' => 'CAEQHxiBgICDvseg3hgiIGZmOGNjNWJiZDUzNjQxNDM4MWM2NDc1YjhkYTk3****', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****'],
                    $body
                ),
            );
            $result = ObjectBasic::toCopyObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"F2064A169EE92E9775EE5324D0B1****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2018-02-24T09:41:56.000Z',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals('1275300285919610****', $result->hashCrc64);
            $this->assertEquals('KMS', $result->serverSideEncryption);
            $this->assertEquals('SM4', $result->serverSideDataEncryption);
            $this->assertEquals('12f8711f-90df-4e0d-903d-ab972b0f****', $result->serverSideEncryptionKeyId);
            $this->assertEquals('CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', $result->versionId);
            $this->assertEquals('CAEQHxiBgICDvseg3hgiIGZmOGNjNWJiZDUzNjQxNDM4MWM2NDc1YjhkYTk3****', $result->sourceVersionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromAppendObject()
    {
        // miss required field
        try {
            $request = new Models\AppendObjectRequest();
            $input = ObjectBasic::fromAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\AppendObjectRequest('bucket-123');
            $input = ObjectBasic::fromAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\AppendObjectRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, position", (string)$e);
        }
        $body = 'hi oss';
        $request = new Models\AppendObjectRequest('bucket-123', 'key-123', 0);
        $request->trafficLimit = 245760;
        $request->requestPayer = 'requester';
        $request->body = Utils::streamFor($body);
        $input = ObjectBasic::fromAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($body, $input->getBody()->getContents());
        $this->assertEquals('0', $input->getParameters()['position']);
        $this->assertEquals(245760, $input->getHeaders()['x-oss-traffic-limit']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        $request = new Models\AppendObjectRequest('bucket-123', 'key-123', 0);
        $request->cacheControl = 'no-cache';
        $request->contentDisposition = 'attachment';
        $request->contentEncoding = 'gzip';
        $request->contentMd5 = 'eB5eJF1ptWaXm4bijSPyxw==';
        $request->expires = '2022-10-12T00:00:00.000Z';
        $request->forbidOverwrite = true;
        $request->serverSideEncryption = 'AES256';
        $request->acl = Models\ObjectACLType::PRIVATE;
        $request->storageClass = Models\StorageClassType::STANDARD;
        $request->metadata = array(
            "location" => "demo",
            "user" => "walker",
        );
        $request->tagging = 'TagA=A&TagB=B';
        $input = ObjectBasic::fromAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-user']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-location']);
        $this->assertEquals('AES256', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('gzip', $input->getHeaders()['content-encoding']);
        $this->assertEquals('eB5eJF1ptWaXm4bijSPyxw==', $input->getHeaders()['content-md5']);
        $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
        $this->assertEquals('TagA=A&TagB=B', $input->getHeaders()['x-oss-tagging']);
        $this->assertEmpty($input->getParameters()['append']);
        $this->assertEquals('0', $input->getParameters()['position']);

        $request = new Models\AppendObjectRequest('bucket-123', 'key-123', 0, null, Models\StorageClassType::STANDARD, array(
            "location" => "demo",
            "user" => "walker",
        ), 'no-cache', 'attachment', 'gzip', null, 'eB5eJF1ptWaXm4bijSPyxw==', null, '2022-10-12T00:00:00.000Z', 'AES256', null, null, 'TagA=A&TagB=B', true, null, null, null, null, null, Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-user']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-location']);
        $this->assertEquals('AES256', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('gzip', $input->getHeaders()['content-encoding']);
        $this->assertEquals('eB5eJF1ptWaXm4bijSPyxw==', $input->getHeaders()['content-md5']);
        $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
        $this->assertEquals('TagA=A&TagB=B', $input->getHeaders()['x-oss-tagging']);
        $this->assertEmpty($input->getParameters()['append']);
        $this->assertEquals('0', $input->getParameters()['position']);

        $request = new Models\AppendObjectRequest('bucket-123', 'key-123', 0, Models\ObjectACLType::PUBLIC_READ, Models\StorageClassType::STANDARD, array(
            "location" => "demo",
            "user" => "walker",
        ), 'no-cache', 'attachment', 'gzip', null, 'eB5eJF1ptWaXm4bijSPyxw==', null, '2022-10-12T00:00:00.000Z', 'AES256', null, null, 'TagA=A&TagB=B', true, null, null, null, null, null, Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertNull($input->getBody());

        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-user']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-location']);
        $this->assertEquals('AES256', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals('gzip', $input->getHeaders()['content-encoding']);
        $this->assertEquals('eB5eJF1ptWaXm4bijSPyxw==', $input->getHeaders()['content-md5']);
        $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
        $this->assertEquals('TagA=A&TagB=B', $input->getHeaders()['x-oss-tagging']);
        $this->assertEmpty($input->getParameters()['append']);
        $this->assertEquals('0', $input->getParameters()['position']);

        $request = new Models\AppendObjectRequest('bucket-123', 'key-123', 0);
        $request->cacheControl = 'no-cache';
        $request->contentDisposition = 'attachment';
        $request->contentEncoding = 'gzip';
        $request->contentMd5 = 'eB5eJF1ptWaXm4bijSPyxw==';
        $request->expires = '2022-10-12T00:00:00.000Z';
        $request->forbidOverwrite = true;
        $request->serverSideEncryption = 'KMS';
        $request->serverSideDataEncryption = 'SM4';
        $request->serverSideEncryptionKeyId = '9468da86-3509-4f8d-a61e-6eab1eac****';
        $request->body = Utils::streamFor($body);
        $input = ObjectBasic::fromAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals($input->getBody()->getContents(), $body);
        $this->assertEquals('no-cache', $input->getHeaders()['cache-control']);
        $this->assertEquals('attachment', $input->getHeaders()['content-disposition']);
        $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
        $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
        $this->assertEmpty($input->getParameters()['append']);
        $this->assertEquals('0', $input->getParameters()['position']);
    }

    public function testToAppendObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toAppendObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'x-oss-next-append-position' => '0', 'x-oss-hash-crc64ecma' => '1275300285919610****'],
                null,
                new OperationInput('AppendObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'x-oss-next-append-position' => '0', 'x-oss-hash-crc64ecma' => '1275300285919610****'],
                ),
            );
            $result = ObjectBasic::toAppendObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"F2064A169EE92E9775EE5324D0B1****"', $result->etag);
            $this->assertEquals('1275300285919610****', $result->hashCrc64);
            $this->assertEquals(0, $result->nextPosition);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'text', 'x-oss-hash-crc64ecma' => '1275300285919610****', "x-oss-server-side-encryption" => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', 'x-oss-next-append-position' => '1717',],
                null,
                new OperationInput('AppendObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'Content-Type' => 'text', 'x-oss-hash-crc64ecma' => '1275300285919610****', "x-oss-server-side-encryption" => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', 'x-oss-next-append-position' => '1717'],
                ),
            );
            $result = ObjectBasic::toAppendObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"F2064A169EE92E9775EE5324D0B1****"', $result->etag);
            $this->assertEquals('1275300285919610****', $result->hashCrc64);
            $this->assertEquals('KMS', $result->serverSideEncryption);
            $this->assertEquals('SM4', $result->serverSideDataEncryption);
            $this->assertEquals('12f8711f-90df-4e0d-903d-ab972b0f****', $result->serverSideEncryptionKeyId);
            $this->assertEquals('CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', $result->versionId);
            $this->assertEquals(1717, $result->nextPosition);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromDeleteObject()
    {
        // miss required field
        try {
            $request = new Models\DeleteObjectRequest();
            $input = ObjectBasic::fromDeleteObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteObjectRequest('bucket-123');
            $input = ObjectBasic::fromDeleteObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\DeleteObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromDeleteObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\DeleteObjectRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****';
        $input = ObjectBasic::fromDeleteObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToDeleteObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toDeleteObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'No Content',
                204,
                ['x-oss-request-id' => '123',],
                null,
                new OperationInput('DeleteObject', 'DELETE', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    204,
                    ['x-oss-request-id' => '123'],
                ),
            );
            $result = ObjectBasic::toDeleteObject($output);
            $this->assertEquals('No Content', $result->status);
            $this->assertEquals(204, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'No Content',
                204,
                ['x-oss-request-id' => '123', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', 'x-oss-delete-marker' => 'true'],
                null,
                new OperationInput('DeleteObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    204,
                    ['x-oss-request-id' => '123', 'x-oss-version-id' => 'CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', 'x-oss-delete-marker' => 'true'],
                ),
            );
            $result = ObjectBasic::toDeleteObject($output);
            $this->assertEquals('No Content', $result->status);
            $this->assertEquals(204, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertTrue($result->deleteMarker);
            $this->assertEquals('CAEQHxiBgMD4qOWz3hgiIDUyMWIzNTBjMWM4NjQ5MDJiNTM4YzEwZGQxM2Rk****', $result->versionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromHeadObject()
    {
        // miss required field
        try {
            $request = new Models\HeadObjectRequest();
            $input = ObjectBasic::fromHeadObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\HeadObjectRequest('bucket-123');
            $input = ObjectBasic::fromHeadObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\HeadObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromHeadObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\HeadObjectRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****';
        $input = ObjectBasic::fromHeadObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToHeadObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toHeadObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'Archive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"'],
                null,
                new OperationInput('HeadObject', 'HEAD', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'Archive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"'],
                ),
            );
            $result = ObjectBasic::toHeadObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"fba9dede5f27731c9771645a3986****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2018 09:41:56 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals(344606, $result->contentLength);
            $this->assertEquals('Archive', $result->storageClass);
            $this->assertEquals('Normal', $result->objectType);
            $this->assertEquals('image/jpg', $result->contentType);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'ColdArchive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"', 'x-oss-transition-time' => 'Thu, 31 Oct 2024 00:24:17 GMT', 'x-oss-restore' => 'ongoing-request="false", expiry-date="Fri, 08 Nov 2024 08:15:52 GMT"', 'x-oss-version-Id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****'],
                null,
                new OperationInput('HeadObject', 'HEAD', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'ColdArchive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"', 'x-oss-transition-time' => 'Thu, 31 Oct 2024 00:24:17 GMT', 'x-oss-restore' => 'ongoing-request="false", expiry-date="Fri, 08 Nov 2024 08:15:52 GMT"', 'x-oss-version-Id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****'],
                ),
            );
            $result = ObjectBasic::toHeadObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"fba9dede5f27731c9771645a3986****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2018 09:41:56 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals(344606, $result->contentLength);
            $this->assertEquals('ColdArchive', $result->storageClass);
            $this->assertEquals('Normal', $result->objectType);
            $this->assertEquals('image/jpg', $result->contentType);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Thu, 31 Oct 2024 00:24:17 GMT',
                new \DateTimeZone('UTC')
            ), $result->transitionTime);
            $this->assertEquals('ongoing-request="false", expiry-date="Fri, 08 Nov 2024 08:15:52 GMT"', $result->restore);
            $this->assertEquals('CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****', $result->versionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => '20', 'Accept-Ranges' => 'bytes', 'Content-disposition' => 'attachment; filename=testing.txt', 'Cache-control' => 'no-cache', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-server-side-encryption' => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-tagging-count' => '2', 'Content-MD5' => 'si4Nw3Cn9wZ/rPX3XX+j****', 'x-oss-hash-crc64ecma' => '870718044876840****', 'x-oss-meta-name' => 'demo', 'x-oss-meta-email' => 'demo@aliyun.com'],
            Utils::streamFor(),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Normal', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'image/jpg', 'Content-Length' => '20', 'Accept-Ranges' => 'bytes', 'Content-disposition' => 'attachment; filename=testing.txt', 'Cache-control' => 'no-cache', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-server-side-encryption' => 'KMS', 'x-oss-server-side-data-encryption' => 'SM4', 'x-oss-server-side-encryption-key-id' => '12f8711f-90df-4e0d-903d-ab972b0f****', 'x-oss-tagging-count' => '2', 'Content-MD5' => 'si4Nw3Cn9wZ/rPX3XX+j****', 'x-oss-hash-crc64ecma' => '870718044876840****', 'x-oss-meta-name' => 'demo', 'x-oss-meta-email' => 'demo@aliyun.com'],
                null
            ),
        );
        $result = ObjectBasic::toHeadObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('image/jpg', $result->contentType);
        $this->assertEquals('no-cache', $result->cacheControl);
        $this->assertEquals('attachment; filename=testing.txt', $result->contentDisposition);
        $this->assertEquals(20, $result->contentLength);
        $this->assertEquals('Normal', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals('KMS', $result->serverSideEncryption);
        $this->assertEquals('SM4', $result->serverSideDataEncryption);
        $this->assertEquals('12f8711f-90df-4e0d-903d-ab972b0f****', $result->serverSideEncryptionKeyId);
        $this->assertEquals(2, $result->taggingCount);
        $this->assertEquals('demo', $result->metadata['name']);
        $this->assertEquals('demo@aliyun.com', $result->metadata['email']);
        $this->assertEquals('si4Nw3Cn9wZ/rPX3XX+j****', $result->contentMd5);
        $this->assertEquals('870718044876840****', $result->hashCrc64);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Appendable', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'text', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-next-append-position' => '1001'],
            Utils::streamFor(),
            new OperationInput('GetObject', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"5B3C1A2E05E1B002CC607C****"', 'x-oss-object-type' => 'Appendable', 'Last-Modified' => 'Fri, 24 Feb 2012 06:07:48 GMT', 'Content-Type' => 'text', 'X-Oss-Storage-Class' => 'Standard', 'x-oss-next-append-position' => '1001'],
                null
            ),
        );
        $result = ObjectBasic::toHeadObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals('"5B3C1A2E05E1B002CC607C****"', $result->etag);
        $this->assertEquals(\DateTime::createFromFormat(
            'D, d M Y H:i:s \G\M\T',
            'Fri, 24 Feb 2012 06:07:48 GMT',
            new \DateTimeZone('UTC')
        ), $result->lastModified);
        $this->assertEquals('text', $result->contentType);
        $this->assertEquals('Appendable', $result->objectType);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
        $this->assertEquals(1001, $result->nextAppendPosition);
    }

    public function testFromGetObjectMeta()
    {
        // miss required field
        try {
            $request = new Models\GetObjectMetaRequest();
            $input = ObjectBasic::fromGetObjectMeta($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetObjectMetaRequest('bucket-123');
            $input = ObjectBasic::fromGetObjectMeta($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\GetObjectMetaRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromGetObjectMeta($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\GetObjectMetaRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****';
        $input = ObjectBasic::fromGetObjectMeta($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToGetObjectMeta()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toGetObjectMeta($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'Archive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"'],
                null,
                new OperationInput('GetObjectMeta', 'HEAD', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'x-oss-object-type' => 'Normal', 'x-oss-storage-class' => 'Archive', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'Content-Type' => 'image/jpg', 'ETag' => '"fba9dede5f27731c9771645a3986****"'],
                ),
            );
            $result = ObjectBasic::toGetObjectMeta($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"fba9dede5f27731c9771645a3986****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2018 09:41:56 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals(344606, $result->contentLength);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'ETag' => '"fba9dede5f27731c9771645a3986****"', 'x-oss-transition-time' => 'Thu, 31 Oct 2024 00:24:17 GMT', 'x-oss-version-Id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****', 'x-oss-last-access-time' => 'Thu, 14 Oct 2021 11:49:05 GMT'],
                null,
                new OperationInput('GetObjectMeta', 'HEAD', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'Last-Modified' => 'Fri, 24 Feb 2018 09:41:56 GMT', 'Content-Length' => '344606', 'ETag' => '"fba9dede5f27731c9771645a3986****"', 'x-oss-transition-time' => 'Thu, 31 Oct 2024 00:24:17 GMT', 'x-oss-version-Id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****', 'x-oss-last-access-time' => 'Thu, 14 Oct 2021 11:49:05 GMT'],
                ),
            );
            $result = ObjectBasic::toGetObjectMeta($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"fba9dede5f27731c9771645a3986****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Fri, 24 Feb 2018 09:41:56 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals(344606, $result->contentLength);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Thu, 31 Oct 2024 00:24:17 GMT',
                new \DateTimeZone('UTC')
            ), $result->transitionTime);
            $this->assertEquals(\DateTime::createFromFormat(
                'D, d M Y H:i:s \G\M\T',
                'Thu, 14 Oct 2021 11:49:05 GMT',
                new \DateTimeZone('UTC')
            ), $result->lastAccessTime);
            $this->assertEquals('CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****', $result->versionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromRestoreObject()
    {
        // miss required field
        try {
            $request = new Models\RestoreObjectRequest();
            $input = ObjectBasic::fromRestoreObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\RestoreObjectRequest('bucket-123');
            $input = ObjectBasic::fromRestoreObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\RestoreObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromRestoreObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\RestoreObjectRequest('bucket-123', 'key-123');
        $request->restoreRequest = new RestoreRequest(3);
        $input = ObjectBasic::fromRestoreObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RestoreRequest><Days>3</Days></RestoreRequest>
BBB;
        $body = $input->getBody()->getContents();
        $this->assertEquals($xml, $this->cleanXml($body));

        $request = new Models\RestoreObjectRequest('bucket-123', 'key-123');
        $request->restoreRequest = new RestoreRequest(3, 'Bulk');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****';
        $input = ObjectBasic::fromRestoreObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><RestoreRequest><Days>3</Days><JobParameters><Tier>Bulk</Tier></JobParameters></RestoreRequest>
BBB;
        $body = $input->getBody()->getContents();
        $this->assertEquals($xml, $this->cleanXml($body));
    }

    public function testToRestoreObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toRestoreObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'Accepted',
                202,
                ['x-oss-request-id' => '123'],
                null,
                new OperationInput('RestoreObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    202,
                    ['x-oss-request-id' => '123',],
                ),
            );
            $result = ObjectBasic::toRestoreObject($output);
            $this->assertEquals('Accepted', $result->status);
            $this->assertEquals(202, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'Accepted',
                202,
                ['x-oss-request-id' => '123', 'x-oss-object-restore-priority' => 'Standard', 'x-oss-version-id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****'],
                null,
                new OperationInput('RestoreObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    202,
                    ['x-oss-request-id' => '123', 'x-oss-object-restore-priority' => 'Standard', 'x-oss-version-id' => 'CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****'],
                ),
            );
            $result = ObjectBasic::toRestoreObject($output);
            $this->assertEquals('Accepted', $result->status);
            $this->assertEquals(202, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('Standard', $result->restorePriority);
            $this->assertEquals('CAEQNRiBgICb8o6D0BYiIDNlNzk5NGE2M2Y3ZjRhZTViYTAxZGE0ZTEyMWYy****', $result->versionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

    }

    public function testFromCleanRestoredObject()
    {
        // miss required field
        try {
            $request = new Models\CleanRestoredObjectRequest();
            $input = ObjectBasic::fromCleanRestoredObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CleanRestoredObjectRequest('bucket-123');
            $input = ObjectBasic::fromCleanRestoredObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\CleanRestoredObjectRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromCleanRestoredObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\CleanRestoredObjectRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****';
        $input = ObjectBasic::fromCleanRestoredObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNhiBgM0BYiIDc4MGZjZGI2OTBjOTRmNTE5NmU5NmFhZjhjYmY****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToCleanRestoredObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toCleanRestoredObject($output);
            $this->assertEquals('', $result->status);
            $this->assertEquals(0, $result->statusCode);
            $this->assertEquals('', $result->requestId);
            $this->assertEquals(0, count($result->headers));
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123'],
                null,
                new OperationInput('CleanRestoredObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123',],
                ),
            );
            $result = ObjectBasic::toCleanRestoredObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromPutObjectAcl()
    {
        // miss required field
        try {
            $request = new Models\PutObjectAclRequest();
            $input = ObjectBasic::fromPutObjectAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutObjectAclRequest('bucket-123');
            $input = ObjectBasic::fromPutObjectAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\PutObjectAclRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromPutObjectAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, acl", (string)$e);
        }

        $request = new Models\PutObjectAclRequest('bucket-123', 'key-123', Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromPutObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()["x-oss-object-acl"]);

        $request = new Models\PutObjectAclRequest('bucket-123', 'key-123', Models\ObjectACLType::PRIVATE, "CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****");
        $input = ObjectBasic::fromPutObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()["x-oss-object-acl"]);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $input->getParameters()['versionId']);

        $request = new Models\PutObjectAclRequest('bucket-123', 'key-123', Models\ObjectACLType::PRIVATE);
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()["x-oss-object-acl"]);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        $request = new Models\PutObjectAclRequest('bucket-123', 'key-123', null, null, null, null, Models\ObjectACLType::PRIVATE);
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()["x-oss-object-acl"]);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        $request = new Models\PutObjectAclRequest('bucket-123', 'key-123',Models\ObjectACLType::PUBLIC_READ,null,'requester',null,Models\ObjectACLType::PRIVATE);
        $input = ObjectBasic::fromPutObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()["x-oss-object-acl"]);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToPutObjectAcl()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toPutObjectAcl($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123'],
                null,
                new OperationInput('PutObjectAcl', 'PUT', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123',],
                ),
            );
            $result = ObjectBasic::toPutObjectAcl($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
                null,
                new OperationInput('PutObjectAcl', 'PUT', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
                ),
            );
            $result = ObjectBasic::toPutObjectAcl($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromGetObjectAcl()
    {
        // miss required field
        try {
            $request = new Models\GetObjectAclRequest();
            $input = ObjectBasic::fromGetObjectAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetObjectAclRequest('bucket-123');
            $input = ObjectBasic::fromGetObjectAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\GetObjectAclRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromGetObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\GetObjectAclRequest('bucket-123', 'key-123', "CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****");
        $input = ObjectBasic::fromGetObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $input->getParameters()['versionId']);

        $request = new Models\GetObjectAclRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromGetObjectAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToGetObjectAcl()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toGetObjectAcl($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
                null,
                new OperationInput('GetObjectAcl', 'GET', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'content-type' => 'test'],
                ),
            );
            $result = ObjectBasic::toGetObjectAcl($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <AccessControlPolicy>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <AccessControlPolicy></AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str),
            new OperationInput('GetObjectAcl', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
            ),
        );
        $result = ObjectBasic::toGetObjectAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->accessControlList);

        $str = '<?xml version="1.0" encoding="UTF-8"?>
<AccessControlPolicy>
    <Owner>
        <ID>0022012****</ID>
        <DisplayName>0022012****</DisplayName>
    </Owner>
    <AccessControlList>
        <Grant>public-read</Grant>
    </AccessControlList>
</AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str),
            new OperationInput('GetObjectAcl', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            ),
        );
        $result = ObjectBasic::toGetObjectAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(Models\ObjectACLType::PUBLIC_READ, $result->accessControlList->grant);
        $this->assertEquals('0022012****', $result->owner->id);
        $this->assertEquals('0022012****', $result->owner->displayName);

        $str = '<?xml version="1.0" encoding="UTF-8"?>
<AccessControlPolicy>
    <Owner>
        <ID>0022012****</ID>
        <DisplayName>0022012****</DisplayName>
    </Owner>
    <AccessControlList>
        <Grant>private</Grant>
    </AccessControlList>
</AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            Utils::streamFor($str),
            new OperationInput('GetObjectAcl', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            ),
        );
        $result = ObjectBasic::toGetObjectAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(3, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $result->accessControlList->grant);
        $this->assertEquals('0022012****', $result->owner->id);
        $this->assertEquals('0022012****', $result->owner->displayName);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
    }

    public function testFromGetObjectTagging()
    {
        // miss required field
        try {
            $request = new Models\GetObjectTaggingRequest();
            $input = ObjectBasic::fromGetObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetObjectTaggingRequest('bucket-123');
            $input = ObjectBasic::fromGetObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\GetObjectTaggingRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromGetObjectTagging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\GetObjectTaggingRequest('bucket-123', 'key-123', "CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****", "requester");
        $input = ObjectBasic::fromGetObjectTagging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToGetObjectTagging()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toGetObjectTagging($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
                null,
                new OperationInput('GetObjectTagging', 'GET', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'content-type' => 'test'],
                ),
            );
            $result = ObjectBasic::toGetObjectTagging($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <Tagging>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <Tagging></Tagging>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str),
            new OperationInput('GetObjectTagging', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
            ),
        );
        $result = ObjectBasic::toGetObjectTagging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->tagSet);

        $str = '<Tagging>
  <TagSet>
    <Tag>
      <Key>a</Key>
      <Value>1</Value>
    </Tag>
    <Tag>
      <Key>b</Key>
      <Value>2</Value>
    </Tag>
  </TagSet>
</Tagging>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str),
            new OperationInput('GetObjectTagging', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            ),
        );
        $result = ObjectBasic::toGetObjectTagging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(2, count($result->tagSet->tags));
        $this->assertEquals('a', $result->tagSet->tags[0]->key);
        $this->assertEquals('1', $result->tagSet->tags[0]->value);
        $this->assertEquals('b', $result->tagSet->tags[1]->key);
        $this->assertEquals('2', $result->tagSet->tags[1]->value);

        $str = '<?xml version="1.0" encoding="UTF-8"?>
<Tagging>
  <TagSet>
    <Tag>
      <Key>age</Key>
      <Value>18</Value>
    </Tag>
  </TagSet>
</Tagging>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            Utils::streamFor($str),
            new OperationInput('GetObjectTagging', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            ),
        );
        $result = ObjectBasic::toGetObjectTagging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(3, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1, count($result->tagSet->tags));
        $this->assertEquals('age', $result->tagSet->tags[0]->key);
        $this->assertEquals('18', $result->tagSet->tags[0]->value);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
    }

    public function testFromPutObjectTagging()
    {
        // miss required field
        try {
            $request = new Models\PutObjectTaggingRequest();
            $input = ObjectBasic::fromPutObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutObjectTaggingRequest('bucket-123');
            $input = ObjectBasic::fromPutObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\PutObjectTaggingRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromPutObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, tagging", (string)$e);
        }

        $request = new Models\PutObjectTaggingRequest('bucket-123', 'key-123');
        $request->tagging = new Models\Tagging(
            new Models\TagSet(
                [new Models\Tag('k1', 'v1'), new Models\Tag('k2', 'v2')]
            )
        );
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****';
        $input = ObjectBasic::fromPutObjectTagging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $inputXml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><Tagging><TagSet><Tag><Key>k1</Key><Value>v1</Value></Tag><Tag><Key>k2</Key><Value>v2</Value></Tag></TagSet></Tagging>
BBB;
        $this->assertEquals($inputXml, $this->cleanXml($input->getBody()->getContents()));
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $input->getParameters()['versionId']);
    }

    public function testToPutObjectTagging()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toPutObjectTagging($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            null,
            new OperationInput('PutObjectTagging', 'PUT', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
            ),
        );
        $result = ObjectBasic::toPutObjectTagging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);


        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            null,
            new OperationInput('PutObjectTagging', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            ),
        );
        $result = ObjectBasic::toPutObjectTagging($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
    }

    public function testFromDeleteObjectTagging()
    {
        // miss required field
        try {
            $request = new Models\DeleteObjectTaggingRequest();
            $input = ObjectBasic::fromDeleteObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteObjectTaggingRequest('bucket-123');
            $input = ObjectBasic::fromDeleteObjectTagging($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\DeleteObjectTaggingRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromDeleteObjectTagging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\DeleteObjectTaggingRequest('bucket-123', 'key-123');
        $request->requestPayer = 'requester';
        $request->versionId = 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****';
        $input = ObjectBasic::fromDeleteObjectTagging($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $input->getParameters()['versionId']);
    }

    public function testToDeleteObjectTagging()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toDeleteObjectTagging($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            null,
            new OperationInput('DeleteObjectTagging', 'DELETE', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                204,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
            ),
        );
        $result = ObjectBasic::toDeleteObjectTagging($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);


        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            null,
            new OperationInput('DeleteObjectTagging', 'DELETE', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                204,
                ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            ),
        );
        $result = ObjectBasic::toDeleteObjectTagging($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
    }

    public function testFromPutSymlink()
    {
        // miss required field
        try {
            $request = new Models\PutSymlinkRequest();
            $input = ObjectBasic::fromPutSymlink($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutSymlinkRequest('bucket-123');
            $input = ObjectBasic::fromPutSymlink($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\PutSymlinkRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromPutSymlink($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, target", (string)$e);
        }

        $request = new Models\PutSymlinkRequest('bucket-123', 'key-123', 'target-key');
        $input = ObjectBasic::fromPutSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('target-key', $input->getHeaders()['x-oss-symlink-target']);

        $request = new Models\PutSymlinkRequest('bucket-123', 'key-123', 'target-key', Models\ObjectACLType::PRIVATE, Models\StorageClassType::STANDARD, ['name' => 'demo', 'email' => 'demo@aliyun.com'], true);
        $input = ObjectBasic::fromPutSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('target-key', $input->getHeaders()['x-oss-symlink-target']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);

        $request = new Models\PutSymlinkRequest('bucket-123', 'key-123', 'target-key');
        $request->requestPayer = 'requester';
        $input = ObjectBasic::fromPutSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('target-key', $input->getHeaders()['x-oss-symlink-target']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        $request = new Models\PutSymlinkRequest('bucket-123', 'key-123', null, null, Models\StorageClassType::STANDARD, ['name' => 'demo', 'email' => 'demo@aliyun.com'], true, null, null, Models\ObjectACLType::PUBLIC_READ, 'target-key');
        $input = ObjectBasic::fromPutSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('target-key', $input->getHeaders()['x-oss-symlink-target']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals(Models\ObjectACLType::PUBLIC_READ, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);

        $request = new Models\PutSymlinkRequest('bucket-123', 'key-123', 'target-key',Models\ObjectACLType::PRIVATE, Models\StorageClassType::STANDARD, ['name' => 'demo', 'email' => 'demo@aliyun.com'], true,null,null,Models\ObjectACLType::PUBLIC_READ,'target-key2');
        $input = ObjectBasic::fromPutSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('target-key2', $input->getHeaders()['x-oss-symlink-target']);
        $this->assertEquals('true', $input->getHeaders()['x-oss-forbid-overwrite']);
        $this->assertEquals(Models\ObjectACLType::PUBLIC_READ, $input->getHeaders()['x-oss-object-acl']);
        $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
        $this->assertEquals('demo', $input->getHeaders()['x-oss-meta-name']);
        $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
    }

    public function testToPutSymlink()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toPutSymlink($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            null,
            new OperationInput('PutSymlink', 'DELETE', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
            ),
        );
        $result = ObjectBasic::toPutSymlink($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);


        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            null,
            new OperationInput('PutSymlink', 'DELETE', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'X-Oss-Version-Id' => 'CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****'],
            ),
        );
        $result = ObjectBasic::toPutSymlink($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('CAEQMhiBgMC1qpSD0BYiIGQ0ZmI5ZDEyYWVkNTQwMjBiNTliY2NjNmY3ZTVk****', $result->versionId);
    }

    public function testFromGetSymlink()
    {
        // miss required field
        try {
            $request = new Models\GetSymlinkRequest();
            $input = ObjectBasic::fromGetSymlink($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetSymlinkRequest('bucket-123');
            $input = ObjectBasic::fromGetSymlink($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        $request = new Models\GetSymlinkRequest('bucket-123', 'key-123');
        $input = ObjectBasic::fromGetSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());

        $request = new Models\GetSymlinkRequest('bucket-123', 'key-123', 'CAEQNRiBgMClj7qD0BYiIDQ5Y2QyMjc3NGZkODRlMTU5M2VkY2U3MWRiNGRh****', 'requester');
        $input = ObjectBasic::fromGetSymlink($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('CAEQNRiBgMClj7qD0BYiIDQ5Y2QyMjc3NGZkODRlMTU5M2VkY2U3MWRiNGRh****', $input->getParameters()['versionId']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToGetSymlink()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toGetSymlink($output);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e);
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => 'A797938C31D59EDD08D86188F6D5****', 'x-oss-symlink-target' => 'example.jpg'],
            null,
            new OperationInput('GetSymlink', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => 'A797938C31D59EDD08D86188F6D5****', 'x-oss-symlink-target' => 'example.jpg'],
            ),
        );
        $result = ObjectBasic::toGetSymlink($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(3, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('A797938C31D59EDD08D86188F6D5****', $result->etag);
        $this->assertEquals('example.jpg', $result->target);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'ETag' => 'A797938C31D59EDD08D86188F6D5****', 'x-oss-symlink-target' => 'example.jpg', 'x-oss-version-id' => 'CAEQNRiBgMClj7qD0BYiIDQ5Y2QyMjc3NGZkODRlMTU5M2VkY2U3MWRiNGRh****'],
            null,
            new OperationInput('GetSymlink', 'GET', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'ETag' => 'A797938C31D59EDD08D86188F6D5****', 'x-oss-symlink-target' => 'example.jpg', 'x-oss-version-id' => 'CAEQNRiBgMClj7qD0BYiIDQ5Y2QyMjc3NGZkODRlMTU5M2VkY2U3MWRiNGRh****'],
            ),
        );
        $result = ObjectBasic::toGetSymlink($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(4, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('CAEQNRiBgMClj7qD0BYiIDQ5Y2QyMjc3NGZkODRlMTU5M2VkY2U3MWRiNGRh****', $result->versionId);
        $this->assertEquals('A797938C31D59EDD08D86188F6D5****', $result->etag);
        $this->assertEquals('example.jpg', $result->target);
    }

    public function testFromAsyncProcessObject()
    {
        // miss required field
        try {
            $request = new Models\AsyncProcessObjectRequest();
            $input = ObjectBasic::fromAsyncProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\AsyncProcessObjectRequest('bucket-123');
            $input = ObjectBasic::fromAsyncProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\AsyncProcessObjectRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromAsyncProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, process", (string)$e);
        }

        $base = 'video/convert,f_avi,vcodec_h265,s_1920x1080,vb_2000000,fps_30,acodec_aac,ab_100000,sn_1';
        $bucket = 'dest-bucket';
        $file = 'demo.mp4';
        $encoded_bucket = rtrim(base64_encode($bucket), '=');
        $encoded_file = rtrim(base64_encode($file), '=');
        $process = sprintf("%s|sys/saveas,b_%s,o_%s", $base, $encoded_bucket, $encoded_file);
        $request = new Models\AsyncProcessObjectRequest('bucket-123', 'key-123', $process, 'requester');
        $input = ObjectBasic::fromAsyncProcessObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('x-oss-async-process=video/convert,f_avi,vcodec_h265,s_1920x1080,vb_2000000,fps_30,acodec_aac,ab_100000,sn_1|sys/saveas,b_ZGVzdC1idWNrZXQ,o_ZGVtby5tcDQ', $input->getBody()->getContents());
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToAsyncProcessObject()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toAsyncProcessObject($output);
            $this->assertEquals('', $result->status);
            $this->assertEquals(0, $result->statusCode);
            $this->assertEquals('', $result->requestId);
            $this->assertEquals(0, count($result->headers));
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            null,
            new OperationInput('AsyncProcessObject', 'POST', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            ),
        );
        $result = ObjectBasic::toAsyncProcessObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->eventId);
        $this->assertNull($result->taskId);
        $this->assertNull($result->processRequestId);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            Utils::streamFor('{"EventId":"181-1kZUlN60OH4fWOcOjZEnGnG****","RequestId":"1D99637F-F59E-5B41-9200-C4892F52****","TaskId":"MediaConvert-e4a737df-69e9-4fca-8d9b-17c40ea3****"}'),
            new OperationInput('AsyncProcessObject', 'POST', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            ),
        );
        $result = ObjectBasic::toAsyncProcessObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('181-1kZUlN60OH4fWOcOjZEnGnG****', $result->eventId);
        $this->assertEquals('1D99637F-F59E-5B41-9200-C4892F52****', $result->processRequestId);
        $this->assertEquals('MediaConvert-e4a737df-69e9-4fca-8d9b-17c40ea3****', $result->taskId);
    }

    public function testFromProcessObject()
    {
        // miss required field
        try {
            $request = new Models\ProcessObjectRequest();
            $input = ObjectBasic::fromProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\ProcessObjectRequest('bucket-123');
            $input = ObjectBasic::fromProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\ProcessObjectRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromProcessObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, process", (string)$e);
        }

        $process = sprintf("image/resize,w_100|sys/saveas,o_%s", base64_encode("dest.jpg"));
        $request = new Models\ProcessObjectRequest('bucket-123', 'key-123', $process, 'requester');
        $input = ObjectBasic::fromProcessObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('x-oss-process=image/resize,w_100|sys/saveas,o_ZGVzdC5qcGc=', $input->getBody()->getContents());
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
    }

    public function testToProcessObject()
    {
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toProcessObject($output);
            $this->assertEquals('', $result->status);
            $this->assertEquals(0, $result->statusCode);
            $this->assertEquals('', $result->requestId);
            $this->assertEquals(0, count($result->headers));
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'Content-Type' => 'application/json'],
            null,
            new OperationInput('ProcessObject', 'POST', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/json'],
            ),
        );
        $result = ObjectBasic::toProcessObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->processStatus);
        $this->assertNull($result->fileSize);
        $this->assertNull($result->bucket);
        $this->assertNull($result->key);

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            Utils::streamFor('{
    "bucket": "dest-bucket",
    "fileSize": 3267,
    "object": "dest.jpg",
    "status": "OK"}'),
            new OperationInput('ProcessObject', 'POST', []),
            null,
            new \GuzzleHttp\Psr7\Response(
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/json;charset=utf-8'],
            ),
        );
        $result = ObjectBasic::toProcessObject($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('dest-bucket', $result->bucket);
        $this->assertEquals('dest.jpg', $result->key);
        $this->assertEquals(3267, $result->fileSize);
        $this->assertEquals('OK', $result->processStatus);
    }

    public function testFromSealAppendObject()
    {
        // miss required field
        try {
            $request = new Models\SealAppendObjectRequest();
            $input = ObjectBasic::fromSealAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\SealAppendObjectRequest('bucket-123');
            $input = ObjectBasic::fromSealAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\SealAppendObjectRequest('bucket-123', 'key-123');
            $input = ObjectBasic::fromSealAppendObject($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, position", (string)$e);
        }
        $body = 'hi oss';
        $request = new Models\SealAppendObjectRequest('bucket-123', 'key-123', 0);
        $input = ObjectBasic::fromSealAppendObject($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('key-123', $input->getKey());
        $this->assertEquals('0', $input->getParameters()['position']);
    }

    public function testToSealAppendObject()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectBasic::toSealAppendObject($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'x-oss-sealed-time' => 'Wed, 07 May 2025 23:00:00 GMT'],
                null,
                new OperationInput('SealAppendObject', 'POST', []),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"F2064A169EE92E9775EE5324D0B1****"', 'x-oss-sealed-time' => 'Wed, 07 May 2025 23:00:00 GMT'],
                ),
            );
            $result = ObjectBasic::toSealAppendObject($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('Wed, 07 May 2025 23:00:00 GMT', $result->sealedTime);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    private function cleanXml($xml)
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
