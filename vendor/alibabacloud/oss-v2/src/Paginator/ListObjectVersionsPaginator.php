<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListObjectVersions
 * Class ListObjectVersionsPaginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListObjectVersionsPaginator extends Paginator
{
    /**
     * Iterates over the object versions.
     * @param Models\ListObjectVersionsRequest $request The request for the ListObjectVersions operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListObjectVersionsResult>
     */
    public function iterPage(Models\ListObjectVersionsRequest $request, array $args = []): \Generator
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
            $result = $this->client->listObjectVersions($req, $args);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->keyMarker = $result->nextKeyMarker;
            $req->versionIdMarker = $result->nextVersionIdMarker;
        }
    }

    /**
     * Iterates over the object versions.
     * @param Models\ListObjectVersionsRequest $request The request for the ListObjectVersions operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListObjectVersionsRequest $request, array $args = []): \Generator
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
            $res = $this->client->listObjectVersionsAsync($req, $args);
            $resIsTruncated = false;
            $resKeyMarker = '';
            $resVersionIdMarker = '';
            yield $res->then(function (Models\ListObjectVersionsResult $result) use (&$resIsTruncated, &$resKeyMarker, &$resVersionIdMarker) {
                $resIsTruncated = $result->isTruncated ?? false;
                $resKeyMarker = $result->nextKeyMarker;
                $resVersionIdMarker = $result->nextVersionIdMarker;
                return $result;
            });

            $firstPage = false;
            $isTruncated = $resIsTruncated;
            $req->keyMarker = $resKeyMarker;
            $req->versionIdMarker = $resVersionIdMarker;
        }
    }
}    
