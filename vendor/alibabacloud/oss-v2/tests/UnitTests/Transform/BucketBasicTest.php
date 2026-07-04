<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Exception\DeserializationExecption;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketBasic;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Exception;
use AlibabaCloud\Oss\V2\Utils;

class BucketBasicTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucket()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketRequest();
            $input = BucketBasic::fromPutBucket($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\PutBucketRequest('bucket-123');
        $input = BucketBasic::fromPutBucket($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // all settings
        $request = new Models\PutBucketRequest(
            'bucket-123',
            Models\BucketACLType::PRIVATE,
            'rg-123',
            new Models\CreateBucketConfiguration(
                Models\StorageClassType::IA,
                Models\DataRedundancyType::LRS
            )
        );
        $createXml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CreateBucketConfiguration><StorageClass>IA</StorageClass><DataRedundancyType>LRS</DataRedundancyType></CreateBucketConfiguration>
BBB;

        $input = BucketBasic::fromPutBucket($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $str = $input->getBody()->getContents();
        $this->assertEquals(base64_encode(md5($str, true)), $input->getHeaders()['content-md5']);
        $this->assertEquals(Models\BucketACLType::PRIVATE, $input->getHeaders()['x-oss-acl']);
        $this->assertEquals('rg-123', $input->getHeaders()['x-oss-resource-group-id']);
        $this->assertEquals($createXml, $this->cleanXml($str));
        $this->assertStringContainsString('<CreateBucketConfiguration>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(2, $xml->count());
        $this->assertEquals('IA', $xml->StorageClass);
        $this->assertEquals('LRS', $xml->DataRedundancyType);

        // extend header & parameters
        $request = new Models\PutBucketRequest(
            'bucket-123',
            null,
            null,
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromPutBucket($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToPutBucket()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketBasic::toPutBucket($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test']
        );
        $result = BucketBasic::toPutBucket($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromDeleteBucket()
    {
        // miss required field 
        try {
            $request = new Models\DeleteBucketRequest();
            $input = BucketBasic::fromDeleteBucket($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\DeleteBucketRequest('bucket-123');
        $input = BucketBasic::fromDeleteBucket($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\DeleteBucketRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromDeleteBucket($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToDeleteBucket()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketBasic::toDeleteBucket($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123', 'content-type' => 'test']
        );
        $result = BucketBasic::toDeleteBucket($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketInfo()
    {
        // miss required field 
        try {
            $request = new Models\GetBucketInfoRequest();
            $input = BucketBasic::fromGetBucketInfo($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\GetBucketInfoRequest('bucket-123');
        $input = BucketBasic::fromGetBucketInfo($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\GetBucketInfoRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromGetBucketInfo($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToGetBucketInfo()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toGetBucketInfo($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <BucketInfo>', $e);
        }

        // xml invalid
        try {
            $output = new OperationOutput(
                'OK',
                200,
                ['x-oss-request-id' => '123', 'content-type' => 'test'],
                Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?><BucketInfo></BucketInfo>')
            );
            $result = BucketBasic::toGetBucketInfo($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <Bucket>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketInfo>
            <Bucket>
            </Bucket>
            </BucketInfo>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketInfo($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNotEmpty($result->bucketInfo);
        $this->assertNull($result->bucketInfo->name);
        $this->assertNull($result->bucketInfo->accessMonitor);
        $this->assertNull($result->bucketInfo->location);
        $this->assertNull($result->bucketInfo->creationDate);
        $this->assertNull($result->bucketInfo->extranetEndpoint);
        $this->assertNull($result->bucketInfo->intranetEndpoint);
        $this->assertNull($result->bucketInfo->acl);
        $this->assertNull($result->bucketInfo->dataRedundancyType);
        $this->assertNull($result->bucketInfo->owner);
        $this->assertNull($result->bucketInfo->storageClass);
        $this->assertNull($result->bucketInfo->resourceGroupId);
        $this->assertNull($result->bucketInfo->sseRule);
        $this->assertNull($result->bucketInfo->versioning);
        $this->assertNull($result->bucketInfo->transferAcceleration);
        $this->assertNull($result->bucketInfo->crossRegionReplication);
        $this->assertNull($result->bucketInfo->comment);
        $this->assertNull($result->bucketInfo->blockPublicAccess);


        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketInfo>
            <Bucket>
                <AccessMonitor>Enabled</AccessMonitor>
                <CreationDate>2023-12-17T03:30:09.000Z</CreationDate>
                <ExtranetEndpoint>oss-cn-hangzhou.aliyuncs.com</ExtranetEndpoint>
                <IntranetEndpoint>oss-cn-hangzhou-internal.aliyuncs.com</IntranetEndpoint>
                <Location>oss-cn-hangzhou</Location>
                <StorageClass>Standard</StorageClass>
                <TransferAcceleration>Disabled</TransferAcceleration>
                <CrossRegionReplication>Disabled</CrossRegionReplication>
                <DataRedundancyType>LRS</DataRedundancyType>
                <Name>oss-example</Name>
                <ResourceGroupId>rg-aek27tc********</ResourceGroupId>
                <Owner>
                    <DisplayName>username</DisplayName>
                    <ID>27183473914****</ID>
                </Owner>
                <AccessControlList>
                    <Grant>private</Grant>
                </AccessControlList>  
                <Versioning>Suspended</Versioning>
                <Comment>test</Comment>
               <ServerSideEncryptionRule>
                    <SSEAlgorithm>KMS</SSEAlgorithm>
                    <KMSMasterKeyID>123</KMSMasterKeyID>
                </ServerSideEncryptionRule>                
                <BucketPolicy>
                    <LogBucket>examplebucket</LogBucket>
                    <LogPrefix>log/</LogPrefix>
                </BucketPolicy>
                <BlockPublicAccess>true</BlockPublicAccess>
            </Bucket>
            </BucketInfo>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketInfo($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNotEmpty($result->bucketInfo);

        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $this->assertEquals('oss-example', $result->bucketInfo->name);
        $this->assertEquals('Enabled', $result->bucketInfo->accessMonitor);
        $this->assertEquals('oss-cn-hangzhou', $result->bucketInfo->location);
        $this->assertEquals($datetimeUtc, $result->bucketInfo->creationDate);
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $result->bucketInfo->extranetEndpoint);
        $this->assertEquals('oss-cn-hangzhou-internal.aliyuncs.com', $result->bucketInfo->intranetEndpoint);
        $this->assertEquals('private', $result->bucketInfo->acl);
        $this->assertEquals('LRS', $result->bucketInfo->dataRedundancyType);
        $this->assertEquals('27183473914****', $result->bucketInfo->owner->id);
        $this->assertEquals('username', $result->bucketInfo->owner->displayName);
        $this->assertEquals('Standard', $result->bucketInfo->storageClass);
        $this->assertEquals('rg-aek27tc********', $result->bucketInfo->resourceGroupId);
        $this->assertEquals('KMS', $result->bucketInfo->sseRule->sseAlgorithm);
        $this->assertEquals('123', $result->bucketInfo->sseRule->kmsMasterKeyId);
        $this->assertNull($result->bucketInfo->sseRule->kmsDataEncryption);
        $this->assertEquals('Suspended', $result->bucketInfo->versioning);
        $this->assertEquals('Disabled', $result->bucketInfo->transferAcceleration);
        $this->assertEquals('Disabled', $result->bucketInfo->crossRegionReplication);

        $this->assertEquals('test', $result->bucketInfo->comment);
        $this->assertEquals('examplebucket', $result->bucketInfo->bucketPolicy->logBucket);
        $this->assertEquals('log/', $result->bucketInfo->bucketPolicy->logPrefix);
        $this->assertEquals(true, $result->bucketInfo->blockPublicAccess);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketInfo>
            <Bucket>
                <AccessMonitor></AccessMonitor>
                <CreationDate></CreationDate>
                <ExtranetEndpoint></ExtranetEndpoint>
                <IntranetEndpoint></IntranetEndpoint>
                <Location></Location>
                <StorageClass></StorageClass>
                <TransferAcceleration></TransferAcceleration>
                <CrossRegionReplication></CrossRegionReplication>
                <DataRedundancyType></DataRedundancyType>
                <Name></Name>
                <ResourceGroupId></ResourceGroupId>
                <Owner>
                    <DisplayName></DisplayName>
                    <ID></ID>
                </Owner>
                <AccessControlList>
                    <Grant></Grant>
                </AccessControlList>  
                <Versioning></Versioning>
                <Comment></Comment>
               <ServerSideEncryptionRule>
                    <SSEAlgorithm></SSEAlgorithm>
                    <KMSMasterKeyID>/</KMSMasterKeyID>
                </ServerSideEncryptionRule>                
                <BucketPolicy>
                    <LogBucket></LogBucket>
                    <LogPrefix>/</LogPrefix>
                </BucketPolicy>
                <BlockPublicAccess></BlockPublicAccess>
            </Bucket>
            </BucketInfo>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketInfo($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNotEmpty($result->bucketInfo);
        $this->assertEquals('', $result->bucketInfo->name);
        $this->assertEquals('', $result->bucketInfo->accessMonitor);
        $this->assertEquals('', $result->bucketInfo->location);
    }

    public function testFromGetBucketLocation()
    {
        // miss required field 
        try {
            $request = new Models\GetBucketLocationRequest();
            $input = BucketBasic::fromGetBucketLocation($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\GetBucketLocationRequest('bucket-123');
        $input = BucketBasic::fromGetBucketLocation($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\GetBucketLocationRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromGetBucketLocation($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToGetBucketLocation()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toGetBucketLocation($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <LocationConstraint>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <LocationConstraint></LocationConstraint>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketLocation($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('', $result->location);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <LocationConstraint>oss-cn-hangzhou</LocationConstraint>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketLocation($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('oss-cn-hangzhou', $result->location);
    }

    public function testFromGetBucketStat()
    {
        // miss required field 
        try {
            $request = new Models\GetBucketStatRequest();
            $input = BucketBasic::fromGetBucketStat($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\GetBucketStatRequest('bucket-123');
        $input = BucketBasic::fromGetBucketStat($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\GetBucketStatRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromGetBucketStat($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToGetBucketStat()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toGetBucketStat($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <BucketStat>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketStat></BucketStat>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketStat($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->storage);
        $this->assertNull($result->objectCount);
        $this->assertNull($result->multipartUploadCount);
        $this->assertNull($result->liveChannelCount);
        $this->assertNull($result->lastModifiedTime);
        $this->assertNull($result->standardStorage);
        $this->assertNull($result->standardObjectCount);
        $this->assertNull($result->infrequentAccessStorage);
        $this->assertNull($result->infrequentAccessRealStorage);
        $this->assertNull($result->infrequentAccessObjectCount);
        $this->assertNull($result->archiveStorage);
        $this->assertNull($result->archiveRealStorage);
        $this->assertNull($result->archiveObjectCount);
        $this->assertNull($result->coldArchiveStorage);
        $this->assertNull($result->coldArchiveRealStorage);
        $this->assertNull($result->coldArchiveObjectCount);
        $this->assertNull($result->deepColdArchiveStorage);
        $this->assertNull($result->deepColdArchiveRealStorage);
        $this->assertNull($result->deepColdArchiveObjectCount);
        $this->assertNull($result->deleteMarkerCount);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketStat>
                <Storage>1600</Storage>
                <ObjectCount>230</ObjectCount>
                <MultipartUploadCount>40</MultipartUploadCount>
                <LiveChannelCount>4</LiveChannelCount>
                <LastModifiedTime>1643341269</LastModifiedTime>
                <StandardStorage>430</StandardStorage>
                <StandardObjectCount>66</StandardObjectCount>
                <InfrequentAccessStorage>2359296</InfrequentAccessStorage>
                <InfrequentAccessRealStorage>360</InfrequentAccessRealStorage>
                <InfrequentAccessObjectCount>54</InfrequentAccessObjectCount>
                <ArchiveStorage>2949120</ArchiveStorage>
                <ArchiveRealStorage>450</ArchiveRealStorage>
                <ArchiveObjectCount>74</ArchiveObjectCount>
                <ColdArchiveStorage>2359296</ColdArchiveStorage>
                <ColdArchiveRealStorage>360</ColdArchiveRealStorage>
                <ColdArchiveObjectCount>36</ColdArchiveObjectCount>
                <DeepColdArchiveStorage>235929</DeepColdArchiveStorage>
                <DeepColdArchiveRealStorage>30</DeepColdArchiveRealStorage>
                <DeepColdArchiveObjectCount>361</DeepColdArchiveObjectCount>                
                <DeleteMarkerCount>1361</DeleteMarkerCount>                
            </BucketStat>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketStat($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1600, $result->storage);
        $this->assertEquals(230, $result->objectCount);
        $this->assertEquals(40, $result->multipartUploadCount);
        $this->assertEquals(4, $result->liveChannelCount);
        $this->assertEquals(1643341269, $result->lastModifiedTime);
        $this->assertEquals(430, $result->standardStorage);
        $this->assertEquals(66, $result->standardObjectCount);
        $this->assertEquals(2359296, $result->infrequentAccessStorage);
        $this->assertEquals(360, $result->infrequentAccessRealStorage);
        $this->assertEquals(54, $result->infrequentAccessObjectCount);
        $this->assertEquals(2949120, $result->archiveStorage);
        $this->assertEquals(450, $result->archiveRealStorage);
        $this->assertEquals(74, $result->archiveObjectCount);
        $this->assertEquals(2359296, $result->coldArchiveStorage);
        $this->assertEquals(360, $result->coldArchiveRealStorage);
        $this->assertEquals(36, $result->coldArchiveObjectCount);
        $this->assertEquals(235929, $result->deepColdArchiveStorage);
        $this->assertEquals(30, $result->deepColdArchiveRealStorage);
        $this->assertEquals(361, $result->deepColdArchiveObjectCount);
        $this->assertEquals(1361, $result->deleteMarkerCount);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <BucketStat>
                <Storage></Storage>
                <ObjectCount></ObjectCount>
                <MultipartUploadCount></MultipartUploadCount>
                <LiveChannelCount></LiveChannelCount>
                <LastModifiedTime></LastModifiedTime>
                <StandardStorage></StandardStorage>
                <StandardObjectCount></StandardObjectCount>
                <InfrequentAccessStorage></InfrequentAccessStorage>
                <InfrequentAccessRealStorage></InfrequentAccessRealStorage>
                <InfrequentAccessObjectCount></InfrequentAccessObjectCount>
                <ArchiveStorage></ArchiveStorage>
                <ArchiveRealStorage></ArchiveRealStorage>
                <ArchiveObjectCount></ArchiveObjectCount>
                <ColdArchiveStorage></ColdArchiveStorage>
                <ColdArchiveRealStorage></ColdArchiveRealStorage>
                <ColdArchiveObjectCount></ColdArchiveObjectCount>
                <DeepColdArchiveStorage></DeepColdArchiveStorage>
                <DeepColdArchiveRealStorage></DeepColdArchiveRealStorage>
                <DeepColdArchiveObjectCount></DeepColdArchiveObjectCount>                
                <DeleteMarkerCount></DeleteMarkerCount>                
            </BucketStat>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketStat($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(0, $result->storage);
        $this->assertEquals(0, $result->objectCount);
        $this->assertEquals(0, $result->multipartUploadCount);
        $this->assertEquals(0, $result->liveChannelCount);
        $this->assertEquals(0, $result->lastModifiedTime);
        $this->assertEquals(0, $result->standardStorage);
        $this->assertEquals(0, $result->standardObjectCount);
        $this->assertEquals(0, $result->infrequentAccessStorage);
        $this->assertEquals(0, $result->infrequentAccessRealStorage);
        $this->assertEquals(0, $result->infrequentAccessObjectCount);
        $this->assertEquals(0, $result->archiveStorage);
        $this->assertEquals(0, $result->archiveRealStorage);
        $this->assertEquals(0, $result->archiveObjectCount);
        $this->assertEquals(0, $result->coldArchiveStorage);
        $this->assertEquals(0, $result->coldArchiveRealStorage);
        $this->assertEquals(0, $result->coldArchiveObjectCount);
        $this->assertEquals(0, $result->deepColdArchiveStorage);
        $this->assertEquals(0, $result->deepColdArchiveRealStorage);
        $this->assertEquals(0, $result->deepColdArchiveObjectCount);
        $this->assertEquals(0, $result->deleteMarkerCount);
    }

    public function testFromGetBucketVersioning()
    {
        // miss required field 
        try {
            $request = new Models\GetBucketVersioningRequest();
            $input = BucketBasic::fromGetBucketVersioning($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\GetBucketVersioningRequest('bucket-123');
        $input = BucketBasic::fromGetBucketVersioning($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\GetBucketVersioningRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromGetBucketVersioning($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToGetBucketVersioning()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toGetBucketVersioning($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <VersioningConfiguration>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <VersioningConfiguration></VersioningConfiguration>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketVersioning($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNotNull($result->versioningConfiguration);
        $this->assertNull($result->versioningConfiguration->status);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <VersioningConfiguration>
                <Status>Enabled</Status>
            </VersioningConfiguration>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketVersioning($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(Models\BucketVersioningStatusType::ENABLED, $result->versioningConfiguration->status);


        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <VersioningConfiguration>
                <Status></Status>
            </VersioningConfiguration>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketVersioning($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('', $result->versioningConfiguration->status);
    }

    public function testFromPutBucketVersioning()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketVersioningRequest();
            $input = BucketBasic::fromPutBucketVersioning($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\PutBucketVersioningRequest('bucket-123');
        $input = BucketBasic::fromPutBucketVersioning($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // all settings
        $request = new Models\PutBucketVersioningRequest(
            'bucket-123',
            new Models\VersioningConfiguration('Enabled'),
        );
        $input = BucketBasic::fromPutBucketVersioning($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $str = $input->getBody()->getContents();
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals(base64_encode(md5($str, true)), $input->getHeaders()['content-md5']);
        $createXml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><VersioningConfiguration><Status>Enabled</Status></VersioningConfiguration>
BBB;
        $this->assertEquals($createXml, $this->cleanXml($str));

        $this->assertStringContainsString('<VersioningConfiguration>', $str);
        $xml = \simplexml_load_string($str);
        $this->assertEquals(1, $xml->count());
        $this->assertEquals('Enabled', $xml->Status);

        // extend header & parameters
        $request = new Models\PutBucketVersioningRequest(
            'bucket-123',
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromPutBucketVersioning($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToPutBucketVersioning()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketBasic::toPutBucketVersioning($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test']
        );
        $result = BucketBasic::toPutBucketVersioning($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromPutBucketAcl()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketAclRequest();
            $input = BucketBasic::fromPutBucketAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        try {
            $request = new Models\PutBucketAclRequest('bucket-123');
            $input = BucketBasic::fromPutBucketAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, acl", (string)$e);
        }

        // all settings
        $request = new Models\PutBucketAclRequest(
            'bucket-123',
            Models\BucketACLType::PRIVATE,
        );
        $input = BucketBasic::fromPutBucketAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals(Models\BucketACLType::PRIVATE, $input->getHeaders()['x-oss-acl']);

        // extend header & parameters
        $request = new Models\PutBucketAclRequest(
            'bucket-123',
            Models\BucketACLType::PUBLIC_READ,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromPutBucketAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
        $this->assertEquals(Models\BucketACLType::PUBLIC_READ, $input->getHeaders()['x-oss-acl']);
    }

    public function testToPutBucketAcl()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketBasic::toPutBucketAcl($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test']
        );
        $result = BucketBasic::toPutBucketAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketAcl()
    {
        // miss required field 
        try {
            $request = new Models\GetBucketAclRequest();
            $input = BucketBasic::fromGetBucketAcl($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\GetBucketAclRequest('bucket-123');
        $input = BucketBasic::fromGetBucketAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // extend header & parameters
        $request = new Models\GetBucketAclRequest(
            'bucket-123',
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromGetBucketAcl($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/xml', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToGetBucketAcl()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toGetBucketAcl($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <AccessControlPolicy>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <AccessControlPolicy></AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->owner);
        $this->assertNull($result->accessControlList);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <AccessControlPolicy>
                <Owner>
                    <ID>0022012****</ID>
                    <DisplayName>user_example</DisplayName>
                </Owner>
                <AccessControlList>
                    <Grant>public-read</Grant>
                </AccessControlList>
            </AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('0022012****', $result->owner->id);
        $this->assertEquals('user_example', $result->owner->displayName);
        $this->assertEquals(Models\BucketACLType::PUBLIC_READ, $result->accessControlList->grant);


        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <AccessControlPolicy>
                <Owner>
                </Owner>
                <AccessControlList>
                </AccessControlList>
            </AccessControlPolicy>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toGetBucketAcl($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->owner->id);
        $this->assertNull($result->owner->displayName);
        $this->assertNull($result->accessControlList->grant);
    }

    public function testFromListObjects()
    {
        // miss required field 
        try {
            $request = new Models\ListObjectsRequest();
            $input = BucketBasic::fromListObjects($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\ListObjectsRequest('bucket-123');
        $input = BucketBasic::fromListObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // all settings
        $request = new Models\ListObjectsRequest(
            'bucket-123',
            'deli-123',
            'url',
            'marker-123',
            123,
            'prefix-123',
            'requester'
        );
        $input = BucketBasic::fromListObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('deli-123', $input->getParameters()['delimiter']);
        $this->assertEquals('marker-123', $input->getParameters()['marker']);
        $this->assertEquals('url', $input->getParameters()['encoding-type']);
        $this->assertEquals('123', $input->getParameters()['max-keys']);
        $this->assertEquals('prefix-123', $input->getParameters()['prefix']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        // extend header & parameters
        $request = new Models\ListObjectsRequest(
            'bucket-123',
            null,
            null,
            null,
            null,
            null,
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromListObjects($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToListObjects()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toListObjects($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <ListBucketResult>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult></ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->name);
        $this->assertNull($result->prefix);
        $this->assertNull($result->marker);
        $this->assertNull($result->maxKeys);
        $this->assertNull($result->delimiter);
        $this->assertNull($result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertNull($result->nextMarker);
        $this->assertNull($result->contents);
        $this->assertNull($result->commonPrefixes);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name>examplebucket</Name>
            <Prefix>fun/</Prefix>
            <Marker>fu</Marker>
            <MaxKeys>2</MaxKeys>
            <Delimiter>/</Delimiter>
            <IsTruncated>true</IsTruncated>
            <NextMarker>fun/test-1.jpg</NextMarker>
            <Contents>
                <Key>fun/test.jpg</Key>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID>0022012****</ID>
                    <DisplayName>user_example</DisplayName>
                </Owner>
                <RestoreInfo>ongoing-request="true"</RestoreInfo>
            </Contents>
            <Contents>
                <Key>fun/test-1.jpg</Key>
                <LastModified>2023-12-17T03:30:19.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344605</Size>
                <StorageClass>IA</StorageClass>
                <Owner>
                </Owner>
            </Contents>            
            <CommonPrefixes>
                <Prefix>fun/movie/</Prefix>
            </CommonPrefixes>
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fu', $result->marker);
        $this->assertEquals(2, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(true, $result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertEquals('fun/test-1.jpg', $result->nextMarker);
        $this->assertEquals(2, count($result->contents));
        $this->assertEquals('fun/test.jpg', $result->contents[0]->key);
        $this->assertEquals($datetimeUtc, $result->contents[0]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->contents[0]->etag);
        $this->assertEquals('Normal', $result->contents[0]->type);
        $this->assertEquals(344606, $result->contents[0]->size);
        $this->assertEquals('Standard', $result->contents[0]->storageClass);
        $this->assertEquals('0022012****', $result->contents[0]->owner->id);
        $this->assertEquals('user_example', $result->contents[0]->owner->displayName);
        $this->assertEquals('ongoing-request="true"', $result->contents[0]->restoreInfo);

        $this->assertEquals('fun/test-1.jpg', $result->contents[1]->key);
        $this->assertEquals($datetimeUtc1, $result->contents[1]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->contents[1]->etag);
        $this->assertEquals('Normal', $result->contents[1]->type);
        $this->assertEquals(344605, $result->contents[1]->size);
        $this->assertEquals('IA', $result->contents[1]->storageClass);
        $this->assertNull($result->contents[1]->owner->id);
        $this->assertNull($result->contents[1]->owner->displayName);

        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // encodingType
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name>examplebucket</Name>
            <Prefix>fun%2F</Prefix>
            <Marker>fun%2F</Marker>
            <MaxKeys>1</MaxKeys>
            <Delimiter>%2F</Delimiter>
            <IsTruncated>false</IsTruncated>
            <NextMarker>fun%2Ftest-1.jpg</NextMarker>
            <EncodingType>url</EncodingType>
            <Contents>
                <Key>fun%2Ftest.jpg</Key>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
                <TransitionTime>2023-12-17T03:30:19.000Z</TransitionTime>
            </Contents>
            <CommonPrefixes>
                <Prefix>fun%2Fmovie%2F</Prefix>
            </CommonPrefixes>
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fun/', $result->marker);
        $this->assertEquals(1, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('url', $result->encodingType);
        $this->assertEquals('fun/test-1.jpg', $result->nextMarker);
        $this->assertEquals(1, count($result->contents));
        $this->assertEquals('fun/test.jpg', $result->contents[0]->key);
        $this->assertEquals('', $result->contents[0]->owner->id);
        $this->assertEquals('', $result->contents[0]->owner->displayName);
        $this->assertEquals($datetimeUtc1, $result->contents[0]->transitionTime);
        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name></Name>
            <Prefix></Prefix>
            <Marker></Marker>
            <MaxKeys></MaxKeys>
            <Delimiter></Delimiter>
            <IsTruncated></IsTruncated>
            <NextMarker></NextMarker>
            <Contents>
                <Key></Key>
                <LastModified></LastModified>
                <ETag></ETag>
                <Type></Type>
                <Size></Size>
                <StorageClass></StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
            </Contents>
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjects($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('', $result->name);
        $this->assertEquals('', $result->prefix);
        $this->assertEquals('', $result->marker);
        $this->assertEquals(0, $result->maxKeys);
        $this->assertEquals('', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('', $result->encodingType);
        $this->assertEquals('', $result->nextMarker);
        $this->assertEquals(1, count($result->contents));
        $this->assertEquals('', $result->contents[0]->key);
        $this->assertNull($result->contents[0]->lastModified);
        $this->assertEquals('', $result->contents[0]->etag);
        $this->assertEquals('', $result->contents[0]->type);
        $this->assertEquals(0, $result->contents[0]->size);
        $this->assertEquals('', $result->contents[0]->storageClass);
        $this->assertEquals('', $result->contents[0]->owner->id);
        $this->assertEquals('', $result->contents[0]->owner->displayName);
        $this->assertEquals(2, count($result->commonPrefixes));
        $this->assertEquals('', $result->commonPrefixes[0]->prefix);
        $this->assertEquals('', $result->commonPrefixes[1]->prefix);
    }

    public function testFromListObjectsV2()
    {
        // miss required field 
        try {
            $request = new Models\ListObjectsV2Request();
            $input = BucketBasic::fromListObjectsV2($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\ListObjectsV2Request('bucket-123');
        $input = BucketBasic::fromListObjectsV2($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // all settings
        $request = new Models\ListObjectsV2Request(
            'bucket-123',
            'deli-123',
            'url',
            'start-after-123',
            'token-123',
            123,
            'prefix-123',
            'requester',
            true,
        );
        $input = BucketBasic::fromListObjectsV2($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('2', $input->getParameters()['list-type']);
        $this->assertEquals('deli-123', $input->getParameters()['delimiter']);
        $this->assertEquals('url', $input->getParameters()['encoding-type']);
        $this->assertEquals('start-after-123', $input->getParameters()['start-after']);
        $this->assertEquals('token-123', $input->getParameters()['continuation-token']);
        $this->assertEquals('123', $input->getParameters()['max-keys']);
        $this->assertEquals('prefix-123', $input->getParameters()['prefix']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);
        $this->assertEquals('true', $input->getParameters()['fetch-owner']);

        // extend header & parameters
        $request = new Models\ListObjectsV2Request(
            'bucket-123',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromListObjectsV2($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToListObjectsV2()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toListObjectsV2($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <ListBucketResult>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult></ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjectsV2($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->name);
        $this->assertNull($result->prefix);
        $this->assertNull($result->startAfter);
        $this->assertNull($result->continuationToken);
        $this->assertNull($result->maxKeys);
        $this->assertNull($result->delimiter);
        $this->assertNull($result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertNull($result->nextContinuationToken);
        $this->assertNull($result->keyCount);
        $this->assertNull($result->contents);
        $this->assertNull($result->commonPrefixes);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name>examplebucket</Name>
            <Prefix>fun/</Prefix>
            <StartAfter>fu</StartAfter>
            <ContinuationToken>AgJiYw--</ContinuationToken>
            <MaxKeys>2</MaxKeys>
            <Delimiter>/</Delimiter>
            <IsTruncated>true</IsTruncated>
            <NextContinuationToken>CgJiYw--</NextContinuationToken>
            <Contents>
                <Key>fun/test.jpg</Key>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID>0022012****</ID>
                    <DisplayName>user_example</DisplayName>
                </Owner>
                <RestoreInfo>ongoing-request="true"</RestoreInfo>
            </Contents>
            <Contents>
                <Key>fun/test-1.jpg</Key>
                <LastModified>2023-12-17T03:30:19.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344605</Size>
                <StorageClass>IA</StorageClass>
                <Owner>
                </Owner>
            </Contents>            
            <CommonPrefixes>
                <Prefix>fun/movie/</Prefix>
            </CommonPrefixes>
            <KeyCount>3</KeyCount>            
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjectsV2($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fu', $result->startAfter);
        $this->assertEquals('AgJiYw--', $result->continuationToken);
        $this->assertEquals(2, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(true, $result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertEquals('CgJiYw--', $result->nextContinuationToken);
        $this->assertEquals(3, $result->keyCount);
        $this->assertEquals(2, count($result->contents));
        $this->assertEquals('fun/test.jpg', $result->contents[0]->key);
        $this->assertEquals($datetimeUtc, $result->contents[0]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->contents[0]->etag);
        $this->assertEquals('Normal', $result->contents[0]->type);
        $this->assertEquals(344606, $result->contents[0]->size);
        $this->assertEquals('Standard', $result->contents[0]->storageClass);
        $this->assertEquals('0022012****', $result->contents[0]->owner->id);
        $this->assertEquals('user_example', $result->contents[0]->owner->displayName);
        $this->assertEquals('ongoing-request="true"', $result->contents[0]->restoreInfo);

        $this->assertEquals('fun/test-1.jpg', $result->contents[1]->key);
        $this->assertEquals($datetimeUtc1, $result->contents[1]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->contents[1]->etag);
        $this->assertEquals('Normal', $result->contents[1]->type);
        $this->assertEquals(344605, $result->contents[1]->size);
        $this->assertEquals('IA', $result->contents[1]->storageClass);
        $this->assertNull($result->contents[1]->owner->id);
        $this->assertNull($result->contents[1]->owner->displayName);

        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // encodingType
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name>examplebucket</Name>
            <Prefix>fun%2F</Prefix>
            <StartAfter>fun%2F</StartAfter>
            <ContinuationToken>fun%2F</ContinuationToken>
            <MaxKeys>1</MaxKeys>
            <Delimiter>%2F</Delimiter>
            <IsTruncated>false</IsTruncated>
            <NextContinuationToken>fun%2Ftest-1.jpg</NextContinuationToken>
            <EncodingType>url</EncodingType>
            <Contents>
                <Key>fun%2Ftest.jpg</Key>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
                <TransitionTime>2023-12-17T03:30:19.000Z</TransitionTime>
            </Contents>
            <CommonPrefixes>
                <Prefix>fun%2Fmovie%2F</Prefix>
            </CommonPrefixes>
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjectsV2($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fun/', $result->startAfter);
        $this->assertEquals('fun/', $result->continuationToken);
        $this->assertEquals(1, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('url', $result->encodingType);
        $this->assertEquals('fun/test-1.jpg', $result->nextContinuationToken);
        $this->assertEquals(1, count($result->contents));
        $this->assertEquals('fun/test.jpg', $result->contents[0]->key);
        $this->assertEquals('', $result->contents[0]->owner->id);
        $this->assertEquals('', $result->contents[0]->owner->displayName);
        $this->assertEquals($datetimeUtc1, $result->contents[0]->transitionTime);
        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListBucketResult>
            <Name></Name>
            <Prefix></Prefix>
            <StartAfter></StartAfter>
            <ContinuationToken></ContinuationToken>
            <MaxKeys></MaxKeys>
            <Delimiter></Delimiter>
            <IsTruncated></IsTruncated>
            <NextContinuationToken></NextContinuationToken>
            <Contents>
                <Key></Key>
                <LastModified></LastModified>
                <ETag></ETag>
                <Type></Type>
                <Size></Size>
                <StorageClass></StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
            </Contents>
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            <KeyCount></KeyCount>
            </ListBucketResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjectsV2($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('', $result->name);
        $this->assertEquals('', $result->prefix);
        $this->assertEquals('', $result->startAfter);
        $this->assertEquals('', $result->continuationToken);
        $this->assertEquals(0, $result->maxKeys);
        $this->assertEquals('', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('', $result->encodingType);
        $this->assertEquals('', $result->nextContinuationToken);
        $this->assertEquals(0, $result->keyCount);
        $this->assertEquals(1, count($result->contents));
        $this->assertEquals('', $result->contents[0]->key);
        $this->assertNull($result->contents[0]->lastModified);
        $this->assertEquals('', $result->contents[0]->etag);
        $this->assertEquals('', $result->contents[0]->type);
        $this->assertEquals(0, $result->contents[0]->size);
        $this->assertEquals('', $result->contents[0]->storageClass);
        $this->assertEquals('', $result->contents[0]->owner->id);
        $this->assertEquals('', $result->contents[0]->owner->displayName);
        $this->assertEquals(2, count($result->commonPrefixes));
        $this->assertEquals('', $result->commonPrefixes[0]->prefix);
        $this->assertEquals('', $result->commonPrefixes[1]->prefix);
    }

    public function testFromListObjectVersions()
    {
        // miss required field 
        try {
            $request = new Models\ListObjectVersionsRequest();
            $input = BucketBasic::fromListObjectVersions($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        // bucket only
        $request = new Models\ListObjectVersionsRequest('bucket-123');
        $input = BucketBasic::fromListObjectVersions($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        // all settings
        $request = new Models\ListObjectVersionsRequest(
            'bucket-123',
            'deli-123',
            'url',
            'key-marker-123',
            'version-id-marker-123',
            123,
            'prefix-123',
            'requester'
        );
        $input = BucketBasic::fromListObjectVersions($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('', $input->getParameters()['versions']);
        $this->assertEquals('deli-123', $input->getParameters()['delimiter']);
        $this->assertEquals('url', $input->getParameters()['encoding-type']);
        $this->assertEquals('key-marker-123', $input->getParameters()['key-marker']);
        $this->assertEquals('version-id-marker-123', $input->getParameters()['version-id-marker']);
        $this->assertEquals('123', $input->getParameters()['max-keys']);
        $this->assertEquals('prefix-123', $input->getParameters()['prefix']);
        $this->assertEquals('requester', $input->getHeaders()['x-oss-request-payer']);

        // extend header & parameters
        $request = new Models\ListObjectVersionsRequest(
            'bucket-123',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            [
                'headers' => ['x-oss-test' => 'test-123'],
                'parameters' => ['x-oss-param' => 'param-123']
            ]
        );
        $input = BucketBasic::fromListObjectVersions($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('application/octet-stream', $input->getHeaders()['content-type']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('test-123', $input->getHeaders()['x-oss-test']);
        $this->assertEquals('param-123', $input->getParameters()['x-oss-param']);
    }

    public function testToListObjectVersions()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toListObjectVersions($output);
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <ListVersionsResult>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListVersionsResult></ListVersionsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjectVersions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->name);
        $this->assertNull($result->prefix);
        $this->assertNull($result->keyMarker);
        $this->assertNull($result->versionIdMarker);
        $this->assertNull($result->maxKeys);
        $this->assertNull($result->delimiter);
        $this->assertNull($result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertNull($result->nextKeyMarker);
        $this->assertNull($result->nextVersionIdMarker);
        $this->assertNull($result->versions);
        $this->assertNull($result->deleteMarkers);
        $this->assertNull($result->commonPrefixes);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListVersionsResult>
            <Name>examplebucket</Name>
            <Prefix>fun/</Prefix>
            <KeyMarker>fu</KeyMarker>
            <VersionIdMarker>AgJiYw--</VersionIdMarker>
            <MaxKeys>2</MaxKeys>
            <Delimiter>/</Delimiter>
            <IsTruncated>true</IsTruncated>
            <NextKeyMarker>fu123</NextKeyMarker>
            <NextVersionIdMarker>AgJiYw--123</NextVersionIdMarker>
            <Version>
                <Key>fun/test.jpg</Key>
                <VersionId>id-123</VersionId>
                <IsLatest>true</IsLatest>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID>0022012****</ID>
                    <DisplayName>user_example</DisplayName>
                </Owner>
                <RestoreInfo>ongoing-request="true"</RestoreInfo>
            </Version>
            <DeleteMarker>
                <Key>example</Key>
                <VersionId>CAEQMxiBgICAof2D0BYiIDJhMGE3N2M1YTI1NDQzOGY5NTkyNTI3MGYyMzJm****</VersionId>
                <IsLatest>false</IsLatest>
                <LastModified>2023-12-17T03:30:19.000Z</LastModified>
                <Owner>
                <ID>1234512528586****</ID>
                <DisplayName>12345125285864390</DisplayName>
                </Owner>
            </DeleteMarker>            
            <Version>
                <Key>fun/test-1.jpg</Key>
                <LastModified>2023-12-17T03:30:19.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344605</Size>
                <StorageClass>IA</StorageClass>
                <Owner>
                </Owner>
            </Version>            
            <CommonPrefixes>
                <Prefix>fun/movie/</Prefix>
            </CommonPrefixes>
            </ListVersionsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjectVersions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fu', $result->keyMarker);
        $this->assertEquals('AgJiYw--', $result->versionIdMarker);
        $this->assertEquals(2, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(true, $result->isTruncated);
        $this->assertNull($result->encodingType);
        $this->assertEquals('fu123', $result->nextKeyMarker);
        $this->assertEquals('AgJiYw--123', $result->nextVersionIdMarker);
        $this->assertEquals(2, count($result->versions));
        $this->assertEquals('fun/test.jpg', $result->versions[0]->key);
        $this->assertEquals('id-123', $result->versions[0]->versionId);
        $this->assertEquals(true, $result->versions[0]->isLatest);
        $this->assertEquals($datetimeUtc, $result->versions[0]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->versions[0]->etag);
        $this->assertEquals('Normal', $result->versions[0]->type);
        $this->assertEquals(344606, $result->versions[0]->size);
        $this->assertEquals('Standard', $result->versions[0]->storageClass);
        $this->assertEquals('0022012****', $result->versions[0]->owner->id);
        $this->assertEquals('user_example', $result->versions[0]->owner->displayName);
        $this->assertEquals('ongoing-request="true"', $result->versions[0]->restoreInfo);

        $this->assertEquals('fun/test-1.jpg', $result->versions[1]->key);
        $this->assertNull($result->versions[1]->versionId);
        $this->assertNull($result->versions[1]->isLatest);
        $this->assertEquals($datetimeUtc1, $result->versions[1]->lastModified);
        $this->assertEquals('"5B3C1A2E053D763E1B002CC607C5A0FE1****"', $result->versions[1]->etag);
        $this->assertEquals('Normal', $result->versions[1]->type);
        $this->assertEquals(344605, $result->versions[1]->size);
        $this->assertEquals('IA', $result->versions[1]->storageClass);
        $this->assertNull($result->versions[1]->owner->id);
        $this->assertNull($result->versions[1]->owner->displayName);

        $this->assertEquals(1, count($result->deleteMarkers));
        $this->assertEquals('example', $result->deleteMarkers[0]->key);
        $this->assertEquals('CAEQMxiBgICAof2D0BYiIDJhMGE3N2M1YTI1NDQzOGY5NTkyNTI3MGYyMzJm****', $result->deleteMarkers[0]->versionId);
        $this->assertEquals(false, $result->deleteMarkers[0]->isLatest);
        $this->assertEquals($datetimeUtc1, $result->deleteMarkers[0]->lastModified);
        $this->assertEquals('1234512528586****', $result->deleteMarkers[0]->owner->id);
        $this->assertEquals('12345125285864390', $result->deleteMarkers[0]->owner->displayName);

        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // encodingType
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListVersionsResult>
            <Name>examplebucket</Name>
            <Prefix>fun%2F</Prefix>
            <KeyMarker>fun%2F</KeyMarker>
            <VersionIdMarker>AgJiYw--</VersionIdMarker>            
            <MaxKeys>1</MaxKeys>
            <Delimiter>%2F</Delimiter>
            <IsTruncated>false</IsTruncated>
            <NextKeyMarker>fun%2Ftest-1.jpg</NextKeyMarker>
            <NextVersionIdMarker>AgJiYw--123</NextVersionIdMarker>            
            <EncodingType>url</EncodingType>
            <Version>
                <Key>fun%2Ftest.jpg</Key>
                <LastModified>2023-12-17T03:30:09.000Z</LastModified>
                <ETag>"5B3C1A2E053D763E1B002CC607C5A0FE1****"</ETag>
                <Type>Normal</Type>
                <Size>344606</Size>
                <StorageClass>Standard</StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
                <TransitionTime>2023-12-17T03:30:19.000Z</TransitionTime>
            </Version>
            <DeleteMarker>
                <Key>example%2F123</Key>
                <VersionId>CAEQMxiBgICAof2D0BYiIDJhMGE3N2M1YTI1NDQzOGY5NTkyNTI3MGYyMzJm****</VersionId>
                <IsLatest>false</IsLatest>
                <LastModified>2023-12-17T03:30:19.000Z</LastModified>
                <Owner>
                <ID>1234512528586****</ID>
                <DisplayName>12345125285864390</DisplayName>
                </Owner>
            </DeleteMarker>              
            <CommonPrefixes>
                <Prefix>fun%2Fmovie%2F</Prefix>
            </CommonPrefixes>
            </ListVersionsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListObjectVersions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('examplebucket', $result->name);
        $this->assertEquals('fun/', $result->prefix);
        $this->assertEquals('fun/', $result->keyMarker);
        $this->assertEquals('AgJiYw--', $result->versionIdMarker);
        $this->assertEquals(1, $result->maxKeys);
        $this->assertEquals('/', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('url', $result->encodingType);
        $this->assertEquals('fun/test-1.jpg', $result->nextKeyMarker);
        $this->assertEquals('AgJiYw--123', $result->nextVersionIdMarker);
        $this->assertEquals(1, count($result->versions));
        $this->assertEquals('fun/test.jpg', $result->versions[0]->key);
        $this->assertEquals('', $result->versions[0]->owner->id);
        $this->assertEquals('', $result->versions[0]->owner->displayName);
        $this->assertEquals(1, count($result->deleteMarkers));
        $this->assertEquals('example/123', $result->deleteMarkers[0]->key);
        $this->assertEquals(1, count($result->commonPrefixes));
        $this->assertEquals('fun/movie/', $result->commonPrefixes[0]->prefix);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListVersionsResult>
            <Name></Name>
            <Prefix></Prefix>
            <StartAfter></StartAfter>
            <ContinuationToken></ContinuationToken>
            <MaxKeys></MaxKeys>
            <Delimiter></Delimiter>
            <IsTruncated></IsTruncated>
            <NextContinuationToken></NextContinuationToken>
            <Version>
                <Key></Key>
                <LastModified></LastModified>
                <ETag></ETag>
                <Type></Type>
                <Size></Size>
                <StorageClass></StorageClass>
                <Owner>
                    <ID></ID>
                    <DisplayName></DisplayName>
                </Owner>
            </Version>
            <DeleteMarker>
                <Key></Key>
                <VersionId></VersionId>
                <IsLatest></IsLatest>
                <LastModified></LastModified>
                <Owner>
                <ID></ID>
                <DisplayName></DisplayName>
                </Owner>
            </DeleteMarker> 
            <DeleteMarker>
                <Key></Key>
                <VersionId></VersionId>
                <IsLatest></IsLatest>
                <LastModified></LastModified>
                <Owner>
                <ID></ID>
                <DisplayName></DisplayName>
                </Owner>
            </DeleteMarker>                         
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            <CommonPrefixes>
                <Prefix></Prefix>
            </CommonPrefixes>
            <KeyCount></KeyCount>
            </ListVersionsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $datetimeUtc1 = new \DateTime();
        $datetimeUtc1->setTimestamp(1702783819);
        $result = BucketBasic::toListObjectVersions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('', $result->name);
        $this->assertEquals('', $result->prefix);
        $this->assertEquals('', $result->keyMarker);
        $this->assertEquals('', $result->versionIdMarker);
        $this->assertEquals(0, $result->maxKeys);
        $this->assertEquals('', $result->delimiter);
        $this->assertEquals(false, $result->isTruncated);
        $this->assertEquals('', $result->encodingType);
        $this->assertEquals('', $result->nextKeyMarker);
        $this->assertEquals('', $result->nextVersionIdMarker);
        $this->assertEquals(1, count($result->versions));
        $this->assertEquals('', $result->versions[0]->key);
        $this->assertEquals('', $result->versions[0]->versionId);
        $this->assertEquals(false, $result->versions[0]->isLatest);
        $this->assertNull($result->versions[0]->lastModified);
        $this->assertEquals('', $result->versions[0]->etag);
        $this->assertEquals('', $result->versions[0]->type);
        $this->assertEquals(0, $result->versions[0]->size);
        $this->assertEquals('', $result->versions[0]->storageClass);
        $this->assertEquals('', $result->versions[0]->owner->id);
        $this->assertEquals('', $result->versions[0]->owner->displayName);

        $this->assertEquals(2, count($result->deleteMarkers));
        $this->assertEquals('', $result->deleteMarkers[0]->key);
        $this->assertEquals('', $result->deleteMarkers[0]->versionId);
        $this->assertEquals(false, $result->deleteMarkers[0]->isLatest);
        $this->assertNull($result->deleteMarkers[0]->lastModified);
        $this->assertEquals('', $result->deleteMarkers[0]->owner->id);
        $this->assertEquals('', $result->deleteMarkers[0]->owner->displayName);

        $this->assertEquals('', $result->deleteMarkers[1]->key);
        $this->assertEquals('', $result->deleteMarkers[1]->versionId);
        $this->assertEquals(false, $result->deleteMarkers[1]->isLatest);

        $this->assertEquals(2, count($result->commonPrefixes));
        $this->assertEquals('', $result->commonPrefixes[0]->prefix);
        $this->assertEquals('', $result->commonPrefixes[1]->prefix);
    }

    public function testFromListBuckets()
    {
        // miss required field
        try {
            $request = new Models\ListBucketsRequest();
            $input = BucketBasic::fromListBuckets($request);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, 'should not here');
        }

        $request = new Models\ListBucketsRequest('/', '', 10,'rg-aek27tc********');
        $input = BucketBasic::fromListBuckets($request);
        $this->assertEquals('rg-aek27tc********', $input->getHeaders()['x-oss-resource-group-id']);
        $this->assertEquals('', $input->getParameters()['marker']);
        $this->assertEquals('/', $input->getParameters()['prefix']);
        $this->assertEquals('10', $input->getParameters()['max-keys']);

        $request = new Models\ListBucketsRequest('/', '', 10,'rg-aek27tc********','k','v');
        $input = BucketBasic::fromListBuckets($request);
        $this->assertEquals('rg-aek27tc********', $input->getHeaders()['x-oss-resource-group-id']);
        $this->assertEquals('', $input->getParameters()['marker']);
        $this->assertEquals('/', $input->getParameters()['prefix']);
        $this->assertEquals('10', $input->getParameters()['max-keys']);
        $this->assertEquals('k', $input->getParameters()['tag-key']);
        $this->assertEquals('v', $input->getParameters()['tag-value']);

        $request = new Models\ListBucketsRequest('/', '', 10,'rg-aek27tc********',null,null,"\"k\":\"v\"");
        $input = BucketBasic::fromListBuckets($request);
        $this->assertEquals('rg-aek27tc********', $input->getHeaders()['x-oss-resource-group-id']);
        $this->assertEquals('', $input->getParameters()['marker']);
        $this->assertEquals('/', $input->getParameters()['prefix']);
        $this->assertEquals('10', $input->getParameters()['max-keys']);
        $this->assertEquals("\"k\":\"v\"", $input->getParameters()['tagging']);
    }

    public function testToListBuckets()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toListBuckets($output);
            $this->assertTrue(false, 'should not here');
        } catch (DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <ListAllMyBucketsResult>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <ListAllMyBucketsResult></ListAllMyBucketsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListBuckets($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->prefix);
        $this->assertNull($result->marker);
        $this->assertNull($result->maxKeys);
        $this->assertNull($result->isTruncated);
        $this->assertNull($result->nextMarker);
        $this->assertNull($result->owner);
        $this->assertNull($result->buckets);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
<ListAllMyBucketsResult>
  <Owner>
    <ID>51264</ID>
    <DisplayName>51264</DisplayName>
  </Owner>
  <Buckets>
    <Bucket>
      <CreationDate>2014-02-17T18:12:43.000Z</CreationDate>
      <ExtranetEndpoint>oss-cn-shanghai.aliyuncs.com</ExtranetEndpoint>
      <IntranetEndpoint>oss-cn-shanghai-internal.aliyuncs.com</IntranetEndpoint>
      <Location>oss-cn-shanghai</Location>
      <Name>app-base-oss</Name>
      <Region>cn-shanghai</Region>
      <StorageClass>Standard</StorageClass>
    </Bucket>
    <Bucket>
      <CreationDate>2014-02-25T11:21:04.000Z</CreationDate>
      <ExtranetEndpoint>oss-cn-hangzhou.aliyuncs.com</ExtranetEndpoint>
      <IntranetEndpoint>oss-cn-hangzhou-internal.aliyuncs.com</IntranetEndpoint>
      <Location>oss-cn-hangzhou</Location>
      <Name>mybucket</Name>
      <Region>cn-hangzhou</Region>
      <StorageClass>IA</StorageClass>
    </Bucket>
  </Buckets>
</ListAllMyBucketsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListBuckets($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('51264', $result->owner->displayName);
        $this->assertEquals('51264', $result->owner->id);
        $this->assertEquals(2, count($result->buckets));
        $this->assertEquals(\DateTime::createFromFormat(
            'Y-m-d\TH:i:s.000\Z',
            '2014-02-17T18:12:43.000Z',
            new \DateTimeZone('UTC')
        ), $result->buckets[0]->creationDate);
        $this->assertEquals('oss-cn-shanghai.aliyuncs.com', $result->buckets[0]->extranetEndpoint);
        $this->assertEquals('oss-cn-shanghai-internal.aliyuncs.com', $result->buckets[0]->intranetEndpoint);
        $this->assertEquals('app-base-oss', $result->buckets[0]->name);
        $this->assertEquals('cn-shanghai', $result->buckets[0]->region);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->buckets[0]->storageClass);
        $this->assertEquals('oss-cn-shanghai', $result->buckets[0]->location);

        $this->assertEquals(\DateTime::createFromFormat(
            'Y-m-d\TH:i:s.000\Z',
            '2014-02-25T11:21:04.000Z',
            new \DateTimeZone('UTC')
        ), $result->buckets[1]->creationDate);
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $result->buckets[1]->extranetEndpoint);
        $this->assertEquals('oss-cn-hangzhou-internal.aliyuncs.com', $result->buckets[1]->intranetEndpoint);
        $this->assertEquals('mybucket', $result->buckets[1]->name);
        $this->assertEquals('cn-hangzhou', $result->buckets[1]->region);
        $this->assertEquals(Models\StorageClassType::IA, $result->buckets[1]->storageClass);
        $this->assertEquals('oss-cn-hangzhou', $result->buckets[1]->location);

        $str = '<?xml version="1.0" encoding="UTF-8"?>
<ListAllMyBucketsResult>
  <Prefix>my</Prefix>
  <Marker>mybucket</Marker>
  <MaxKeys>10</MaxKeys>
  <IsTruncated>true</IsTruncated>
  <NextMarker>mybucket10</NextMarker>
  <Owner>
    <ID>ut_test_put_bucket</ID>
    <DisplayName>ut_test_put_bucket</DisplayName>
  </Owner>
  <Buckets>
    <Bucket>
      <CreationDate>2014-05-14T11:18:32.000Z</CreationDate>
      <ExtranetEndpoint>oss-cn-hangzhou.aliyuncs.com</ExtranetEndpoint>
      <IntranetEndpoint>oss-cn-hangzhou-internal.aliyuncs.com</IntranetEndpoint>
      <Location>oss-cn-hangzhou</Location>
      <Name>mybucket01</Name>
      <Region>cn-hangzhou</Region>
      <StorageClass>Standard</StorageClass>
       <ResourceGroupId>rg-aek27tc********</ResourceGroupId>
       
    </Bucket>
  </Buckets>
</ListAllMyBucketsResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toListBuckets($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('ut_test_put_bucket', $result->owner->displayName);
        $this->assertEquals('ut_test_put_bucket', $result->owner->id);
        $this->assertEquals('my', $result->prefix);
        $this->assertEquals('mybucket', $result->marker);
        $this->assertEquals(10, $result->maxKeys);
        $this->assertTrue($result->isTruncated);
        $this->assertEquals('mybucket10', $result->nextMarker);
        $this->assertEquals(1, count($result->buckets));
        $this->assertEquals(\DateTime::createFromFormat(
            'Y-m-d\TH:i:s.000\Z',
            '2014-05-14T11:18:32.000Z',
            new \DateTimeZone('UTC')
        ), $result->buckets[0]->creationDate);
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $result->buckets[0]->extranetEndpoint);
        $this->assertEquals('oss-cn-hangzhou-internal.aliyuncs.com', $result->buckets[0]->intranetEndpoint);
        $this->assertEquals('mybucket01', $result->buckets[0]->name);
        $this->assertEquals('cn-hangzhou', $result->buckets[0]->region);
        $this->assertEquals(Models\StorageClassType::STANDARD, $result->buckets[0]->storageClass);
        $this->assertEquals('oss-cn-hangzhou', $result->buckets[0]->location);
        $this->assertEquals('rg-aek27tc********', $result->buckets[0]->resourceGroupId);
    }

    public function testFromDescribeRegions()
    {
        // miss required field
        try {
            $request = new Models\DescribeRegionsRequest();
            $input = BucketBasic::fromDescribeRegions($request);
            $this->assertEquals('', $input->getParameters()['regions']);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(false, 'should not here');
        }

        $request = new Models\DescribeRegionsRequest("oss-cn-hangzhou");
        $input = BucketBasic::fromDescribeRegions($request);
        $this->assertEquals('oss-cn-hangzhou', $input->getParameters()['regions']);
    }

    public function testToDescribeRegions()
    {
        // empty output
        try {
            $output = new OperationOutput();
            $result = BucketBasic::toDescribeRegions($output);
            $this->assertTrue(false, 'should not here');
        } catch (DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag <RegionInfoList>', $e);
        }

        //empty xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <RegionInfoList></RegionInfoList>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'test'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toDescribeRegions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('test', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertNull($result->regionInfos);

        // xml
        $str = '<?xml version="1.0" encoding="UTF-8"?>
<RegionInfoList>
  <RegionInfo>
     <Region>oss-cn-hangzhou</Region>
     <InternetEndpoint>oss-cn-hangzhou.aliyuncs.com</InternetEndpoint>
     <InternalEndpoint>oss-cn-hangzhou-internal.aliyuncs.com</InternalEndpoint>
     <AccelerateEndpoint>oss-accelerate.aliyuncs.com</AccelerateEndpoint>  
  </RegionInfo>
  <RegionInfo>
     <Region>oss-cn-shanghai</Region>
     <InternetEndpoint>oss-cn-shanghai.aliyuncs.com</InternetEndpoint>
     <InternalEndpoint>oss-cn-shanghai-internal.aliyuncs.com</InternalEndpoint>
     <AccelerateEndpoint>oss-accelerate.aliyuncs.com</AccelerateEndpoint>  
  </RegionInfo>
</RegionInfoList>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toDescribeRegions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(2, count($result->regionInfos));
        $this->assertEquals('oss-cn-hangzhou', $result->regionInfos[0]->region);
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $result->regionInfos[0]->internetEndpoint);
        $this->assertEquals('oss-cn-hangzhou-internal.aliyuncs.com', $result->regionInfos[0]->internalEndpoint);
        $this->assertEquals('oss-accelerate.aliyuncs.com', $result->regionInfos[0]->accelerateEndpoint);

        $this->assertEquals('oss-cn-shanghai', $result->regionInfos[1]->region);
        $this->assertEquals('oss-cn-shanghai.aliyuncs.com', $result->regionInfos[1]->internetEndpoint);
        $this->assertEquals('oss-cn-shanghai-internal.aliyuncs.com', $result->regionInfos[1]->internalEndpoint);
        $this->assertEquals('oss-accelerate.aliyuncs.com', $result->regionInfos[1]->accelerateEndpoint);

        $str = '<?xml version="1.0" encoding="UTF-8"?>
<RegionInfoList>
  <RegionInfo>
    <Region>oss-cn-hangzhou</Region>
    <InternetEndpoint>oss-cn-hangzhou.aliyuncs.com</InternetEndpoint>
    <InternalEndpoint>oss-cn-hangzhou-internal.aliyuncs.com</InternalEndpoint>
    <AccelerateEndpoint>oss-accelerate.aliyuncs.com</AccelerateEndpoint>  
  </RegionInfo>
</RegionInfoList>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($str)
        );
        $result = BucketBasic::toDescribeRegions($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->regionInfos));
        $this->assertEquals('oss-cn-hangzhou', $result->regionInfos[0]->region);
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $result->regionInfos[0]->internetEndpoint);
        $this->assertEquals('oss-cn-hangzhou-internal.aliyuncs.com', $result->regionInfos[0]->internalEndpoint);
        $this->assertEquals('oss-accelerate.aliyuncs.com', $result->regionInfos[0]->accelerateEndpoint);
    }

    private function cleanXml($xml)
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
