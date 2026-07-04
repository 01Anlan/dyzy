<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketMetaQueryTest extends TestIntegration
{
    public function testBucketMetaQuery()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // OpenMetaQuery
        $putResult = $client->openMetaQuery(new Oss\Models\OpenMetaQueryRequest(
            $bucketName,
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetMetaQueryStatus
        $getResult = $client->getMetaQueryStatus(new Oss\Models\GetMetaQueryStatusRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DoMetaQuery
        $request = new Oss\Models\DoMetaQueryRequest($bucketName, new Oss\Models\MetaQuery(
            maxResults: 5,
            query: "{'Field': 'Size','Value': '1048576','Operation': 'gt'}",
            sort: 'Size',
            order: Oss\Models\MetaQueryOrderType::ASC,
            aggregations: new Oss\Models\MetaQueryAggregations(
            [
                new Oss\Models\MetaQueryAggregation(
                    field: 'Size',
                    operation: 'sum'
                ),
                new Oss\Models\MetaQueryAggregation(
                    field: 'Size',
                    operation: 'max'
                ),
            ]
        ),
        ));
        $doResult = $client->doMetaQuery($request);
        $this->assertEquals(200, $doResult->statusCode);
        $this->assertEquals('OK', $doResult->status);
        $this->assertEquals(True, count($doResult->headers) > 0);
        $this->assertEquals(24, strlen($doResult->requestId));

        // CloseMetaQuery
        $closeResult = $client->closeMetaQuery(new Oss\Models\CloseMetaQueryRequest($bucketName));
        $this->assertEquals(200, $closeResult->statusCode);
        $this->assertEquals('OK', $closeResult->status);
        $this->assertEquals(True, count($closeResult->headers) > 0);
        $this->assertEquals(24, strlen($closeResult->requestId));

        $bucketNameAiSearch = self::$BUCKETNAME_PREFIX . strval(rand(0, 100)) . '-' . strval(time()) . '-semantic';
        $client->putBucket(new Oss\Models\PutBucketRequest($bucketNameAiSearch));
        // OpenMetaQuery with semantic
        $putResult = $client->openMetaQuery(new Oss\Models\OpenMetaQueryRequest(
            $bucketNameAiSearch, 'semantic'
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // DoMetaQuery with semantic
        $request = new Oss\Models\DoMetaQueryRequest($bucketNameAiSearch, new Oss\Models\MetaQuery(
            maxResults: 99,
            query: "Overlook the snow-covered forest",
            mediaTypes: new Oss\Models\MetaQueryMediaTypes('image'),
            simpleQuery: '{"Operation":"gt", "Field": "Size", "Value": "30"}',
        ), 'semantic');
        $doResult = $client->doMetaQuery($request);
        $this->assertEquals(200, $doResult->statusCode);
        $this->assertEquals('OK', $doResult->status);
        $this->assertEquals(True, count($doResult->headers) > 0);
        $this->assertEquals(24, strlen($doResult->requestId));

        $client->deleteBucket(new Oss\Models\DeleteBucketRequest($bucketNameAiSearch));
    }

    public function testBucketMetaQueryFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // OpenMetaQuery
        try {
            $putResult = $client->openMetaQuery(new Oss\Models\OpenMetaQueryRequest(
                $bucketName,
            ));
            $this->assertEquals(200, $putResult->statusCode);
            $this->assertEquals('OK', $putResult->status);
            $this->assertEquals(True, count($putResult->headers) > 0);
            $this->assertEquals(24, strlen($putResult->requestId));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error OpenMetaQuery', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetMetaQueryStatus
        try {
            $getResult = $client->getMetaQueryStatus(new Oss\Models\GetMetaQueryStatusRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetMetaQueryStatus', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DoMetaQuery
        try {
            $request = new Oss\Models\DoMetaQueryRequest($bucketName, new Oss\Models\MetaQuery(
                maxResults: 5,
                query: "{'Field': 'Size','Value': '1048576','Operation': 'gt'}",
                sort: 'Size',
                order: Oss\Models\MetaQueryOrderType::ASC,
                aggregations: new Oss\Models\MetaQueryAggregations(
                [
                    new Oss\Models\MetaQueryAggregation(
                        field: 'Size',
                        operation: 'sum'
                    ),
                    new Oss\Models\MetaQueryAggregation(
                        field: 'Size',
                        operation: 'max'
                    ),
                ]
            ),
            ));
            $doResult = $client->doMetaQuery($request);
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DoMetaQuery', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // CloseMetaQuery
        try {
            $closeResult = $client->closeMetaQuery(new Oss\Models\CloseMetaQueryRequest($bucketName));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error CloseMetaQuery', $e);
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