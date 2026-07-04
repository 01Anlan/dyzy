<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListParts operation.
 * Class ListPartsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListPartsResult extends ResultModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The ID of the upload task.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The position from which the list starts. All parts whose part numbers are greater than the value of this parameter are listed.
     * @var string|null
     */
    public ?string $partNumberMarker;

    /**
     * The NextPartNumberMarker value that is used for the PartNumberMarker value in a subsequent request when the response does not contain all required results.
     * @var string|null
     */
    public ?string $nextPartNumberMarker;

    /**
     * The maximum number of parts in the response.
     * @var int|null
     */
    public ?int $maxParts;

    /**
     * Indicates whether the list of parts returned in the response has been truncated. A value of true indicates that the response does not contain all required results. A value of false indicates that the response contains all required results.Valid values: true and false.
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * The storage class of the object.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The method used to encode the object name in the response.
     * If encoding-type is specified in the request, values of those elements including
     * Delimiter, KeyMarker, Prefix, NextKeyMarker, and Key are encoded in the returned result.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The list of all parts.
     * @var array<Part>|null
     */
    public ?array $parts;

    /**
     * ListPartsResult constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param string|null $uploadId The ID of the upload task.
     * @param string|null $partNumberMarker The position from which the list starts.
     * @param string|null $nextPartNumberMarker The NextPartNumberMarker value that is used for the PartNumberMarker value in a subsequent request when the response does not contain all required results.
     * @param int|null $maxParts The maximum number of parts in the response.
     * @param bool|null $isTruncated Indicates whether the list of parts returned in the response has been truncated.
     * @param string|null $storageClass The storage class of the object.
     * @param string|null $encodingType The method used to encode the object name in the response.
     * @param array|null $parts The list of all parts.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $uploadId = null,
        ?string $partNumberMarker = null,
        ?string $nextPartNumberMarker = null,
        ?int $maxParts = null,
        ?bool $isTruncated = null,
        ?string $storageClass = null,
        ?string $encodingType = null,
        ?array $parts = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->partNumberMarker = $partNumberMarker;
        $this->nextPartNumberMarker = $nextPartNumberMarker;
        $this->maxParts = $maxParts;
        $this->isTruncated = $isTruncated;
        $this->storageClass = $storageClass;
        $this->encodingType = $encodingType;
        $this->parts = $parts;
    }
}
