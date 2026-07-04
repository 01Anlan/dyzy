<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketRedundancyTransition;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketRedundancyTransitionTest extends \PHPUnit\Framework\TestCase
{
    public function testFromCreateBucketDataRedundancyTransition()
    {
        // miss required field 
        try {
            $request = new Models\CreateBucketDataRedundancyTransitionRequest();
            $input = BucketRedundancyTransition::fromCreateBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CreateBucketDataRedundancyTransitionRequest('bucket-123');
            $input = BucketRedundancyTransition::fromCreateBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, targetRedundancyType", (string)$e);
        }

        $request = new Models\CreateBucketDataRedundancyTransitionRequest('bucket-123', Models\DataRedundancyType::ZRS);
        $input = BucketRedundancyTransition::fromCreateBucketDataRedundancyTransition($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals(Models\DataRedundancyType::ZRS, $input->getParameters()['x-oss-target-redundancy-type']);
    }

    public function testToCreateBucketDataRedundancyTransition()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRedundancyTransition::toCreateBucketDataRedundancyTransition($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = <<<BBB
<?xml version="1.0" encoding="UTF-8"?>
			<BucketDataRedundancyTransition>
			  <TaskId>4be5beb0f74f490186311b268bf6****</TaskId>
			</BucketDataRedundancyTransition>
BBB;

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toCreateBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('4be5beb0f74f490186311b268bf6****', $result->bucketDataRedundancyTransition->taskId);
    }

    public function testFromGetBucketDataRedundancyTransition()
    {
        // miss required field
        try {
            $request = new Models\GetBucketDataRedundancyTransitionRequest();
            $input = BucketRedundancyTransition::fromGetBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetBucketDataRedundancyTransitionRequest('bucket-123');
            $input = BucketRedundancyTransition::fromGetBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, redundancyTransitionTaskid", (string)$e);
        }

        $request = new Models\GetBucketDataRedundancyTransitionRequest('bucket-123', 'task-123');
        $input = BucketRedundancyTransition::fromGetBucketDataRedundancyTransition($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('task-123', $input->getParameters()['x-oss-redundancy-transition-taskid']);
    }

    public function testToGetBucketDataRedundancyTransition()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRedundancyTransition::toGetBucketDataRedundancyTransition($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>4be5beb0f74f490186311b268bf6****</TaskId>
  <Status>Queueing</Status>
  <CreateTime>2023-11-17T09:11:58.000Z</CreateTime>
</BucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toGetBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->bucketDataRedundancyTransition->bucket);
        $this->assertEquals('4be5beb0f74f490186311b268bf6****', $result->bucketDataRedundancyTransition->taskId);
        $this->assertEquals('Queueing', $result->bucketDataRedundancyTransition->status);
        $this->assertEquals('2023-11-17T09:11:58.000Z', $result->bucketDataRedundancyTransition->createTime);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Processing</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>0</ProcessPercentage>
  <EstimatedRemainingTime>100</EstimatedRemainingTime>
</BucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toGetBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->bucketDataRedundancyTransition->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->bucketDataRedundancyTransition->taskId);
        $this->assertEquals('Processing', $result->bucketDataRedundancyTransition->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->bucketDataRedundancyTransition->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->bucketDataRedundancyTransition->startTime);
        $this->assertEquals(0, $result->bucketDataRedundancyTransition->processPercentage);
        $this->assertEquals(100, $result->bucketDataRedundancyTransition->estimatedRemainingTime);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Finished</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>100</ProcessPercentage>
  <EstimatedRemainingTime>0</EstimatedRemainingTime>
  <EndTime>2023-11-18T09:14:39.000Z</EndTime>
</BucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toGetBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->bucketDataRedundancyTransition->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->bucketDataRedundancyTransition->taskId);
        $this->assertEquals('Finished', $result->bucketDataRedundancyTransition->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->bucketDataRedundancyTransition->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->bucketDataRedundancyTransition->startTime);
        $this->assertEquals(100, $result->bucketDataRedundancyTransition->processPercentage);
        $this->assertEquals(0, $result->bucketDataRedundancyTransition->estimatedRemainingTime);
        $this->assertEquals('2023-11-18T09:14:39.000Z', $result->bucketDataRedundancyTransition->endTime);
    }

    public function testFromDeleteBucketDataRedundancyTransition()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketDataRedundancyTransitionRequest();
            $input = BucketRedundancyTransition::fromDeleteBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteBucketDataRedundancyTransitionRequest('bucket-123');
            $input = BucketRedundancyTransition::fromDeleteBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, redundancyTransitionTaskid", (string)$e);
        }

        $request = new Models\DeleteBucketDataRedundancyTransitionRequest('bucket-123', 'task-123');
        $input = BucketRedundancyTransition::fromDeleteBucketDataRedundancyTransition($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('task-123', $input->getParameters()['x-oss-redundancy-transition-taskid']);
    }

    public function testToDeleteBucketDataRedundancyTransition()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRedundancyTransition::toDeleteBucketDataRedundancyTransition($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketRedundancyTransition::toDeleteBucketDataRedundancyTransition($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromListBucketDataRedundancyTransition()
    {
        // miss required field
        try {
            $request = new Models\ListBucketDataRedundancyTransitionRequest();
            $input = BucketRedundancyTransition::fromListBucketDataRedundancyTransition($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\ListBucketDataRedundancyTransitionRequest('bucket-123');
        $input = BucketRedundancyTransition::fromListBucketDataRedundancyTransition($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToListBucketDataRedundancyTransition()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListBucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>4be5beb0f74f490186311b268bf6****</TaskId>
  <Status>Queueing</Status>
  <CreateTime>2023-11-17T09:11:58.000Z</CreateTime>
</BucketDataRedundancyTransition>
</ListBucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->bucket);
        $this->assertEquals('4be5beb0f74f490186311b268bf6****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->taskId);
        $this->assertEquals('Queueing', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->status);
        $this->assertEquals('2023-11-17T09:11:58.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->createTime);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListBucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Processing</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>0</ProcessPercentage>
  <EstimatedRemainingTime>100</EstimatedRemainingTime>
</BucketDataRedundancyTransition>
</ListBucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->taskId);
        $this->assertEquals('Processing', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->startTime);
        $this->assertEquals(0, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->processPercentage);
        $this->assertEquals(100, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->estimatedRemainingTime);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListBucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Finished</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>100</ProcessPercentage>
  <EstimatedRemainingTime>0</EstimatedRemainingTime>
  <EndTime>2023-11-18T09:14:39.000Z</EndTime>
</BucketDataRedundancyTransition>
</ListBucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('examplebucket', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->taskId);
        $this->assertEquals('Finished', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->startTime);
        $this->assertEquals(100, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->processPercentage);
        $this->assertEquals(0, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->estimatedRemainingTime);
        $this->assertEquals('2023-11-18T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->endTime);
    }

    public function testFromListUserDataRedundancyTransition()
    {
        $request = new Models\ListUserDataRedundancyTransitionRequest();
        $input = BucketRedundancyTransition::fromListUserDataRedundancyTransition($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        $request = new Models\ListUserDataRedundancyTransitionRequest('token');
        $input = BucketRedundancyTransition::fromListUserDataRedundancyTransition($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('token', $input->getParameters()['continuation-token']);

        $request = new Models\ListUserDataRedundancyTransitionRequest('token', '100');
        $input = BucketRedundancyTransition::fromListUserDataRedundancyTransition($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('token', $input->getParameters()['continuation-token']);
        $this->assertEquals(100, $input->getParameters()['max-keys']);
    }

    public function testToListUserDataRedundancyTransition()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListBucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket</Bucket>
  <TaskId>4be5beb0f74f490186311b268bf6****</TaskId>
  <Status>Queueing</Status>
  <CreateTime>2023-11-17T09:11:58.000Z</CreateTime>
</BucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket1</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Processing</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>0</ProcessPercentage>
  <EstimatedRemainingTime>100</EstimatedRemainingTime>
</BucketDataRedundancyTransition>
<BucketDataRedundancyTransition>
  <Bucket>examplebucket2</Bucket>
  <TaskId>909c6c818dd041d1a44e0fdc66aa****</TaskId>
  <Status>Finished</Status>
  <CreateTime>2023-11-17T09:14:39.000Z</CreateTime>
  <StartTime>2023-11-17T09:14:39.000Z</StartTime>
  <ProcessPercentage>100</ProcessPercentage>
  <EstimatedRemainingTime>0</EstimatedRemainingTime>
  <EndTime>2023-11-18T09:14:39.000Z</EndTime>
</BucketDataRedundancyTransition>
<IsTruncated>false</IsTruncated>
<NextContinuationToken></NextContinuationToken>
</ListBucketDataRedundancyTransition>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketRedundancyTransition::toListBucketDataRedundancyTransition($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals(3, count($result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions));
        $this->assertEquals('examplebucket', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->bucket);
        $this->assertEquals('4be5beb0f74f490186311b268bf6****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->taskId);
        $this->assertEquals('Queueing', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->status);
        $this->assertEquals('2023-11-17T09:11:58.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[0]->createTime);

        $this->assertEquals('examplebucket1', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->taskId);
        $this->assertEquals('Processing', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->startTime);
        $this->assertEquals(0, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->processPercentage);
        $this->assertEquals(100, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[1]->estimatedRemainingTime);

        $this->assertEquals('examplebucket2', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->bucket);
        $this->assertEquals('909c6c818dd041d1a44e0fdc66aa****', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->taskId);
        $this->assertEquals('Finished', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->status);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->createTime);
        $this->assertEquals('2023-11-17T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->startTime);
        $this->assertEquals(100, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->processPercentage);
        $this->assertEquals(0, $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->estimatedRemainingTime);
        $this->assertEquals('2023-11-18T09:14:39.000Z', $result->listBucketDataRedundancyTransition->bucketDataRedundancyTransitions[2]->endTime);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
