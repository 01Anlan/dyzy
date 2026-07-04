<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ObjectProperties
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ObjectProperties')]
final class ObjectProperties extends Model
{
    /**
     * The name of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

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
     * The entity tag (ETag). An ETag is created when the object is created to identify the content of an object.*   For an object that is created by calling the PutObject operation, the ETag value of the object is the MD5 hash of the object content.*   For an object that is created by using another method, the ETag value is not the MD5 hash of the object content but a unique value calculated based on a specific rule.*   The ETag of an object can be used to check whether the object content changes. However, we recommend that you use the MD5 hash of an object rather than the ETag value of the object to verify data integrity.
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
     * ObjectProperties constructor.
     * @param string|null $key The name of the object.
     * @param string|null $type The type of the object.
     * @param int|null $size The size of the object. Unit: bytes.
     * @param string|null $etag The entity tag (ETag).
     * @param \DateTime|null $lastModified The time when the object was last modified.
     * @param string|null $storageClass The storage class of the object.
     * @param Owner|null $owner The container that stores the information about the bucket owner.
     * @param string|null $restoreInfo The restoration status of the object.
     * @param \DateTime|null $transitionTime The time when an object is dumped into a cold archive or deep cold archive storage type through lifecycle rules.
     */
    public function __construct(
        ?string $key = null,
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
