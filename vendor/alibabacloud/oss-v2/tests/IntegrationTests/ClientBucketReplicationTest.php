<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketReplicationTest extends TestIntegration
{
    public function testBucketReplication()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;
        $targetBucketName = self::randomBucketName() . '-target';
        $cfg = Oss\Config::loadDefault();
        $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
            self::$ACCESS_ID,
            self::$ACCESS_KEY
        ));
        $cfg->setRegion('cn-beijing');
        $client1 = new Oss\Client($cfg);
        $client1->putBucket(new Oss\Models\PutBucketRequest(
            $targetBucketName
        ));

        // PutBucketReplication
        $putResult = $client->putBucketReplication(new Oss\Models\PutBucketReplicationRequest(
            $bucketName,
            new Oss\Models\ReplicationConfiguration(
                array(
                    new Oss\Models\ReplicationRule(
                        sourceSelectionCriteria: new Oss\Models\ReplicationSourceSelectionCriteria(
                        sseKmsEncryptedObjects: new Oss\Models\SseKmsEncryptedObjects(
                            status: 'Enabled'
                        )
                    ),
                        destination: new Oss\Models\ReplicationDestination(
                        bucket: $targetBucketName,
                        location: 'oss-cn-beijing',
                        transferType: Oss\Models\TransferType::INTERNAL
                    ),
                        historicalObjectReplication: Oss\Models\HistoricalObjectReplicationType::ENABLED,
                        rtc: new Oss\Models\ReplicationTimeControl(status: 'enabled')
                    )
                )
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketReplication
        $getResult = $client->getBucketReplication(new Oss\Models\GetBucketReplicationRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // GetBucketReplicationLocation
        $getLocationResult = $client->getBucketReplicationLocation(new Oss\Models\GetBucketReplicationLocationRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getLocationResult->statusCode);
        $this->assertEquals('OK', $getLocationResult->status);
        $this->assertEquals(True, count($getLocationResult->headers) > 0);
        $this->assertEquals(24, strlen($getLocationResult->requestId));

        // GetBucketReplicationProgress
        $getProgressResult = $client->getBucketReplicationProgress(new Oss\Models\GetBucketReplicationProgressRequest(
            $bucketName,
            $getResult->replicationConfiguration->rules[0]->id
        ));
        $this->assertEquals(200, $getProgressResult->statusCode);
        $this->assertEquals('OK', $getProgressResult->status);
        $this->assertEquals(True, count($getProgressResult->headers) > 0);
        $this->assertEquals(24, strlen($getProgressResult->requestId));

        // PutBucketRtc
        $putRtcResult = $client->putBucketRtc(new Oss\Models\PutBucketRtcRequest(
            $bucketName,
            rtcConfiguration: new Oss\Models\RtcConfiguration(
            rtc: new Oss\Models\ReplicationTimeControl(
            status: 'disabled'
        ),
            id: $getResult->replicationConfiguration->rules[0]->id
        ),
        ));
        $this->assertEquals(200, $putRtcResult->statusCode);
        $this->assertEquals('OK', $putRtcResult->status);
        $this->assertEquals(True, count($putRtcResult->headers) > 0);
        $this->assertEquals(24, strlen($putRtcResult->requestId));

        // DeleteBucketReplication
        $delResult = $client->deleteBucketReplication(new Oss\Models\DeleteBucketReplicationRequest(
            $bucketName,
            replicationRules: new Oss\Models\ReplicationRules(
                ids: [$getResult->replicationConfiguration->rules[0]->id]
            )
        ),
        );
        $this->assertEquals(200, $delResult->statusCode);
        $this->assertEquals('OK', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
        $client1->deleteBucket(new Oss\Models\DeleteBucketRequest(
            $targetBucketName
        ));
    }

    public function testBucketReplicationFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketReplication
        try {
            $putResult = $client->putBucketReplication(new Oss\Models\PutBucketReplicationRequest(
                $bucketName,
                new Oss\Models\ReplicationConfiguration(
                    array(
                        new Oss\Models\ReplicationRule(
                            sourceSelectionCriteria: new Oss\Models\ReplicationSourceSelectionCriteria(
                            sseKmsEncryptedObjects: new Oss\Models\SseKmsEncryptedObjects(
                                status: 'Enabled'
                            )
                        ),
                            destination: new Oss\Models\ReplicationDestination(
                            bucket: 'bucket-not-exist',
                            location: 'oss-cn-beijing',
                            transferType: Oss\Models\TransferType::INTERNAL
                        ),
                            historicalObjectReplication: Oss\Models\HistoricalObjectReplicationType::ENABLED,
                            rtc: new Oss\Models\ReplicationTimeControl(status: 'enabled')
                        )
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketReplication', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketReplication
        try {
            $getResult = $client->getBucketReplication(new Oss\Models\GetBucketReplicationRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketReplication', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getLocationResult = $client->getBucketReplicationLocation(new Oss\Models\GetBucketReplicationLocationRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketReplicationLocation', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $getProgressResult = $client->getBucketReplicationProgress(new Oss\Models\GetBucketReplicationProgressRequest(
                $bucketName,
                'rule-id'
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketReplicationProgress', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $putRtcResult = $client->putBucketRtc(new Oss\Models\PutBucketRtcRequest(
                $bucketName,
                rtcConfiguration: new Oss\Models\RtcConfiguration(
                rtc: new Oss\Models\ReplicationTimeControl(
                status: 'disabled'
            ),
                id: 'rule-id'
            ),
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketRtc', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        try {
            $delResult = $client->deleteBucketReplication(new Oss\Models\DeleteBucketReplicationRequest(
                $bucketName,
                replicationRules: new Oss\Models\ReplicationRules(
                    ids: ['rule-id']
                )
            ),
            );
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketReplication', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }
}