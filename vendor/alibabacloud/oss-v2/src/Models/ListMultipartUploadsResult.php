<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListMultipartUploads operation.
 * Class ListMultipartUploadsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListMultipartUploadsResult extends ResultModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The prefix that the returned object names must contain. If you specify a prefix in the request, the specified prefix is included in the response.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * The name of the object that corresponds to the multipart upload task after which the list begins.
     * @var string|null
     */
    public ?string $keyMarker;

    /**
     * The upload ID of the multipart upload task after which the list begins.
     * @var string|null
     */
    public ?string $uploadIdMarker;

    /**
     * The maximum number of multipart upload tasks returned by OSS.
     * @var int|null
     */
    public ?int $maxUploads;

    /**
     * The character used to group objects by name. If you specify the Delimiter parameter in the request, the response contains the CommonPrefixes element. Objects whose names contain the same string from the prefix to the next occurrence of the delimiter are grouped as a single result element in
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * Indicates whether the list of multipart upload tasks returned in the response is truncated. Default value: false. Valid values:- true: Only part of the results are returned this time.- false: All results are returned.
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * The object name marker in the response for the next request to return the remaining results.
     * @var string|null
     */
    public ?string $nextKeyMarker;

    /**
     * The NextUploadMarker value that is used for the UploadMarker value in the next request if the response does not contain all required results.
     * @var string|null
     */
    public ?string $nextUploadIdMarker;

    /**
     * The method used to encode the object name in the response. If encoding-type is specified in the request, values of those elements including Delimiter, KeyMarker, Prefix, NextKeyMarker, and Key are encoded in the returned result.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The ID list of the multipart upload tasks.
     * @var array<Upload>|null
     */
    public ?array $uploads;

    /**
     * ListMultipartUploadsResult constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $prefix The prefix that the returned object names must contain.
     * @param string|null $keyMarker The name of the object that corresponds to the multipart upload task after which the list begins.
     * @param string|null $uploadIdMarker The upload ID of the multipart upload task after which the list begins.
     * @param int|null $maxUploads The maximum number of multipart upload tasks returned by OSS.
     * @param string|null $delimiter The character used to group objects by name.
     * @param bool|null $isTruncated Indicates whether the list of multipart upload tasks returned in the response is truncated.
     * @param string|null $nextKeyMarker The object name marker in the response for the next request to return the remaining results.
     * @param string|null $nextUploadIdMarker The NextUploadMarker value that is used for the UploadMarker value in the next request if the response does not contain all required results.
     * @param string|null $encodingType The method used to encode the object name in the response.
     * @param array<Upload>|null $uploads The ID list of the multipart upload tasks.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $prefix = null,
        ?string $keyMarker = null,
        ?string $uploadIdMarker = null,
        ?int $maxUploads = null,
        ?string $delimiter = null,
        ?bool $isTruncated = null,
        ?string $nextKeyMarker = null,
        ?string $nextUploadIdMarker = null,
        ?string $encodingType = null,
        ?array $uploads = null
    )
    {
        $this->bucket = $bucket;
        $this->prefix = $prefix;
        $this->keyMarker = $keyMarker;
        $this->uploadIdMarker = $uploadIdMarker;
        $this->maxUploads = $maxUploads;
        $this->delimiter = $delimiter;
        $this->isTruncated = $isTruncated;
        $this->nextKeyMarker = $nextKeyMarker;
        $this->nextUploadIdMarker = $nextUploadIdMarker;
        $this->encodingType = $encodingType;
        $this->uploads = $uploads;
    }
}
