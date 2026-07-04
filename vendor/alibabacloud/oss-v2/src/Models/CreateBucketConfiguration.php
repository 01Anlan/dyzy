<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;

/**
 * Class CreateBucketConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CreateBucketConfiguration')]
final class CreateBucketConfiguration extends Model
{
    /**
     * The storage class of the bucket. Valid values:*   Standard (default)*   IA*   Archive*   ColdArchive
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'StorageClass', type: 'string')]
    public ?string $storageClass;

    /**
     * The redundancy type of the bucket.*   LRS (default)    LRS stores multiple copies of your data on multiple devices in the same zone. LRS ensures data durability and availability even if hardware failures occur on two devices.*   ZRS    ZRS stores multiple copies of your data across three zones in the same region. Even if a zone becomes unavailable due to unexpected events, such as power outages and fires, data can still be accessed.  You cannot set the redundancy type of Archive buckets to ZRS.
     * Sees DataRedundancyType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'DataRedundancyType', type: 'string')]
    public ?string $dataRedundancyType;

    /**
     * CreateBucketConfiguration constructor.
     * @param string|null $storageClass The storage class of the bucket.
     * @param string|null $dataRedundancyType The redundancy type of the bucket.
     */
    public function __construct(
        ?string $storageClass = null,
        ?string $dataRedundancyType = null
    )
    {
        $this->storageClass = $storageClass;
        $this->dataRedundancyType = $dataRedundancyType;
    }
}
