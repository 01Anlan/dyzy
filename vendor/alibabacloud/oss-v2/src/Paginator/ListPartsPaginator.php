<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListParts
 * Class ListPartsPaginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListPartsPaginator extends Paginator
{
    /**
     * Iterates over the parts.
     * @param Models\ListPartsRequest $request The request for the ListParts operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListPartsResult>
     */
    public function iterPage(Models\ListPartsRequest $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? ($this->limit ?? null);
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxParts = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $result = $this->client->listParts($req, $args);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->partNumberMarker = $result->nextPartNumberMarker;
        }
    }

    /**
     * Iterates over the parts asynchronously.
     * @param Models\ListPartsRequest $request The request for the ListParts operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListPartsRequest $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? $this->limit;
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxParts = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $res = $this->client->listPartsAsync($req, $args);
            $resIsTruncated = false;
            $resNextPartNumberMarker = '';
            yield $res->then(function (Models\ListPartsResult $result) use (&$resIsTruncated, &$resNextPartNumberMarker) {
                $resIsTruncated = $result->isTruncated ?? false;
                $resNextPartNumberMarker = $result->nextPartNumberMarker;
                return $result;
            });

            $firstPage = false;
            $isTruncated = $resIsTruncated;
            $req->partNumberMarker = $resNextPartNumberMarker;
        }
    }
}    
