<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListBuckets operation.
 * Class ListBucketsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListBucketsResult extends ResultModel
{
    /**
     * The marker for the next ListBuckets (GetService) request. You can use the value of this parameter as the value of marker in the next ListBuckets (GetService) request to retrieve the unreturned results.
     * @var string|null
     */
    public ?string $nextMarker;

    /**
     * The container that stores the information about multiple buckets.
     * @var array<Bucket>|null
     */
    public ?array $buckets;

    /**
     * The container that stores the information about the bucket owner.
     * @var Owner|null
     */
    public ?Owner $owner;

    /**
     * The prefix contained in the names of returned buckets.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * The name of the bucket from which the buckets are returned.
     * @var string|null
     */
    public ?string $marker;

    /**
     * The maximum number of buckets that can be returned.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * Indicates whether all results are returned. Valid values:- true: All results are not returned in the response. - false: All results are returned in the response.
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * ListAllMyBucketsResult constructor.
     * @param string|null $nextMarker The marker for the next ListBuckets (GetService) request.
     * @param array<Bucket>|null $buckets The container that stores the information about multiple buckets.
     * @param Owner|null $owner The container that stores the information about the bucket owner.
     * @param string|null $prefix The prefix contained in the names of returned buckets.
     * @param string|null $marker The name of the bucket from which the buckets are returned.
     * @param int|null $maxKeys The maximum number of buckets that can be returned.
     * @param bool|null $isTruncated Indicates whether all results are returned.
     */
    public function __construct(
        ?string $nextMarker = null,
        ?array $buckets = null,
        ?Owner $owner = null,
        ?string $prefix = null,
        ?string $marker = null,
        ?int $maxKeys = null,
        ?bool $isTruncated = null
    )
    {
        $this->nextMarker = $nextMarker;
        $this->buckets = $buckets;
        $this->owner = $owner;
        $this->prefix = $prefix;
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->isTruncated = $isTruncated;
    }
}