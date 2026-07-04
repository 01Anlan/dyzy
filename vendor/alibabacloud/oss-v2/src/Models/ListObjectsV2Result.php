<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListObjectsV2 operation.
 * Class ListObjectsV2Result
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectsV2Result extends ResultModel
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
     * If start-after is specified in the request, the response contains StartAfter.
     * @var string|null
     */
    public ?string $startAfter;

    /**
     * If continuation-token is specified in the request, the response contains ContinuationToken.
     * @var string|null
     */
    public ?string $continuationToken;

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
     * Indicates whether the returned results are truncated. Valid values:- true- false
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * The token from which the next list operation starts. Use the value of NextContinuationToken as the value of continuation-token in the next request.
     * @var string|null
     */
    public ?string $nextContinuationToken;

    /**
     * The encoding type of the content in the response. If you specify encoding-type in the request, the values of Delimiter, StartAfter, Prefix, NextContinuationToken, and Key are encoded in the response.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The number of objects returned for this request. If delimiter is specified in the request, the value of KeyCount is the sum of the values of Key and CommonPrefixes.
     * @var int|null
     */
    public ?int $keyCount;

    /**
     * Summary of contents
     * @var array<ObjectProperties>|null
     */
    public ?array $contents;

    /**
     * Summary of contents
     * @var array<CommonPrefix>|null
     */
    public ?array $commonPrefixes;

    /**
     * ListObjectsV2Result constructor.
     * @param string|null $name The name of the bucket.
     * @param string|null $prefix The prefix in the names of the returned objects.
     * @param string|null $startAfter If start-after is specified in the request, the response contains StartAfter.
     * @param string|null $continuationToken If continuation-token is specified in the request, the response contains ContinuationToken.
     * @param int|null $maxKeys The maximum number of returned objects in the response.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param bool|null $isTruncated Indicates whether the returned results are truncated.
     * @param string|null $nextContinuationToken The token from which the next list operation starts.
     * @param string|null $encodingType The encoding type of the content in the response.
     * @param int|null $keyCount The number of objects returned for this request.
     * @param array|null $contents The container that stores the metadata of the returned objects.
     * @param array<CommonPrefix>|null $commonPrefixes Objects whose names contain the same string that ranges from the prefix to the next occurrence of the delimiter are grouped as a single result element
     */
    public function __construct(
        ?string $name = null,
        ?string $prefix = null,
        ?string $startAfter = null,
        ?string $continuationToken = null,
        ?int $maxKeys = null,
        ?string $delimiter = null,
        ?bool $isTruncated = null,
        ?string $nextContinuationToken = null,
        ?string $encodingType = null,
        ?int $keyCount = null,
        ?array $contents = null,
        ?array $commonPrefixes = null
    )
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->startAfter = $startAfter;
        $this->continuationToken = $continuationToken;
        $this->maxKeys = $maxKeys;
        $this->delimiter = $delimiter;
        $this->isTruncated = $isTruncated;
        $this->nextContinuationToken = $nextContinuationToken;
        $this->encodingType = $encodingType;
        $this->keyCount = $keyCount;
        $this->contents = $contents;
        $this->commonPrefixes = $commonPrefixes;
    }
}
