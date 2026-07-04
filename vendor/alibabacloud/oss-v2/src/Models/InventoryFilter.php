<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InventoryFilter
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InventoryFilter')]
final class InventoryFilter extends Model
{
    /**
     * The prefix that is specified in the inventory.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * The beginning of the time range during which the object was last modified. Unit: seconds.Valid values: [1262275200, 253402271999]
     * @var int|null
     */
    #[XmlElement(rename: 'LastModifyBeginTimeStamp', type: 'int')]
    public ?int $lastModifyBeginTimeStamp;

    /**
     * The end of the time range during which the object was last modified. Unit: seconds.Valid values: [1262275200, 253402271999]
     * @var int|null
     */
    #[XmlElement(rename: 'LastModifyEndTimeStamp', type: 'int')]
    public ?int $lastModifyEndTimeStamp;

    /**
     * The minimum size of the specified object. Unit: B.Valid values: [0 B, 48.8 TB]
     * @var int|null
     */
    #[XmlElement(rename: 'LowerSizeBound', type: 'int')]
    public ?int $lowerSizeBound;

    /**
     * The maximum size of the specified object. Unit: B.Valid values: (0 B, 48.8 TB]
     * @var int|null
     */
    #[XmlElement(rename: 'UpperSizeBound', type: 'int')]
    public ?int $upperSizeBound;

    /**
     * The storage class of the object. You can specify multiple storage classes.Valid values:StandardIAArchiveColdArchiveAll
     * @var string|null
     */
    #[XmlElement(rename: 'StorageClass', type: 'string')]
    public ?string $storageClass;

    /**
     * InventoryFilter constructor.
     * @param string|null $prefix The prefix that is specified in the inventory.
     * @param int|null $lastModifyBeginTimeStamp The beginning of the time range during which the object was last modified.
     * @param int|null $lastModifyEndTimeStamp The end of the time range during which the object was last modified.
     * @param int|null $lowerSizeBound The minimum size of the specified object.
     * @param int|null $upperSizeBound The maximum size of the specified object.
     * @param string|null $storageClass The storage class of the object.
     */
    public function __construct(
        ?string $prefix = null,
        ?int $lastModifyBeginTimeStamp = null,
        ?int $lastModifyEndTimeStamp = null,
        ?int $lowerSizeBound = null,
        ?int $upperSizeBound = null,
        ?string $storageClass = null
    )
    {
        $this->prefix = $prefix;
        $this->lastModifyBeginTimeStamp = $lastModifyBeginTimeStamp;
        $this->lastModifyEndTimeStamp = $lastModifyEndTimeStamp;
        $this->lowerSizeBound = $lowerSizeBound;
        $this->upperSizeBound = $upperSizeBound;
        $this->storageClass = $storageClass;
    }
}