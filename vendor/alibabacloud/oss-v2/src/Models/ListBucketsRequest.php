<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListBuckets operation.
 * Class ListBucketsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListBucketsRequest extends RequestModel
{
    /**
     * The ID of the resource group to which the bucket belongs.
     * @var string|null
     */
    public ?string $resourceGroupId;

    /**
     * The prefix that the names of returned buckets must contain. If this parameter is not specified, prefixes are not used to filter returned buckets. By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * The name of the bucket from which the buckets start to return. The buckets whose names are alphabetically after the value of marker are returned. If this parameter is not specified, all results are returned. By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $marker;

    /**
     * The maximum number of buckets that can be returned. Valid values: 1 to 1000. Default value: 100
     * @var int|null
     */
    public ?int $maxKeys;


    /**
     * A tag key of target buckets.
     * The listing results will only include Buckets that have been tagged with this key.
     * @var string|null
     */
    public ?string $tagKey;

    /**
     * A tag value for the target buckets.
     * If this parameter is specified in the request, the tag-key must also be specified.
     * The listing results will only include Buckets that have been tagged with this key-value pair.
     * @var string|null
     */
    public ?string $tagValue;

    /**
     * Tag list of target buckets.
     * Only Buckets that match all the key-value pairs in the list will added into the listing results.
     * The tagging parameter cannot be used with the tag-key and tag-value parameters in a request.
     * @var string|null
     */
    public ?string $tagging;

    /**
     * ListBucketsRequest constructor.
     * @param string|null $prefix The prefix that the names of returned buckets must contain.
     * @param string|null $marker The name of the bucket from which the buckets start to return.
     * @param int|null $maxKeys The maximum number of buckets that can be returned.
     * @param string|null $resourceGroupId The ID of the resource group to which the bucket belongs.
     * @param string|null $tagKey A tag key of target buckets.
     * @param string|null $tagValue A tag value for the target buckets.
     * @param string|null $tagging Tag list of target buckets.
     * @param array|null $options
     */
    public function __construct(
        ?string $prefix = null,
        ?string $marker = null,
        ?int $maxKeys = null,
        ?string $resourceGroupId = null,
        ?string $tagKey = null,
        ?string $tagValue = null,
        ?string $tagging = null,
        ?array $options = null
    )
    {
        $this->resourceGroupId = $resourceGroupId;
        $this->prefix = $prefix;
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->tagKey = $tagKey;
        $this->tagValue = $tagValue;
        $this->tagging = $tagging;
        parent::__construct($options);
    }
}