<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InventoryDestination
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InventoryDestination')]
final class InventoryDestination extends Model
{
    /**
     * The container that stores information about the bucket in which exported inventory lists are stored.
     * @var InventoryOSSBucketDestination|null
     */
    #[XmlElement(rename: 'OSSBucketDestination', type: InventoryOSSBucketDestination::class)]
    public ?InventoryOSSBucketDestination $ossBucketDestination;

    /**
     * InventoryDestination constructor.
     * @param InventoryOSSBucketDestination|null $ossBucketDestination The container that stores information about the bucket in which exported inventory lists are stored.
     */
    public function __construct(
        ?InventoryOSSBucketDestination $ossBucketDestination = null
    )
    {
        $this->ossBucketDestination = $ossBucketDestination;
    }
}