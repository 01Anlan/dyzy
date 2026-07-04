<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ListCloudBoxes
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ListCloudBoxResult')]
final class ListCloudBoxes extends Model
{
    /**
     * The name of the bucket after which the ListBuckets  operation starts.
     * @var string|null
     */
    #[XmlElement(rename: 'Marker', type: 'string')]
    public ?string $marker;

    /**
     * The maximum number of buckets that can be returned in the single query.
     * Valid values: 1 to 1000.
     * @var int|null
     */
    #[XmlElement(rename: 'MaxKeys', type: 'int')]
    public ?int $maxKeys;

    /**
     * The prefix that the names of returned buckets must contain.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * Indicates whether all results are returned.
     * true: Only part of the results are returned for the request.
     * false: All results are returned for the request.
     * @var bool|null
     */
    #[XmlElement(rename: 'IsTruncated', type: 'bool')]
    public ?bool $isTruncated;

    /**
     * The marker for the next ListBuckets request, which can be used to return the remaining results.
     * @var string|null
     */
    #[XmlElement(rename: 'NextMarker', type: 'string')]
    public ?string $nextMarker;

    /**
     * The container that stores information about the bucket owner.
     * @var Owner|null
     */
    #[XmlElement(rename: 'Owner', type: Owner::class)]
    public ?Owner $owner;

    /**
     * The container that is used to store the information about all CNAME records.
     * @var CloudBoxes|null
     */
    #[XmlElement(rename: 'CloudBoxes', type: CloudBoxes::class)]
    public ?CloudBoxes $cloudBoxes;

    /**
     * ListCloudBoxes constructor.
     * @param string|null $prefix The prefix contained in the names of the returned bucket.
     * @param string|null $marker The name of the bucket after which the ListBuckets  operation starts.
     * @param int|null $maxKeys The maximum number of buckets that can be returned for the request.
     * @param bool|null $isTruncated Indicates whether all results are returned.
     * @param string|null $nextMarker The marker for the next ListBuckets request, which can be used to return the remaining results.
     * @param Owner|null $owner The container that stores information about the bucket owner.
     * @param CloudBoxes|null $cloudBoxes The container that stores information about cloud box bucket.
     */
    public function __construct(
        ?string $prefix = null,
        ?string $marker = null,
        ?int $maxKeys = null,
        ?bool $isTruncated = null,
        ?string $nextMarker = null,
        ?Owner $owner = null,
        ?CloudBoxes $cloudBoxes = null
    )
    {
        $this->prefix = $prefix;
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->isTruncated = $isTruncated;
        $this->nextMarker = $nextMarker;
        $this->owner = $owner;
        $this->cloudBoxes = $cloudBoxes;
    }
}
