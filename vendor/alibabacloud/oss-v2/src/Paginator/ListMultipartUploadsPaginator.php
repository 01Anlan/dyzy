<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Paginator;

use AlibabaCloud\Oss\V2\Models;

/**
 * A paginator for ListMultipartUploads
 * Class ListMultipartUploadsPaginator
 * @package AlibabaCloud\Oss\V2\Paginator
 */
final class ListMultipartUploadsPaginator extends Paginator
{
    /**
     * Iterates over the multipart uploads.
     * @param Models\ListMultipartUploadsRequest $request The request for the ListMultipartUploads operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<Models\ListMultipartUploadsResult>
     */
    public function iterPage(Models\ListMultipartUploadsRequest $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? ($this->limit ?? null);
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxUploads = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $result = $this->client->listMultipartUploads($req);
            yield $result;

            $firstPage = false;
            $isTruncated = $result->isTruncated ?? false;
            $req->uploadIdMarker = $result->nextUploadIdMarker;
            $req->keyMarker = $result->nextKeyMarker;
        }
    }

    /**
     * Iterates over the multipart uploads asynchronously.
     * @param Models\ListMultipartUploadsRequest $request The request for the ListMultipartUploads operation.
     * @param array $args accepts the following:
     * - limit int: The maximum number of items in the response.
     *   For example, ['limit' => 10]
     * @return \Generator<\GuzzleHttp\Promise\Promise>
     */
    public function iterPageAsync(Models\ListMultipartUploadsRequest $request, array $args = []): \Generator
    {
        $limit = $args['limit'] ?? $this->limit;
        if (isset($args['limit'])) {
            unset($args['limit']);
        }
        $req = clone $request;
        if (isset($limit) && is_int($limit)) {
            $req->maxUploads = $limit;
        }

        $firstPage = true;
        $isTruncated = false;

        while ($firstPage || $isTruncated) {
            $res = $this->client->listMultipartUploadsAsync($req, $args);
            $resIsTruncated = false;
            $resNextKeyMarker = '';
            $resNextUploadIdMarker = '';
            yield $res->then(function (Models\ListMultipartUploadsResult $result) use (&$resIsTruncated, &$resNextKeyMarker, &$resNextUploadIdMarker) {
                $resIsTruncated = $result->isTruncated ?? false;
                $resNextKeyMarker = $result->nextKeyMarker;
                $resNextUploadIdMarker = $result->nextUploadIdMarker;
                return $result;
            });

            $firstPage = false;
            $isTruncated = $resIsTruncated;
            $req->keyMarker = $resNextKeyMarker;
            $req->uploadIdMarker = $resNextUploadIdMarker;
        }
    }
}    
