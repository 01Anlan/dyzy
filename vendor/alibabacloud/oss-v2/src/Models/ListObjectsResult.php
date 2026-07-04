<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListObjects operation.
 * Class ListObjectsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectsResult extends ResultModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $name;

    /**
     * The prefix in the names of the returned objects.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * The name of the object after which the GetBucket (ListObjects) operation begins.
     * @var string|null
     */
    public ?string $marker;

    /**
     * The maximum number of returned objects in the response.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * The character that is used to group objects by name. The objects whose names contain the same string from the prefix to the next occurrence of the delimiter are grouped as a single result element in CommonPrefixes.
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * Indicates whether the returned list in the result is truncated. Valid values:- true- false
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * If not all results are returned, NextMarker is included in the response to indicate the value of marker in the next request.
     * @var string|null
     */
    public ?string $nextMarker;

    /**
     * The encoding type of the content in the response. If you specify encoding-type in the request, the values of Delimiter, Marker, Prefix, NextMarker, and Key are encoded in the response.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The container that stores the information about the returned objects.
     * @var array<ObjectProperties>|null
     */
    public ?array $contents;

    /**
     * Summary of contents
     * @var array<CommonPrefix>|null
     */
    public ?array $commonPrefixes;

    /**
     * ListObjectsResult constructor.
     * @param string|null $name The name of the bucket.
     * @param string|null $prefix The prefix in the names of the returned objects.
     * @param string|null $marker The name of the object after which the GetBucket (ListObjects) operation begins.
     * @param int|null $maxKeys The maximum number of returned objects in the response.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param bool|null $isTruncated $isTruncated Indicates whether the returned list in the result is truncated.
     * @param string|null $encodingType The encoding type of the content in the response.
     * @param string|null $nextMarker If not all results are returned, NextMarker is included in the response to indicate the value of marker in the next request.
     * @param array|null $contents The container that stores the metadata of the returned objects.
     * @param array<CommonPrefix>|null $commonPrefixes If delimiter is specified in the request, the response contains CommonPrefixes.
     */
    public function __construct(
        ?string $name = null,
        ?string $prefix = null,
        ?string $marker = null,
        ?int $maxKeys = null,
        ?string $delimiter = null,
        ?bool $isTruncated = null,
        ?string $encodingType = null,
        ?string $nextMarker = null,
        ?array $contents = null,
        ?array $commonPrefixes = null
    )
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->delimiter = $delimiter;
        $this->isTruncated = $isTruncated;
        $this->encodingType = $encodingType;
        $this->nextMarker = $nextMarker;
        $this->contents = $contents;
        $this->commonPrefixes = $commonPrefixes;
    }
}
