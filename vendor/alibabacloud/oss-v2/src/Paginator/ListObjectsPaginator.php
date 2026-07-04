<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListObjects
 * Class ListObjectsPaginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListObjectsPaginator extends Paginator
{
    /**
     * Iterates over the objects.
     * @param Models\ListObjectsRequest $request The request for the ListObjects operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListObjectsResult>
     */
    public function iterPage(Models\ListObjectsRequest $request, array $args = []): \Generator
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
            $result = $this->client->listObjects($req, $args);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->marker = $result->nextMarker;
        }
    }

    /**
     * Iterates over the objects asynchronously.
     * @param Models\ListObjectsRequest $request The request for the ListObjects operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListObjectsRequest $request, array $args = []): \Generator
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
            $res = $this->client->listObjectsAsync($req, $args);
            $resIsTruncated = false;
            $resNextMarker = '';
            yield $res->then(function (Models\ListObjectsResult $result) use (&$resIsTruncated, &$resNextMarker) {
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
