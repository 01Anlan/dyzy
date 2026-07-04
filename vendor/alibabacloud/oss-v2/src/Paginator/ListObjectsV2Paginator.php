<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListObjectsV2
 * Class ListObjectsV2Paginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListObjectsV2Paginator extends Paginator
{
    /**
     * Iterates over the objects.
     * @param Models\ListObjectsV2Request $request The request for the ListObjectsV2 operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListObjectsV2Result>
     */
    public function iterPage(Models\ListObjectsV2Request $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? ($this->limit ?? null);
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxKeys = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $result = $this->client->listObjectsV2($req, $args);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->continuationToken = $result->nextContinuationToken;
        }
    }

    /**
     * Iterates over the objects asynchronously.
     * @param Models\ListObjectsV2Request $request The request for the ListObjectsV2 operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListObjectsV2Request $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? $this->limit;
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxKeys = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $res = $this->client->listObjectsV2Async($req, $args);
            $resIsTruncated = false;
            $resNextContinuationToken = '';
            yield $res->then(function (Models\ListObjectsV2Result $result) use (&$resIsTruncated, &$resNextContinuationToken) {
                $resIsTruncated = $result->isTruncated ?? false;
                $resNextContinuationToken = $result->nextContinuationToken;
                return $result;
            });

            $firstPage = false;
            $isTruncated = $resIsTruncated;
            $req->continuationToken = $resNextContinuationToken;
        }
    }
}    
