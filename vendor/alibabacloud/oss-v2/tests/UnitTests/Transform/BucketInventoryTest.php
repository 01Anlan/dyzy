<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketInventory;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketInventoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketInventory()
    {
        // miss required field
        try {
            $request = new Models\PutBucketInventoryRequest();
            $input = BucketInventory::fromPutBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketInventoryRequest('bucket-123');
            $input = BucketInventory::fromPutBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, inventoryId", (string)$e);
        }

        try {
            $request = new Models\PutBucketInventoryRequest('bucket-123', 'inventory-id');
            $input = BucketInventory::fromPutBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, inventoryConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketInventoryRequest('bucket-123', 'report1', inventoryConfiguration: new Models\InventoryConfiguration(
            isEnabled: true,
            destination: new Models\InventoryDestination(
            ossBucketDestination: new Models\InventoryOSSBucketDestination(
                bucket: 'acs:oss:::destination-bucket',
                prefix: 'prefix1',
                encryption: new Models\InventoryEncryption(
                sseKms: new Models\SSEKMS('keyId')
            ),
                format: Models\InventoryFormatType::CSV,
                accountId: '1000000000000000', roleArn: 'acs:ram::1000000000000000:role/AliyunOSSRole'
            )
        ),
            filter: new Models\InventoryFilter(
            prefix: 'filterPrefix',
            lastModifyBeginTimeStamp: 1637883649,
            lastModifyEndTimeStamp: 1638347592,
            lowerSizeBound: 1024,
            upperSizeBound: 1048576,
            storageClass: 'Standard,IA'
        ),
            includedObjectVersions: 'All',
            optionalFields: new Models\OptionalFields(
            [
                Models\InventoryOptionalFieldType::SIZE,
                Models\InventoryOptionalFieldType::LAST_MODIFIED_DATE,
                Models\InventoryOptionalFieldType::ETAG,
                Models\InventoryOptionalFieldType::STORAGE_CLASS,
                Models\InventoryOptionalFieldType::IS_MULTIPART_UPLOADED,
                Models\InventoryOptionalFieldType::ENCRYPTION_STATUS,
                Models\InventoryOptionalFieldType::TRANSITION_TIME,
            ]
        ),
            id: 'report1'
        ));
        $input = BucketInventory::fromPutBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><InventoryConfiguration><IsEnabled>true</IsEnabled><Destination><OSSBucketDestination><Bucket>acs:oss:::destination-bucket</Bucket><Prefix>prefix1</Prefix><Encryption><SSE-KMS><KeyId>keyId</KeyId></SSE-KMS></Encryption><Format>CSV</Format><AccountId>1000000000000000</AccountId><RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn></OSSBucketDestination></Destination><Filter><Prefix>filterPrefix</Prefix><LastModifyBeginTimeStamp>1637883649</LastModifyBeginTimeStamp><LastModifyEndTimeStamp>1638347592</LastModifyEndTimeStamp><LowerSizeBound>1024</LowerSizeBound><UpperSizeBound>1048576</UpperSizeBound><StorageClass>Standard,IA</StorageClass></Filter><IncludedObjectVersions>All</IncludedObjectVersions><OptionalFields><Field>Size</Field><Field>LastModifiedDate</Field><Field>ETag</Field><Field>StorageClass</Field><Field>IsMultipartUploaded</Field><Field>EncryptionStatus</Field><Field>TransitionTime</Field></OptionalFields><Id>report1</Id></InventoryConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketInventoryRequest('bucket-123', 'report1', inventoryConfiguration: new Models\InventoryConfiguration(
            isEnabled: true,
            destination: new Models\InventoryDestination(
            ossBucketDestination: new Models\InventoryOSSBucketDestination(
                bucket: 'acs:oss:::destination-bucket',
                format: Models\InventoryFormatType::CSV,
                accountId: '1000000000000000',
                roleArn: 'acs:ram::1000000000000000:role/AliyunOSSRole'
            )
        ),
            schedule: new Models\InventorySchedule(
            frequency: Models\InventoryFrequencyType::DAILY
        ),
            filter: new Models\InventoryFilter(
            prefix: 'filterPrefix',
            lastModifyBeginTimeStamp: 1637883649,
            lastModifyEndTimeStamp: 1638347592,
            lowerSizeBound: 1024,
            upperSizeBound: 1048576,
            storageClass: 'Standard,IA'
        ),
            includedObjectVersions: 'All',
            id: 'report1'
        ));
        $input = BucketInventory::fromPutBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><InventoryConfiguration><IsEnabled>true</IsEnabled><Destination><OSSBucketDestination><Bucket>acs:oss:::destination-bucket</Bucket><Format>CSV</Format><AccountId>1000000000000000</AccountId><RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn></OSSBucketDestination></Destination><Schedule><Frequency>Daily</Frequency></Schedule><Filter><Prefix>filterPrefix</Prefix><LastModifyBeginTimeStamp>1637883649</LastModifyBeginTimeStamp><LastModifyEndTimeStamp>1638347592</LastModifyEndTimeStamp><LowerSizeBound>1024</LowerSizeBound><UpperSizeBound>1048576</UpperSizeBound><StorageClass>Standard,IA</StorageClass></Filter><IncludedObjectVersions>All</IncludedObjectVersions><Id>report1</Id></InventoryConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketInventory()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketInventory::toPutBucketInventory($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketInventory::toPutBucketInventory($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketInventory()
    {
        // miss required field
        try {
            $request = new Models\GetBucketInventoryRequest();
            $input = BucketInventory::fromGetBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetBucketInventoryRequest('bucket-123');
            $input = BucketInventory::fromGetBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, inventoryId", (string)$e);
        }

        $request = new Models\GetBucketInventoryRequest('bucket-123', 'inventoryId');
        $input = BucketInventory::fromGetBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketInventory()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketInventory::toGetBucketInventory($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
			<InventoryConfiguration>
     <Id>report1</Id>
     <IsEnabled>true</IsEnabled>
     <Destination>
        <OSSBucketDestination>
           <Format>CSV</Format>
           <AccountId>1000000000000000</AccountId>
           <RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn>
           <Bucket>acs:oss:::bucket_0001</Bucket>
           <Prefix>prefix1</Prefix>
           <Encryption>
              <SSE-KMS>
                 <KeyId>keyId</KeyId>
              </SSE-KMS>
           </Encryption>
        </OSSBucketDestination>
     </Destination>
     <Schedule>
        <Frequency>Daily</Frequency>
     </Schedule>
     <Filter>
        <LastModifyBeginTimeStamp>1637883649</LastModifyBeginTimeStamp>
        <LastModifyEndTimeStamp>1638347592</LastModifyEndTimeStamp>
        <LowerSizeBound>1024</LowerSizeBound>
        <UpperSizeBound>1048576</UpperSizeBound>
        <StorageClass>Standard,IA</StorageClass>
       	<Prefix>myprefix/</Prefix>
     </Filter>
     <IncludedObjectVersions>All</IncludedObjectVersions>
     <OptionalFields>
        <Field>Size</Field>
        <Field>LastModifiedDate</Field>
        <Field>ETag</Field>
        <Field>StorageClass</Field>
        <Field>IsMultipartUploaded</Field>
        <Field>EncryptionStatus</Field>
		<Field>TransitionTime</Field>
     </OptionalFields>
  </InventoryConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketInventory::toGetBucketInventory($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('report1', $result->inventoryConfiguration->id);
        $this->assertTrue($result->inventoryConfiguration->isEnabled);
        $this->assertEquals(Models\InventoryFormatType::CSV, $result->inventoryConfiguration->destination->ossBucketDestination->format);
        $this->assertEquals('1000000000000000', $result->inventoryConfiguration->destination->ossBucketDestination->accountId);
        $this->assertEquals('acs:ram::1000000000000000:role/AliyunOSSRole', $result->inventoryConfiguration->destination->ossBucketDestination->roleArn);
        $this->assertEquals('acs:oss:::bucket_0001', $result->inventoryConfiguration->destination->ossBucketDestination->bucket);
        $this->assertEquals('prefix1', $result->inventoryConfiguration->destination->ossBucketDestination->prefix);
        $this->assertEquals('keyId', $result->inventoryConfiguration->destination->ossBucketDestination->encryption->sseKms->keyId);
        $this->assertEquals(Models\InventoryFrequencyType::DAILY, $result->inventoryConfiguration->schedule->frequency);
        $this->assertEquals('All', $result->inventoryConfiguration->includedObjectVersions);
        $this->assertEquals(7, count($result->inventoryConfiguration->optionalFields->fields));
        $this->assertEquals(Models\InventoryOptionalFieldType::STORAGE_CLASS, $result->inventoryConfiguration->optionalFields->fields[3]);
        $this->assertEquals(Models\InventoryOptionalFieldType::TRANSITION_TIME, $result->inventoryConfiguration->optionalFields->fields[6]);
        $this->assertEquals('myprefix/', $result->inventoryConfiguration->filter->prefix);
        $this->assertEquals(1637883649, $result->inventoryConfiguration->filter->lastModifyBeginTimeStamp);
        $this->assertEquals(1638347592, $result->inventoryConfiguration->filter->lastModifyEndTimeStamp);
        $this->assertEquals(1024, $result->inventoryConfiguration->filter->lowerSizeBound);
        $this->assertEquals(1048576, $result->inventoryConfiguration->filter->upperSizeBound);
        $this->assertEquals('Standard,IA', $result->inventoryConfiguration->filter->storageClass);
        $body = '<?xml version="1.0" encoding="UTF-8"?>
<InventoryConfiguration>
    <Id>report1</Id>
    <IsEnabled>true</IsEnabled>
    <Destination>
        <OSSBucketDestination>
            <Format>CSV</Format>
            <AccountId>1000000000000000</AccountId>
            <RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn>
            <Bucket>acs:oss:::destination-bucket</Bucket>
        </OSSBucketDestination>
    </Destination>
    <Schedule>
        <Frequency>Weekly</Frequency>
    </Schedule>
    <IncludedObjectVersions>Current</IncludedObjectVersions>
</InventoryConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketInventory::toGetBucketInventory($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('report1', $result->inventoryConfiguration->id);
        $this->assertTrue($result->inventoryConfiguration->isEnabled);
        $this->assertEquals(Models\InventoryFormatType::CSV, $result->inventoryConfiguration->destination->ossBucketDestination->format);
        $this->assertEquals('1000000000000000', $result->inventoryConfiguration->destination->ossBucketDestination->accountId);
        $this->assertEquals('acs:ram::1000000000000000:role/AliyunOSSRole', $result->inventoryConfiguration->destination->ossBucketDestination->roleArn);
        $this->assertEquals('acs:oss:::destination-bucket', $result->inventoryConfiguration->destination->ossBucketDestination->bucket);
        $this->assertEquals(Models\InventoryFrequencyType::WEEKLY, $result->inventoryConfiguration->schedule->frequency);
        $this->assertEquals('Current', $result->inventoryConfiguration->includedObjectVersions);
    }

    public function testFromListBucketInventory()
    {
        // miss required field
        try {
            $request = new Models\ListBucketInventoryRequest();
            $input = BucketInventory::fromListBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\ListBucketInventoryRequest('bucket-123');
        $input = BucketInventory::fromListBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        $request = new Models\ListBucketInventoryRequest('bucket-123', 'token');
        $input = BucketInventory::fromListBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('token', $input->getParameters()['continuation-token']);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToListBucketInventory()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketInventory::toListBucketInventory($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
			<ListInventoryConfigurationsResult>
     <InventoryConfiguration>
        <Id>report1</Id>
        <IsEnabled>true</IsEnabled>
        <Destination>
           <OSSBucketDestination>
              <Format>CSV</Format>
              <AccountId>1000000000000000</AccountId>
              <RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn>
              <Bucket>acs:oss:::destination-bucket</Bucket>
              <Prefix>prefix1</Prefix>
           </OSSBucketDestination>
        </Destination>
        <Schedule>
           <Frequency>Daily</Frequency>
        </Schedule>
        <Filter>
           <Prefix>prefix/One</Prefix>
        </Filter>
        <IncludedObjectVersions>All</IncludedObjectVersions>
        <OptionalFields>
           <Field>Size</Field>
           <Field>LastModifiedDate</Field>
           <Field>ETag</Field>
           <Field>StorageClass</Field>
           <Field>IsMultipartUploaded</Field>
           <Field>EncryptionStatus</Field>
        </OptionalFields>
     </InventoryConfiguration>
     <InventoryConfiguration>
        <Id>report2</Id>
        <IsEnabled>true</IsEnabled>
        <Destination>
           <OSSBucketDestination>
              <Format>CSV</Format>
              <AccountId>1000000000000000</AccountId>
              <RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn>
              <Bucket>acs:oss:::destination-bucket</Bucket>
              <Prefix>prefix2</Prefix>
           </OSSBucketDestination>
        </Destination>
        <Schedule>
           <Frequency>Daily</Frequency>
        </Schedule>
        <Filter>
           <Prefix>prefix/Two</Prefix>
        </Filter>
        <IncludedObjectVersions>All</IncludedObjectVersions>
        <OptionalFields>
           <Field>Size</Field>
           <Field>LastModifiedDate</Field>
           <Field>ETag</Field>
           <Field>StorageClass</Field>
           <Field>IsMultipartUploaded</Field>
           <Field>EncryptionStatus</Field>
        </OptionalFields>
     </InventoryConfiguration>
     <InventoryConfiguration>
        <Id>report3</Id>
        <IsEnabled>true</IsEnabled>
        <Destination>
           <OSSBucketDestination>
              <Format>CSV</Format>
              <AccountId>1000000000000000</AccountId>
              <RoleArn>acs:ram::1000000000000000:role/AliyunOSSRole</RoleArn>
              <Bucket>acs:oss:::destination-bucket</Bucket>
              <Prefix>prefix3</Prefix>
           </OSSBucketDestination>
        </Destination>
        <Schedule>
           <Frequency>Daily</Frequency>
        </Schedule>
        <Filter>
           <Prefix>prefix/Three</Prefix>
        </Filter>
        <IncludedObjectVersions>All</IncludedObjectVersions>
        <OptionalFields>
           <Field>Size</Field>
           <Field>LastModifiedDate</Field>
           <Field>ETag</Field>
           <Field>StorageClass</Field>
           <Field>IsMultipartUploaded</Field>
           <Field>EncryptionStatus</Field>
        </OptionalFields>
     </InventoryConfiguration>
     <IsTruncated>false</IsTruncated>
  </ListInventoryConfigurationsResult>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketInventory::toListBucketInventory($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(3, count($result->listInventoryConfigurationsResult->inventoryConfigurations));
        $this->assertEquals('report1', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->id);
        $this->assertTrue($result->listInventoryConfigurationsResult->inventoryConfigurations[0]->isEnabled);
        $this->assertEquals(Models\InventoryFormatType::CSV, $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->destination->ossBucketDestination->format);
        $this->assertEquals('1000000000000000', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->destination->ossBucketDestination->accountId);
        $this->assertEquals('acs:ram::1000000000000000:role/AliyunOSSRole', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->destination->ossBucketDestination->roleArn);
        $this->assertEquals('acs:oss:::destination-bucket', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->destination->ossBucketDestination->bucket);
        $this->assertEquals('prefix1', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->destination->ossBucketDestination->prefix);
        $this->assertEquals(Models\InventoryFrequencyType::DAILY, $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->schedule->frequency);
        $this->assertEquals('All', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->includedObjectVersions);
        $this->assertEquals(6, count($result->listInventoryConfigurationsResult->inventoryConfigurations[0]->optionalFields->fields));
        $this->assertEquals(Models\InventoryOptionalFieldType::STORAGE_CLASS, $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->optionalFields->fields[3]);
        $this->assertEquals('prefix/One', $result->listInventoryConfigurationsResult->inventoryConfigurations[0]->filter->prefix);

        $this->assertEquals('report2', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->id);
        $this->assertTrue($result->listInventoryConfigurationsResult->inventoryConfigurations[1]->isEnabled);
        $this->assertEquals(Models\InventoryFormatType::CSV, $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->destination->ossBucketDestination->format);
        $this->assertEquals('1000000000000000', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->destination->ossBucketDestination->accountId);
        $this->assertEquals('acs:ram::1000000000000000:role/AliyunOSSRole', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->destination->ossBucketDestination->roleArn);
        $this->assertEquals('acs:oss:::destination-bucket', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->destination->ossBucketDestination->bucket);
        $this->assertEquals('prefix2', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->destination->ossBucketDestination->prefix);
        $this->assertEquals(Models\InventoryFrequencyType::DAILY, $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->schedule->frequency);
        $this->assertEquals('All', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->includedObjectVersions);
        $this->assertEquals(6, count($result->listInventoryConfigurationsResult->inventoryConfigurations[1]->optionalFields->fields));
        $this->assertEquals(Models\InventoryOptionalFieldType::STORAGE_CLASS, $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->optionalFields->fields[3]);
        $this->assertEquals('prefix/Two', $result->listInventoryConfigurationsResult->inventoryConfigurations[1]->filter->prefix);
        $this->assertFalse($result->listInventoryConfigurationsResult->isTruncated);
    }

    public function testFromDeleteBucketInventory()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketInventoryRequest();
            $input = BucketInventory::fromDeleteBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteBucketInventoryRequest('bucket-123');
            $input = BucketInventory::fromDeleteBucketInventory($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, inventoryId", (string)$e);
        }

        $request = new Models\DeleteBucketInventoryRequest('bucket-123', 'inventoryId');
        $input = BucketInventory::fromDeleteBucketInventory($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketInventory()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketInventory::toDeleteBucketInventory($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketInventory::toDeleteBucketInventory($output);
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
