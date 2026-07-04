<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Bucket
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Buckets.Bucket')]
final class Bucket extends Model
{
    /**
     * The storage class of the bucket. Valid values: Standard, IA, Archive, and ColdArchive.
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'StorageClass', type: 'string')]
    public ?string $storageClass;

    /**
     * The region in which the bucket is located.
     * @var string|null
     */
    #[XmlElement(rename: 'Region', type: 'string')]
    public ?string $region;

    /**
     * The time when the bucket was created. Format: `yyyy-mm-ddThh:mm:ss.timezone`.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'CreationDate', type: 'DateTime')]
    public ?\DateTime $creationDate;

    /**
     * The public endpoint of the region in which the bucket resides.
     * @var string|null
     */
    #[XmlElement(rename: 'ExtranetEndpoint', type: 'string')]
    public ?string $extranetEndpoint;

    /**
     * The internal endpoint of the region in which the bucket you access from ECS instances resides. The bucket and ECS instances are in the same region.
     * @var string|null
     */
    #[XmlElement(rename: 'IntranetEndpoint', type: 'string')]
    public ?string $intranetEndpoint;

    /**
     * The data center in which the bucket is located.
     * @var string|null
     */
    #[XmlElement(rename: 'Location', type: 'string')]
    public ?string $location;

    /**
     * The name of the bucket.
     * @var string|null
     */
    #[XmlElement(rename: 'Name', type: 'string')]
    public ?string $name;

    /**
     * The ID of the resource group to which the bucket belongs.
     * @var string|null
     */
    #[XmlElement(rename: 'ResourceGroupId', type: 'string')]
    public ?string $resourceGroupId;

    /**
     * Bucket constructor.
     * @param string|null $storageClass The storage class of the bucket.
     * @param string|null $region The region in which the bucket is located.
     * @param \DateTime|null $creationDate The time when the bucket was created.
     * @param string|null $extranetEndpoint The public endpoint of the region in which the bucket resides.
     * @param string|null $intranetEndpoint The internal endpoint of the region in which the bucket you access from ECS instances resides.
     * @param string|null $location The data center in which the bucket is located.
     * @param string|null $name The name of the bucket.
     * @param string|null $resourceGroupId The ID of the resource group to which the bucket belongs.
     */
    public function __construct(
        ?string $storageClass = null,
        ?string $region = null,
        ?\DateTime $creationDate = null,
        ?string $extranetEndpoint = null,
        ?string $intranetEndpoint = null,
        ?string $location = null,
        ?string $name = null,
        ?string $resourceGroupId = null
    )
    {
        $this->storageClass = $storageClass;
        $this->region = $region;
        $this->creationDate = $creationDate;
        $this->extranetEndpoint = $extranetEndpoint;
        $this->intranetEndpoint = $intranetEndpoint;
        $this->location = $location;
        $this->name = $name;
        $this->resourceGroupId = $resourceGroupId;
    }
}