<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ObjectVersionProperties
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Version')]
final class ObjectVersionProperties extends Model
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
     * The type of the object. An object has one of the following types:*   Normal: The object is created by using simple upload.*   Multipart: The object is created by using multipart upload.*   Appendable: The object is created by using append upload. An appendable object can be appended.
     * @var string|null
     */
    #[XmlElement(rename: 'Type', type: 'string')]
    public ?string $type;

    /**
     * The size of the object. Unit: bytes.
     * @var int|null
     */
    #[XmlElement(rename: 'Size', type: 'int')]
    public ?int $size;

    /**
     * The ETag that is generated when an object is created. ETags are used to identify the content of objects.*   If an object is created by calling the PutObject operation, the ETag of the object is the MD5 hash of the object content.*   If an object is created by using another method, the ETag is not the MD5 hash of the object content but a unique value that is calculated based on a specific rule.  The ETag of an object can be used only to check whether the object content is modified. However, we recommend that you use the MD5 hash of an object rather than the ETag of the object to verify data integrity.
     * @var string|null
     */
    #[XmlElement(rename: 'ETag', type: 'string')]
    public ?string $etag;

    /**
     * The time when the object was last modified.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'LastModified', type: 'DateTime')]
    public ?\DateTime $lastModified;

    /**
     * The storage class of the object.
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'StorageClass', type: 'string')]
    public ?string $storageClass;

    /**
     * The container that stores the information about the bucket owner.
     * @var Owner|null
     */
    #[XmlElement(rename: 'Owner', type: Owner::class)]
    public ?Owner $owner;

    /**
     * The restoration status of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'RestoreInfo', type: 'string')]
    public ?string $restoreInfo;

    /**
     * The time when an object is dumped into a cold archive or deep cold archive storage type through lifecycle rules.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'TransitionTime', type: 'DateTime')]
    public ?\DateTime $transitionTime;

    /**
     * ObjectVersionProperties constructor.
     * @param string|null $key The name of the object.
     * @param string|null $versionId The version ID of the object.
     * @param bool|null $isLatest Indicates whether the version is the current version.
     * @param string|null $type The type of the object.
     * @param int|null $size The size of the object.
     * @param string|null $etag The ETag that is generated when an object is created.
     * @param \DateTime|null $lastModified The time when the object was last modified.
     * @param string|null $storageClass The storage class of the object.
     * @param Owner|null $owner The container that stores the information about the bucket owner.
     * @param string|null $restoreInfo The restoration status of the object.
     * @param \DateTime|null $transitionTime The time when an object is dumped into a cold archive or deep cold archive storage type through lifecycle rules.
     */
    public function __construct(
        ?string $key = null,
        ?string $versionId = null,
        ?bool $isLatest = null,
        ?string $type = null,
        ?int $size = null,
        ?string $etag = null,
        ?\DateTime $lastModified = null,
        ?string $storageClass = null,
        ?Owner $owner = null,
        ?string $restoreInfo = null,
        ?\DateTime $transitionTime = null
    )
    {
        $this->key = $key;
        $this->versionId = $versionId;
        $this->isLatest = $isLatest;
        $this->type = $type;
        $this->size = $size;
        $this->etag = $etag;
        $this->lastModified = $lastModified;
        $this->storageClass = $storageClass;
        $this->owner = $owner;
        $this->restoreInfo = $restoreInfo;
        $this->transitionTime = $transitionTime;
    }
}
