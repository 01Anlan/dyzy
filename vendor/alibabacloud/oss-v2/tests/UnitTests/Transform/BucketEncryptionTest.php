<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketEncryption;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketEncryptionTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketEncryption()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketEncryptionRequest();
            $input = BucketEncryption::fromPutBucketEncryption($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketEncryptionRequest('bucket-123');
            $input = BucketEncryption::fromPutBucketEncryption($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, serverSideEncryptionRule", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketEncryptionRequest('bucket-123', new Models\ServerSideEncryptionRule(
            new Models\ApplyServerSideEncryptionByDefault(
                sseAlgorithm: 'KMS',
                kmsDataEncryption: 'SM4'
            )));
        $input = BucketEncryption::fromPutBucketEncryption($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ServerSideEncryptionRule><ApplyServerSideEncryptionByDefault><SSEAlgorithm>KMS</SSEAlgorithm><KMSDataEncryption>SM4</KMSDataEncryption></ApplyServerSideEncryptionByDefault></ServerSideEncryptionRule>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketEncryptionRequest('bucket-123', new Models\ServerSideEncryptionRule(
            new Models\ApplyServerSideEncryptionByDefault(
                sseAlgorithm: 'KMS',
                kmsMasterKeyID: '9468da86-3509-4f8d-a61e-6eab1eac****',
                kmsDataEncryption: 'SM4'
            )));
        $input = BucketEncryption::fromPutBucketEncryption($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ServerSideEncryptionRule><ApplyServerSideEncryptionByDefault><SSEAlgorithm>KMS</SSEAlgorithm><KMSMasterKeyID>9468da86-3509-4f8d-a61e-6eab1eac****</KMSMasterKeyID><KMSDataEncryption>SM4</KMSDataEncryption></ApplyServerSideEncryptionByDefault></ServerSideEncryptionRule>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo3
        $request = new Models\PutBucketEncryptionRequest('bucket-123', new Models\ServerSideEncryptionRule(
            new Models\ApplyServerSideEncryptionByDefault(
                sseAlgorithm: 'AES256',
            )));
        $input = BucketEncryption::fromPutBucketEncryption($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ServerSideEncryptionRule><ApplyServerSideEncryptionByDefault><SSEAlgorithm>AES256</SSEAlgorithm></ApplyServerSideEncryptionByDefault></ServerSideEncryptionRule>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketEncryption()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketEncryption::toPutBucketEncryption($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketEncryption::toPutBucketEncryption($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketEncryption()
    {
        // miss required field
        try {
            $request = new Models\GetBucketEncryptionRequest();
            $input = BucketEncryption::fromGetBucketEncryption($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketEncryptionRequest('bucket-123');
        $input = BucketEncryption::fromGetBucketEncryption($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketEncryption()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketEncryption::toGetBucketEncryption($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ServerSideEncryptionRule>
  <ApplyServerSideEncryptionByDefault>
    <SSEAlgorithm>KMS</SSEAlgorithm>
    <KMSMasterKeyID>9468da86-3509-4f8d-a61e-6eab1eac****</KMSMasterKeyID>
  </ApplyServerSideEncryptionByDefault>
</ServerSideEncryptionRule>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketEncryption::toGetBucketEncryption($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->kmsMasterKeyID);
        $this->assertEquals('KMS', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->sseAlgorithm);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ServerSideEncryptionRule>
  <ApplyServerSideEncryptionByDefault>
    <SSEAlgorithm>KMS</SSEAlgorithm>
    <KMSDataEncryption>SM4</KMSDataEncryption>
    <KMSMasterKeyID>9468da86-3509-4f8d-a61e-6eab1eac****</KMSMasterKeyID>
  </ApplyServerSideEncryptionByDefault>
</ServerSideEncryptionRule>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketEncryption::toGetBucketEncryption($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('9468da86-3509-4f8d-a61e-6eab1eac****', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->kmsMasterKeyID);
        $this->assertEquals('KMS', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->sseAlgorithm);
        $this->assertEquals('SM4', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->kmsDataEncryption);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ServerSideEncryptionRule>
  <ApplyServerSideEncryptionByDefault>
    <SSEAlgorithm>AES256</SSEAlgorithm>
  </ApplyServerSideEncryptionByDefault>
</ServerSideEncryptionRule>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketEncryption::toGetBucketEncryption($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('AES256', $result->serverSideEncryptionRule->applyServerSideEncryptionByDefault->sseAlgorithm);
    }

    public function testFromDeleteBucketEncryption()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketEncryptionRequest();
            $input = BucketEncryption::fromDeleteBucketEncryption($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketEncryptionRequest('bucket-123');
        $input = BucketEncryption::fromDeleteBucketEncryption($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketEncryption()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketEncryption::toDeleteBucketEncryption($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketEncryption::toDeleteBucketEncryption($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
