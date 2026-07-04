<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketReplication;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketReplicationTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketRtc()
    {
        // miss required field
        try {
            $request = new Models\PutBucketRtcRequest();
            $input = BucketReplication::fromPutBucketRtc($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketRtcRequest('bucket-123');
            $input = BucketReplication::fromPutBucketRtc($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, rtcConfiguration", (string)$e);
        }

        $request = new Models\PutBucketRtcRequest('bucket-123', new Models\RtcConfiguration(
            rtc: new Models\ReplicationTimeControl(
            status: 'enabled'
        ),
            id: 'test_replication_rule_1'
        ));
        $input = BucketReplication::fromPutBucketRtc($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ReplicationRule><RTC><Status>enabled</Status></RTC><ID>test_replication_rule_1</ID></ReplicationRule>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketRtc()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toPutBucketRtc($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketReplication::toPutBucketRtc($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromPutBucketReplication()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketReplicationRequest();
            $input = BucketReplication::fromPutBucketReplication($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketReplicationRequest('bucket-123');
            $input = BucketReplication::fromPutBucketReplication($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, replicationConfiguration", (string)$e);
        }

        $request = new Models\PutBucketReplicationRequest('bucket-123', new Models\ReplicationConfiguration(
            array(new Models\ReplicationRule(
                sourceSelectionCriteria: new Models\ReplicationSourceSelectionCriteria(
                sseKmsEncryptedObjects: new Models\SseKmsEncryptedObjects(
                    status: 'Enabled',
                )
            ),
                destination: new Models\ReplicationDestination(
                bucket: 'destBucket',
                location: 'oss-cn-beijing',
                transferType: Models\TransferType::OSS_ACC
            ),
                historicalObjectReplication: Models\HistoricalObjectReplicationType::ENABLED,
                syncRole: 'aliyunramrole',
                encryptionConfiguration: new Models\ReplicationEncryptionConfiguration(
                replicaKmsKeyID: 'c4d49f85-ee30-426b-a5ed-95e9139d****'
            ),
                rtc: new Models\ReplicationTimeControl(
                    status: 'enabled'
                )
            ))
        ));
        $input = BucketReplication::fromPutBucketReplication($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ReplicationConfiguration><Rule><SourceSelectionCriteria><SseKmsEncryptedObjects><Status>Enabled</Status></SseKmsEncryptedObjects></SourceSelectionCriteria><Destination><Bucket>destBucket</Bucket><Location>oss-cn-beijing</Location><TransferType>oss_acc</TransferType></Destination><HistoricalObjectReplication>enabled</HistoricalObjectReplication><SyncRole>aliyunramrole</SyncRole><EncryptionConfiguration><ReplicaKmsKeyID>c4d49f85-ee30-426b-a5ed-95e9139d****</ReplicaKmsKeyID></EncryptionConfiguration><RTC><Status>enabled</Status></RTC></Rule></ReplicationConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketReplication()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toPutBucketReplication($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'x-oss-replication-rule-id' => 'b890c26e-907e-4692-b9ac-ec4c11ec****']
        );
        $result = BucketReplication::toPutBucketReplication($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('b890c26e-907e-4692-b9ac-ec4c11ec****', $result->replicationRuleId);
    }

    public function testFromGetBucketReplication()
    {
        // miss required field
        try {
            $request = new Models\GetBucketReplicationRequest();
            $input = BucketReplication::fromGetBucketReplication($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketReplicationRequest('bucket-123');
        $input = BucketReplication::fromGetBucketReplication($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketReplication()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toGetBucketReplication($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ReplicationConfiguration>
  <Rule>
    <ID>test_replication_1</ID>
    <PrefixSet>
      <Prefix>source1</Prefix>
      <Prefix>video</Prefix>
    </PrefixSet>
    <Action>PUT</Action>
    <Destination>
      <Bucket>destbucket</Bucket>
      <Location>oss-cn-beijing</Location>
      <TransferType>oss_acc</TransferType>
    </Destination>
    <Status>doing</Status>
    <HistoricalObjectReplication>enabled</HistoricalObjectReplication>
    <SyncRole>aliyunramrole</SyncRole>
    <RTC>
      <Status>enabled</Status>
    </RTC>
  </Rule>
</ReplicationConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketReplication::toGetBucketReplication($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1, count($result->replicationConfiguration->rules));
        $this->assertEquals('enabled', $result->replicationConfiguration->rules[0]->rtc->status);
        $this->assertEquals('test_replication_1', $result->replicationConfiguration->rules[0]->id);
        $this->assertEquals('source1', $result->replicationConfiguration->rules[0]->prefixSet->prefixs[0]);
        $this->assertEquals('video', $result->replicationConfiguration->rules[0]->prefixSet->prefixs[1]);
        $this->assertEquals('PUT', $result->replicationConfiguration->rules[0]->action);
        $this->assertEquals(Models\TransferType::OSS_ACC, $result->replicationConfiguration->rules[0]->destination->transferType);
        $this->assertEquals('oss-cn-beijing', $result->replicationConfiguration->rules[0]->destination->location);
        $this->assertEquals('doing', $result->replicationConfiguration->rules[0]->status);
        $this->assertEquals(Models\HistoricalObjectReplicationType::ENABLED, $result->replicationConfiguration->rules[0]->historicalObjectReplication);
        $this->assertEquals('aliyunramrole', $result->replicationConfiguration->rules[0]->syncRole);
    }

    public function testFromGetBucketReplicationLocation()
    {
        // miss required field
        try {
            $request = new Models\GetBucketReplicationLocationRequest();
            $input = BucketReplication::fromGetBucketReplicationLocation($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketReplicationLocationRequest('bucket-123');
        $input = BucketReplication::fromGetBucketReplicationLocation($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketReplicationLocation()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toGetBucketReplicationLocation($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
			<ReplicationLocation>
  <Location>oss-cn-beijing</Location>
  <Location>oss-cn-qingdao</Location>
  <Location>oss-cn-shenzhen</Location>
  <Location>oss-cn-hongkong</Location>
  <Location>oss-us-west-1</Location>
  <LocationTransferTypeConstraint>
    <LocationTransferType>
      <Location>oss-cn-hongkong</Location>
        <TransferTypes>
          <Type>oss_acc</Type>          
        </TransferTypes>
      </LocationTransferType>
      <LocationTransferType>
        <Location>oss-us-west-1</Location>
        <TransferTypes>
          <Type>oss_acc</Type>
        </TransferTypes>
      </LocationTransferType>
    </LocationTransferTypeConstraint>
  </ReplicationLocation>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketReplication::toGetBucketReplicationLocation($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(5, count($result->replicationLocation->locations));
        $this->assertEquals('oss-cn-beijing', $result->replicationLocation->locations[0]);
        $this->assertEquals('oss-cn-qingdao', $result->replicationLocation->locations[1]);
        $this->assertEquals('oss-cn-shenzhen', $result->replicationLocation->locations[2]);
        $this->assertEquals('oss-cn-hongkong', $result->replicationLocation->locations[3]);
        $this->assertEquals('oss-us-west-1', $result->replicationLocation->locations[4]);
        $this->assertEquals(2, count($result->replicationLocation->locationTransferTypeConstraint->locationTransferTypes));
        $this->assertEquals('oss-cn-hongkong', $result->replicationLocation->locationTransferTypeConstraint->locationTransferTypes[0]->location);
        $this->assertEquals('oss_acc', $result->replicationLocation->locationTransferTypeConstraint->locationTransferTypes[0]->transferTypes->types[0]);
        $this->assertEquals('oss-us-west-1', $result->replicationLocation->locationTransferTypeConstraint->locationTransferTypes[1]->location);
        $this->assertEquals('oss_acc', $result->replicationLocation->locationTransferTypeConstraint->locationTransferTypes[1]->transferTypes->types[0]);
    }

    public function testFromGetBucketReplicationProgress()
    {
        // miss required field
        try {
            $request = new Models\GetBucketReplicationProgressRequest();
            $input = BucketReplication::fromGetBucketReplicationProgress($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetBucketReplicationProgressRequest('bucket-123');
            $input = BucketReplication::fromGetBucketReplicationProgress($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, ruleId", (string)$e);
        }

        $request = new Models\GetBucketReplicationProgressRequest('bucket-123', 'rule-1');
        $input = BucketReplication::fromGetBucketReplicationProgress($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketReplicationProgress()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toGetBucketReplicationProgress($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ReplicationProgress>
 <Rule>
   <ID>test_replication_1</ID>
   <PrefixSet>
    <Prefix>source_image</Prefix>
    <Prefix>video</Prefix>
   </PrefixSet>
   <Action>PUT</Action>
   <Destination>
    <Bucket>target-bucket</Bucket>
    <Location>oss-cn-beijing</Location>
    <TransferType>oss_acc</TransferType>
   </Destination>
   <Status>doing</Status>
   <HistoricalObjectReplication>enabled</HistoricalObjectReplication>
   <Progress>
    <HistoricalObject>0.85</HistoricalObject>
    <NewObject>2015-09-24T15:28:14.000Z</NewObject>
   </Progress>
 </Rule>
</ReplicationProgress>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = BucketReplication::toGetBucketReplicationProgress($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals(1, count($result->replicationProgress->rules));
        $this->assertEquals('test_replication_1', $result->replicationProgress->rules[0]->id);
        $this->assertEquals('source_image', $result->replicationProgress->rules[0]->prefixSet->prefixs[0]);
        $this->assertEquals('video', $result->replicationProgress->rules[0]->prefixSet->prefixs[1]);
        $this->assertEquals('PUT', $result->replicationProgress->rules[0]->action);
        $this->assertEquals(Models\TransferType::OSS_ACC, $result->replicationProgress->rules[0]->destination->transferType);
        $this->assertEquals('target-bucket', $result->replicationProgress->rules[0]->destination->bucket);
        $this->assertEquals('oss-cn-beijing', $result->replicationProgress->rules[0]->destination->location);
        $this->assertEquals('doing', $result->replicationProgress->rules[0]->status);
        $this->assertEquals('0.85', $result->replicationProgress->rules[0]->progress->historicalObject);
        $this->assertEquals('2015-09-24T15:28:14.000Z', $result->replicationProgress->rules[0]->progress->newObject);
    }

    public function testFromDeleteBucketReplication()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketReplicationRequest();
            $input = BucketReplication::fromDeleteBucketReplication($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteBucketReplicationRequest('bucket-123');
            $input = BucketReplication::fromDeleteBucketReplication($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, replicationRules", (string)$e);
        }

        $request = new Models\DeleteBucketReplicationRequest('bucket-123', new Models\ReplicationRules(
            ids: ['test_replication_1']
        ));
        $input = BucketReplication::fromDeleteBucketReplication($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><ReplicationRules><ID>test_replication_1</ID></ReplicationRules>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToDeleteBucketReplication()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketReplication::toGetBucketReplicationProgress($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
        );
        $result = BucketReplication::toGetBucketReplicationProgress($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
