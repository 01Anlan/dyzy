<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Exception;
use AlibabaCloud\Oss\V2\Transform\ObjectMultipart;
use AlibabaCloud\Oss\V2\Utils;

class ObjectMultipartTest extends \PHPUnit\Framework\TestCase
{
    public function testFromInitiateMultipartUpload()
    {
        // miss required field
        try {
            $request = new Models\InitiateMultipartUploadRequest();
            $input = ObjectMultipart::fromInitiateMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        try {
            $request = new Models\InitiateMultipartUploadRequest('bucket-123');
            $input = ObjectMultipart::fromInitiateMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\InitiateMultipartUploadRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromInitiateMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals(Models\EncodeType::URL, $input->getParameters()['encoding-type']);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\InitiateMultipartUploadRequest('bucket-123', 'key-123', Models\EncodeType::URL, Models\StorageClassType::STANDARD, array('name' => 'walker', 'email' => 'demo@aliyun.com'), 'no-cache', 'attachment', 'utf-8', null, null, null, '2022-10-12T00:00:00.000Z', 'KMS', 'SM4', '9468da86-3509-4f8d-a61e-6eab1eac****', 'TagA=B&TagC=D', false, 'requester');
            $input = ObjectMultipart::fromInitiateMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals(Models\EncodeType::URL, $input->getParameters()['encoding-type']);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
            $this->assertEquals(Models\StorageClassType::STANDARD, $input->getHeaders()['x-oss-storage-class']);
            $this->assertEquals('walker', $input->getHeaders()['x-oss-meta-name']);
            $this->assertEquals('demo@aliyun.com', $input->getHeaders()['x-oss-meta-email']);
            $this->assertEquals('KMS', $input->getHeaders()['x-oss-server-side-encryption']);
            $this->assertEquals('SM4', $input->getHeaders()['x-oss-server-side-data-encryption']);
            $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $input->getHeaders()['x-oss-server-side-encryption-key-id']);
            $this->assertEquals('false', $input->getHeaders()['x-oss-forbid-overwrite']);
            $this->assertEquals('utf-8', $input->getHeaders()['content-encoding']);
            $this->assertEquals('2022-10-12T00:00:00.000Z', $input->getHeaders()['expires']);
            $this->assertEquals('TagA=B&TagC=D', $input->getHeaders()['x-oss-tagging']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
            $this->assertEmpty($input->getParameters()['uploads']);
            $this->assertNotEmpty($input->getOpMetadata());
            $this->assertEquals(true, $input->getOpMetadata()['detect_content_type']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testToInitiateMultipartUpload()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toInitiateMultipartUpload($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <InitiateMultipartUploadResult>', $e);
        }

        //empty xml
        try {
            $str = '<?xml version="1.0" encoding="UTF-8"?>
            <InitiateMultipartUploadResult></InitiateMultipartUploadResult>';
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
                Utils::streamFor($str)
            );
            $result = ObjectMultipart::toInitiateMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));
            $this->assertEquals('application/xml', $result->headers['content-type']);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertNull($result->bucket);
            $this->assertNull($result->key);
            $this->assertNull($result->uploadId);
            $this->assertNull($result->encodingType);
        } catch (Exception\DeserializationExecption $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $str = '<?xml version="1.0" encoding="UTF-8"?>
            <InitiateMultipartUploadResult><Bucket></Bucket><Key></Key></InitiateMultipartUploadResult>';
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
                Utils::streamFor($str)
            );
            $result = ObjectMultipart::toInitiateMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));
            $this->assertEquals('application/xml', $result->headers['content-type']);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('', $result->bucket);
            $this->assertEquals('', $result->key);
            $this->assertNull($result->uploadId);
            $this->assertNull($result->encodingType);
        } catch (Exception\DeserializationExecption $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?><InitiateMultipartUploadResult>
    <Bucket>oss-example</Bucket>
    <Key>multipart.data</Key>
    <UploadId>0004B9894A22E5B1888A1E29F823****</UploadId>
</InitiateMultipartUploadResult>')
            );
            $result = ObjectMultipart::toInitiateMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));
            $this->assertEquals('application/xml', $result->headers['content-type']);
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('oss-example', $result->bucket);
            $this->assertEquals('multipart.data', $result->key);
            $this->assertEquals('0004B9894A22E5B1888A1E29F823****', $result->uploadId);
            $this->assertEquals('', $result->encodingType);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromUploadPart()
    {
        // miss required field
        try {
            $request = new Models\UploadPartRequest();
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\UploadPartRequest('bucket-123');
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\UploadPartRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, partNumber", (string)$e);
        }

        try {
            $request = new Models\UploadPartRequest('bucket-123', 'key-123', 1);
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, uploadId", (string)$e);
        }

        try {
            $request = new Models\UploadPartRequest('bucket-123', 'key-123', 1, '0004B9895DBBB6EC9****');
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('1', $input->getParameters()['partNumber']);
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\UploadPartRequest('bucket-123', 'key-123', 1, '0004B9895DBBB6EC9****', null, null, 100 * 1024 * 8, 'requester');
            $input = ObjectMultipart::fromUploadPart($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('1', $input->getParameters()['partNumber']);
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals('819200', $input->getHeaders()['x-oss-traffic-limit']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testToUploadPart()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toUploadPart($output);
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
                null,
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'ETag' => '"7265F4D211B56873A381D321F586****"', 'Content-MD5' => '1B2M2Y8AsgTpgAmY7Ph****', 'x-oss-hash-crc64ecma' => '316181249502703*****'],
                ),
            );
            $result = ObjectMultipart::toUploadPart($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(4, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"7265F4D211B56873A381D321F586****"', $result->etag);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7Ph****', $result->contentMd5);
            $this->assertEquals('316181249502703*****', $result->hashCrc64);
        } catch (Exception\DeserializationExecption $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromUploadPartCopy()
    {
        // miss required field
        try {
            $request = new Models\UploadPartCopyRequest();
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, sourceKey", (string)$e);
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123', 'key-123', null, null, null, 'key-source');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, partNumber", (string)$e);
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123', 'key-123', 1, null, null, 'key-source');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, uploadId", (string)$e);
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123', 'key-123', 1, '0004B9895DBBB6EC9****', null, 'oss-src-dir/oss-src-obj+123');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('/bucket-123/oss-src-dir/oss-src-obj%2B123', $input->getHeaders()['x-oss-copy-source']);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
            $this->assertEquals('1', $input->getParameters()['partNumber']);
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\UploadPartCopyRequest('bucket-123', 'key-123', 1, '0004B9895DBBB6EC9****', 'src-bucket', 'oss-src-dir/oss-src-obj', 'CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', null, null, null, null, null, 100 * 1024 * 8, 'requester');
            $input = ObjectMultipart::fromUploadPartCopy($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('/src-bucket/oss-src-dir/oss-src-obj?versionId=CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', $input->getHeaders()['x-oss-copy-source']);
            $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
            $this->assertEquals('1', $input->getParameters()['partNumber']);
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals('819200', $input->getHeaders()['x-oss-traffic-limit']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testToUploadPartCopy()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toUploadPartCopy($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'x-oss-copy-source-version-id' => 'CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<CopyPartResult>
    <LastModified>2014-07-17T06:27:54.000Z</LastModified>
    <ETag>"5B3C1A2E053D763E1B002CC607C5****"</ETag>
</CopyPartResult>'),
                null,
                null,
                new \GuzzleHttp\Psr7\Response(
                    200,
                    ['x-oss-request-id' => '123', 'x-oss-copy-source-version-id' => 'CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', 'Content-Type' => 'application/xml'],
                ),
            );
            $result = ObjectMultipart::toUploadPartCopy($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(3, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5****"', $result->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2014-07-17T06:27:54.000Z',
                new \DateTimeZone('UTC')
            ), $result->lastModified);
            $this->assertEquals('CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', $result->sourceVersionId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromCompleteMultipartUpload()
    {
        // miss required field
        try {
            $request = new Models\CompleteMultipartUploadRequest();
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123');
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, uploadId", (string)$e);
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****');
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', Models\ObjectACLType::PUBLIC_READ, null, null, null, null, null, null, null, null, Models\ObjectACLType::PRIVATE);
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', Models\ObjectACLType::PUBLIC_READ, null, null, null, null, null, null, null, null, Models\ObjectACLType::PRIVATE);
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals(Models\ObjectACLType::PRIVATE, $input->getHeaders()['x-oss-object-acl']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', null, new Models\CompleteMultipartUpload([new Models\UploadPart(1, '"3349DC700140D7F86A0784842780****"'), new Models\UploadPart(2, '"8C315065167132444177411FDA14****"'), new Models\UploadPart(3, '"8EFDA8BE206636A695359836FE0A****"'),]), null, null, null, null, Models\EncodeType::URL);
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals(Models\EncodeType::URL, $input->getParameters()['encoding-type']);
            $body = $input->getBody()->getContents();
            $this->assertEquals(base64_encode(md5($body, true)), $input->getHeaders()['content-md5']);
            $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload><Part><PartNumber>1</PartNumber><ETag>"3349DC700140D7F86A0784842780****"</ETag></Part><Part><PartNumber>2</PartNumber><ETag>"8C315065167132444177411FDA14****"</ETag></Part><Part><PartNumber>3</PartNumber><ETag>"8EFDA8BE206636A695359836FE0A****"</ETag></Part></CompleteMultipartUpload>
BBB;
            $this->assertEquals($xml, $this->cleanXml($body));
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', null, new Models\CompleteMultipartUpload([new Models\UploadPart(1, null, '"3349DC700140D7F86A0784842780****1"',), new Models\UploadPart(2, null, '"8C315065167132444177411FDA14****2"'), new Models\UploadPart(3, null, '"8EFDA8BE206636A695359836FE0A****3"'),]), null, null, null, null, Models\EncodeType::URL);
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals(Models\EncodeType::URL, $input->getParameters()['encoding-type']);
            $body = $input->getBody()->getContents();
            $this->assertEquals(base64_encode(md5($body, true)), $input->getHeaders()['content-md5']);
            $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload><Part><PartNumber>1</PartNumber><ETag>"3349DC700140D7F86A0784842780****1"</ETag></Part><Part><PartNumber>2</PartNumber><ETag>"8C315065167132444177411FDA14****2"</ETag></Part><Part><PartNumber>3</PartNumber><ETag>"8EFDA8BE206636A695359836FE0A****3"</ETag></Part></CompleteMultipartUpload>
BBB;
            $this->assertEquals($xml, $this->cleanXml($body));
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', null, new Models\CompleteMultipartUpload([new Models\UploadPart(1, '"3349DC700140D7F86A0784842780****"', '"3349DC700140D7F86A0784842780****1"',), new Models\UploadPart(2, '"8C315065167132444177411FDA14****"', '"8C315065167132444177411FDA14****2"'), new Models\UploadPart(3, '"8EFDA8BE206636A695359836FE0A****"', '"8EFDA8BE206636A695359836FE0A****3"'),]), null, null, null, null, Models\EncodeType::URL);
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals(Models\EncodeType::URL, $input->getParameters()['encoding-type']);
            $body = $input->getBody()->getContents();
            $this->assertEquals(base64_encode(md5($body, true)), $input->getHeaders()['content-md5']);
            $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload><Part><PartNumber>1</PartNumber><ETag>"3349DC700140D7F86A0784842780****1"</ETag></Part><Part><PartNumber>2</PartNumber><ETag>"8C315065167132444177411FDA14****2"</ETag></Part><Part><PartNumber>3</PartNumber><ETag>"8EFDA8BE206636A695359836FE0A****3"</ETag></Part></CompleteMultipartUpload>
BBB;
            $this->assertEquals($xml, $this->cleanXml($body));
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $callback = base64_encode('{"callbackUrl":"www.aliyuncs.com", "callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}&x=${x:a}&b=${x:b}"}');
            $callbackVar = base64_encode('{"x:a":"a", "x:b":"b"}');
            $request = new Models\CompleteMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', null, null, 'yes', $callback, $callbackVar, false, null, 'requester');
            $input = ObjectMultipart::fromCompleteMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals('false', $input->getHeaders()['x-oss-forbid-overwrite']);
            $this->assertEquals($callback, $input->getHeaders()['x-oss-callback']);
            $this->assertEquals($callbackVar, $input->getHeaders()['x-oss-callback-var']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testToCompleteMultipartUpload()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toCompleteMultipartUpload($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Call to a member function hasHeader() on null', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                null,
                new OperationInput('CompleteMultipartUpload', 'POST'),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200, ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                ),
            );
            $result = ObjectMultipart::toCompleteMultipartUpload($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <CompleteMultipartUploadResult>', $e->getMessage());
        }


        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<CompleteMultipartUploadResult>
  <EncodingType>url</EncodingType>
  <Location>http://oss-example.oss-cn-hangzhou.aliyuncs.com/multipart.data</Location>
  <Bucket>oss-example</Bucket>
  <Key>demo%2Fmultipart.data</Key>
  <ETag>"097DE458AD02B5F89F9D0530231876****"</ETag>
</CompleteMultipartUploadResult>'),
                new OperationInput('CompleteMultipartUpload', 'POST'),
                null,
                new \GuzzleHttp\Psr7\Response(),
            );
            $result = ObjectMultipart::toCompleteMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('"097DE458AD02B5F89F9D0530231876****"', $result->etag);
            $this->assertEquals(Models\EncodeType::URL, $result->encodingType);
            $this->assertEquals('oss-example', $result->bucket);
            $this->assertEquals('demo/multipart.data', $result->key);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/json', 'x-oss-version-id' => 'CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', 'x-oss-hash-crc64ecma' => '1206617243528768****'],
                Utils::streamFor('{"filename":"oss-obj.txt","size":"100","mimeType":"","x":"a","b":"b"}'),
                new OperationInput('CompleteMultipartUpload', 'POST', ['x-oss-callback' => '{"callbackUrl":"www.aliyuncs.com", "callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}&x=${x:a}&b=${x:b}"}']),
                null,
                new \GuzzleHttp\Psr7\Response(
                    200, ['x-oss-request-id' => '123', 'Content-Type' => 'application/json', 'x-oss-version-id' => 'CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', 'x-oss-hash-crc64ecma' => '1206617243528768****']
                ),
            );
            $result = ObjectMultipart::toCompleteMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(4, count($result->headers));
            $this->assertEquals('123', $result->headers['x-oss-request-id']);
            $this->assertEquals('application/json', $result->headers['Content-Type']);
            $this->assertEquals('CAEQMxiBgMC0vs6D0BYiIGJiZWRjOTRjNTg0NzQ1MTRiN2Y1OTYxMTdkYjQ0****', $result->versionId);
            $this->assertEquals('1206617243528768****', $result->hashCrc64);
            $this->assertEquals('{"filename":"oss-obj.txt","size":"100","mimeType":"","x":"a","b":"b"}', json_encode($result->
            callbackResult));
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }
    }

    public function testFromAbortMultipartUpload()
    {
        // miss required field
        try {
            $request = new Models\AbortMultipartUploadRequest();
            $input = ObjectMultipart::fromAbortMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\AbortMultipartUploadRequest('bucket-123');
            $input = ObjectMultipart::fromAbortMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\AbortMultipartUploadRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromAbortMultipartUpload($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, uploadId", (string)$e);
        }

        try {
            $request = new Models\AbortMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****');
            $input = ObjectMultipart::fromAbortMultipartUpload($request);
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\AbortMultipartUploadRequest('bucket-123', 'key-123', '0004B9895DBBB6EC9****', 'requester');
            $input = ObjectMultipart::fromAbortMultipartUpload($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('0004B9895DBBB6EC9****', $input->getParameters()['uploadId']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, "should not here");
        }

    }

    public function testToAbortMultipartUpload()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toAbortMultipartUpload($output);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123'],
            );
            $result = ObjectMultipart::toAbortMultipartUpload($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

    }

    public function testFromListMultipartUploads()
    {
        try {
            $request = new Models\ListMultipartUploadsRequest();
            $input = ObjectMultipart::fromListMultipartUploads($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\ListMultipartUploadsRequest('bucket-123');
            $input = ObjectMultipart::fromListMultipartUploads($request);
            $this->assertEquals('bucket-123', $input->getBucket());
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\ListMultipartUploadsRequest('bucket-123', '/', Models\EncodeType::URL, '89F0105AA66942638E35300618DF5EE7', '89F0105AA66942638E35300618DF5EE7', 10, 'prefix', 'requester');
            $input = ObjectMultipart::fromListMultipartUploads($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('', $input->getParameters()['uploads']);
            $this->assertEquals('/', $input->getParameters()['delimiter']);
            $this->assertEquals('prefix', $input->getParameters()['prefix']);
            $this->assertEquals('89F0105AA66942638E35300618DF5EE7', $input->getParameters()['key-marker']);
            $this->assertEquals('89F0105AA66942638E35300618DF5EE7', $input->getParameters()['upload-id-marker']);
            $this->assertEquals('10', $input->getParameters()['max-uploads']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, "should not here");
        }

    }

    public function testToListMultipartUploads()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toListMultipartUploads($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <ListMultipartUploadsResult>', $e->getMessage());
        }

        // empty xml
        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
            );
            $result = ObjectMultipart::toListMultipartUploads($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <ListMultipartUploadsResult>', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListMultipartUploadsResult></ListMultipartUploadsResult>')
            );
            $result = ObjectMultipart::toListMultipartUploads($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertNull($result->bucket);
            $this->assertNull($result->prefix);
            $this->assertNull($result->keyMarker);
            $this->assertNull($result->uploadIdMarker);
            $this->assertNull($result->maxUploads);
            $this->assertNull($result->delimiter);
            $this->assertNull($result->isTruncated);
            $this->assertNull($result->nextKeyMarker);
            $this->assertNull($result->nextUploadIdMarker);
            $this->assertNull($result->uploads);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListMultipartUploadsResult>
    <Bucket>oss-example</Bucket>
    <KeyMarker></KeyMarker>
    <UploadIdMarker></UploadIdMarker>
    <NextKeyMarker>oss.avi</NextKeyMarker>
    <NextUploadIdMarker>0004B99B8E707874FC2D692FA5D77D3F</NextUploadIdMarker>
    <Delimiter></Delimiter>
    <Prefix></Prefix>
    <MaxUploads>1000</MaxUploads>
    <IsTruncated>false</IsTruncated>
    <Upload>
        <Key>multipart.data</Key>
        <UploadId>0004B999EF518A1FE585B0C9360DC4C8</UploadId>
        <Initiated>2012-02-23T04:18:23.000Z</Initiated>
    </Upload>
    <Upload>
        <Key>multipart.data</Key>
        <UploadId>0004B999EF5A239BB9138C6227D6****</UploadId>
        <Initiated>2012-02-23T04:18:23.000Z</Initiated>
    </Upload>
    <Upload>
        <Key>oss.avi</Key>
        <UploadId>0004B99B8E707874FC2D692FA5D7****</UploadId>
        <Initiated>2012-02-23T06:14:27.000Z</Initiated>
    </Upload>
</ListMultipartUploadsResult>')
            );
            $result = ObjectMultipart::toListMultipartUploads($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertEquals('oss-example', $result->bucket);
            $this->assertEquals('', $result->keyMarker);
            $this->assertEquals('', $result->uploadIdMarker);
            $this->assertEquals('oss.avi', $result->nextKeyMarker);
            $this->assertEquals('0004B99B8E707874FC2D692FA5D77D3F', $result->nextUploadIdMarker);
            $this->assertEquals('', $result->delimiter);
            $this->assertEquals('', $result->prefix);
            $this->assertEquals(1000, $result->maxUploads);
            $this->assertEquals(false, $result->isTruncated);
            $this->assertCount(3, $result->uploads);

            $this->assertEquals('multipart.data', $result->uploads[0]->key);
            $this->assertEquals('0004B999EF518A1FE585B0C9360DC4C8', $result->uploads[0]->uploadId);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2012-02-23T04:18:23.000Z',
                new \DateTimeZone('UTC')
            ), $result->uploads[0]->initiated);
        } catch (\Throwable $e) {
            printf($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListMultipartUploadsResult>
  <EncodingType>url</EncodingType>
  <Bucket>oss-example</Bucket>
  <KeyMarker></KeyMarker>
  <UploadIdMarker></UploadIdMarker>
  <NextKeyMarker>oss.avi</NextKeyMarker>
  <NextUploadIdMarker>89F0105AA66942638E35300618DF****</NextUploadIdMarker>
  <Delimiter></Delimiter>
  <Prefix></Prefix>
  <MaxUploads>1000</MaxUploads>
  <IsTruncated>false</IsTruncated>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>0214A87687F040F1BA4D83AB17C9****</UploadId>
    <Initiated>2023-11-22T05:45:57.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>3AE2ED7A60E04AFE9A5287055D37****</UploadId>
    <Initiated>2023-11-22T05:03:33.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>47E0E90F5DCB4AD5B3C4CD886CB0****</UploadId>
    <Initiated>2023-11-22T05:02:11.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>A89E0E28E2E948A1BFF6FD5CDAFF****</UploadId>
    <Initiated>2023-11-22T06:57:03.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>B18E1DCDB6964F5CB197F5F6B26A****</UploadId>
    <Initiated>2023-11-22T05:42:02.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>demo%2Fgp-%0C%0A%0B</Key>
    <UploadId>D4E111D4EA834F3ABCE4877B2779****</UploadId>
    <Initiated>2023-11-22T05:42:33.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>walker-dest.txt</Key>
    <UploadId>5209986C3A96486EA16B9C52C160****</UploadId>
    <Initiated>2023-11-21T08:34:47.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>walker-dest.txt</Key>
    <UploadId>63B652FA2C1342DCB3CCCC86D748****</UploadId>
    <Initiated>2023-11-21T08:28:46.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>walker-dest.txt</Key>
    <UploadId>6F67B34BCA3C481F887D73508A07****</UploadId>
    <Initiated>2023-11-21T08:32:12.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>walker-dest.txt</Key>
    <UploadId>89F0105AA66942638E35300618D****</UploadId>
    <Initiated>2023-11-21T08:37:53.000Z</Initiated>
  </Upload>
</ListMultipartUploadsResult>')
            );
            $result = ObjectMultipart::toListMultipartUploads($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertEquals('oss-example', $result->bucket);
            $this->assertEquals('', $result->keyMarker);
            $this->assertEquals('', $result->uploadIdMarker);
            $this->assertEquals('oss.avi', $result->nextKeyMarker);
            $this->assertEquals('89F0105AA66942638E35300618DF****', $result->nextUploadIdMarker);
            $this->assertEquals('', $result->delimiter);
            $this->assertEquals('', $result->prefix);
            $this->assertEquals(1000, $result->maxUploads);
            $this->assertEquals(false, $result->isTruncated);
            $this->assertCount(10, $result->uploads);
            $this->assertEquals("demo/gp-\f\n\v", $result->uploads[0]->key);
            $this->assertEquals('0214A87687F040F1BA4D83AB17C9****', $result->uploads[0]->uploadId);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2023-11-22T05:45:57.000Z',
                new \DateTimeZone('UTC')
            ), $result->uploads[0]->initiated);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

    }

    public function testFromListParts()
    {
        try {
            $request = new Models\ListPartsRequest();
            $input = ObjectMultipart::fromListParts($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\ListPartsRequest('bucket-123');
            $input = ObjectMultipart::fromListParts($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, key", (string)$e);
        }

        try {
            $request = new Models\ListPartsRequest('bucket-123', 'key-123');
            $input = ObjectMultipart::fromListParts($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, uploadId", (string)$e);
        }

        try {
            $request = new Models\ListPartsRequest('bucket-123', 'key-123', 'uploadId-123');
            $input = ObjectMultipart::fromListParts($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('uploadId-123', $input->getParameters()['uploadId']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

        try {
            $request = new Models\ListPartsRequest('bucket-123', 'key-123', '89F0105AA66942638E35300618DF5EE7', null, null, 10, 'requester');
            $input = ObjectMultipart::fromListParts($request);
            $this->assertEquals('bucket-123', $input->getBucket());
            $this->assertEquals('key-123', $input->getKey());
            $this->assertEquals('89F0105AA66942638E35300618DF5EE7', $input->getParameters()['uploadId']);
            $this->assertEquals('url', $input->getParameters()['encoding-type']);
            $this->assertEquals('10', $input->getParameters()['max-parts']);
            $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        } catch (\Throwable $e) {
            $this->assertTrue(false, "should not here");
        }

    }

    public function testToListParts()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = ObjectMultipart::toListParts($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <ListPartsResult>', $e->getMessage());
        }

        // empty xml
        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
            );
            $result = ObjectMultipart::toListParts($output);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Not found tag <ListPartsResult>', $e->getMessage());
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListPartsResult></ListPartsResult>')
            );
            $result = ObjectMultipart::toListParts($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertNull($result->bucket);
            $this->assertNull($result->key);
            $this->assertNull($result->uploadId);
            $this->assertNull($result->nextPartNumberMarker);
            $this->assertNull($result->maxParts);
            $this->assertNull($result->isTruncated);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListPartsResult>
    <Bucket>multipart_upload</Bucket>
    <Key>multipart.data</Key>
    <UploadId>0004B999EF5A239BB9138C6227D6****</UploadId>
    <NextPartNumberMarker>5</NextPartNumberMarker>
    <MaxParts>1000</MaxParts>
    <IsTruncated>false</IsTruncated>
    <Part>
        <PartNumber>1</PartNumber>
        <LastModified>2012-02-23T07:01:34.000Z</LastModified>
        <ETag>"3349DC700140D7F86A0784842780****"</ETag>
        <Size>6291456</Size>
    </Part>
    <Part>
        <PartNumber>2</PartNumber>
        <LastModified>2012-02-23T07:01:12.000Z</LastModified>
        <ETag>"3349DC700140D7F86A0784842780****"</ETag>
        <Size>6291456</Size>
    </Part>
    <Part>
        <PartNumber>5</PartNumber>
        <LastModified>2012-02-23T07:02:03.000Z</LastModified>
        <ETag>"7265F4D211B56873A381D321F586****"</ETag>
        <Size>1024</Size>
    </Part>
</ListPartsResult>')
            );
            $result = ObjectMultipart::toListParts($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertEquals('multipart_upload', $result->bucket);
            $this->assertEquals('multipart.data', $result->key);
            $this->assertNull($result->partNumberMarker);
            $this->assertNull($result->encodingType);
            $this->assertEquals('5', $result->nextPartNumberMarker);
            $this->assertEquals(false, $result->isTruncated);
            $this->assertEquals(1000, $result->maxParts);
            $this->assertCount(3, $result->parts);
            $this->assertEquals(1, $result->parts[0]->partNumber);
            $this->assertEquals('"3349DC700140D7F86A0784842780****"', $result->parts[0]->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2012-02-23T07:01:34.000Z',
                new \DateTimeZone('UTC')
            ), $result->parts[0]->lastModified);
            $this->assertEquals(6291456, $result->parts[0]->size);
        } catch (\Throwable $e) {
            printf($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }

        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'Content-Type' => 'application/xml'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<ListPartsResult>
  <EncodingType>url</EncodingType>
  <Bucket>oss-bucket</Bucket>
  <Key>demo%2Fgp-%0C%0A%0B</Key>
  <UploadId>D4E111D4EA834F3ABCE4877B2779****</UploadId>
  <StorageClass>Standard</StorageClass>
  <PartNumberMarker>0</PartNumberMarker>
  <NextPartNumberMarker>1</NextPartNumberMarker>
  <MaxParts>1000</MaxParts>
  <IsTruncated>false</IsTruncated>
  <Part>
    <PartNumber>1</PartNumber>
    <LastModified>2023-11-22T05:42:34.000Z</LastModified>
    <ETag>"CF3F46D505093571E916FCDD4967****"</ETag>
    <HashCrc64ecma>12066172435287683848</HashCrc64ecma>
    <Size>96316</Size>
  </Part>
</ListPartsResult>')
            );
            $result = ObjectMultipart::toListParts($output);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('123', $result->requestId);
            $this->assertEquals(2, count($result->headers));

            $this->assertEquals('oss-bucket', $result->bucket);
            $this->assertEquals(urldecode('demo%2Fgp-%0C%0A%0B'), $result->key);
            $this->assertEquals('0', $result->partNumberMarker);
            $this->assertEquals(Models\EncodeType::URL, $result->encodingType);
            $this->assertEquals(Models\StorageClassType::STANDARD, $result->storageClass);
            $this->assertEquals('1', $result->nextPartNumberMarker);
            $this->assertEquals(false, $result->isTruncated);
            $this->assertEquals(1000, $result->maxParts);
            $this->assertCount(1, $result->parts);
            $this->assertEquals(1, $result->parts[0]->partNumber);
            $this->assertEquals('"CF3F46D505093571E916FCDD4967****"', $result->parts[0]->etag);
            $this->assertEquals(\DateTime::createFromFormat(
                'Y-m-d\TH:i:s.000\Z',
                '2023-11-22T05:42:34.000Z',
                new \DateTimeZone('UTC')
            ), $result->parts[0]->lastModified);
            $this->assertEquals('12066172435287683848', $result->parts[0]->hashCrc64);
            $this->assertEquals(96316, $result->parts[0]->size);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            $this->assertTrue(false, 'should not here');
        }
    }

    private function cleanXml($xml)
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
