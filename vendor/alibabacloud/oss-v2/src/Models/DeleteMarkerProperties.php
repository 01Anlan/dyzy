<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class DeleteMarkerProperties
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'DeleteMarker')]
final class DeleteMarkerProperties extends Model
{
    /**
     * The name of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The version ID of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'VersionId', type: 'string')]
    public ?string $versionId;

    /**
     * Indicates whether the version is the current version. Valid values:*   true: The version is the current version.*   false: The version is a previous version.
     * @var bool|null
     */
    #[XmlElement(rename: 'IsLatest', type: 'bool')]
    public ?bool $isLatest;

    /**
     * The time when the object was last modified.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'LastModified', type: 'DateTime')]
    public ?\DateTime $lastModified;

    /**
     * The container that stores the information about the bucket owner.
     * @var Owner|null
     */
    #[XmlElement(rename: 'Owner', type: Owner::class)]
    public ?Owner $owner;

    /**
     * DeleteMarkerProperties constructor.
     * @param string|null $key The name of the object.
     * @param string|null $versionId The version ID of the object.
     * @param bool|null $isLatest Indicates whether the version is the current version.
     * @param \DateTime|null $lastModified The time when the object was last modified.
     * @param Owner|null $owner The container that stores the information about the bucket owner.
     */
    public function __construct(
        ?string $key = null,
        ?string $versionId = null,
        ?bool $isLatest = null,
        ?\DateTime $lastModified = null,
        ?Owner $owner = null
    )
    {
        $this->key = $key;
        $this->versionId = $versionId;
        $this->isLatest = $isLatest;
        $this->lastModified = $lastModified;
        $this->owner = $owner;
    }
}
