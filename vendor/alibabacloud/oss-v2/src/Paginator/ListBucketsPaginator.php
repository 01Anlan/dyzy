<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListBuckets
 * Class ListBucketsPaginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListBucketsPaginator extends Paginator
{
    /**
     * Iterates over the buckets.
     * @param Models\ListBucketsRequest $request The request for the ListBuckets operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListBucketsResult>
     */
    public function iterPage(Models\ListBucketsRequest $request, array $args = []): \Generator
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
            $result = $this->client->listBuckets($req, $args);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->marker = $result->nextMarker;
        }
    }

    /**
     * Iterates over the buckets asynchronously.
     * @param Models\ListBucketsRequest $request The request for the ListBuckets operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListBucketsRequest $request, array $args = []): \Generator
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
            $res = $this->client->listBucketsAsync($req, $args);
            $resIsTruncated = false;
            $resNextMarker = '';
            yield $res->then(function (Models\ListBucketsResult $result) use (&$resIsTruncated, &$resNextMarker) {
                $resIsTruncated = $result->isTruncated ?? false;
                $resNextMarker = $result->nextMarker;
                return $result;
            });

            $firstPage = false;
            $isTruncated = $resIsTruncated;
            $req->marker = $resNextMarker;
        }
    }
}    
