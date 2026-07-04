<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class NoncurrentVersionTransition
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'NoncurrentVersionTransition')]
final class NoncurrentVersionTransition extends Model
{   
    /**
     * The number of days from when the objects became previous versions to when the lifecycle rule takes effect.
     * @var int|null
     */
    #[XmlElement(rename: 'NoncurrentDays', type: 'int')]
    public ?int $noncurrentDays;
    
    /**
     * The storage class to which objects are converted. Valid values:*   IA*   Archive*   ColdArchive  You can convert the storage class of objects in an IA bucket to only Archive or Cold Archive.
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'StorageClass', type: 'string')]
    public ?string $storageClass;
    
    /**
     * Specifies whether the lifecycle rule applies to objects based on their last access time. Valid values:*   true: The rule applies to objects based on their last access time.*   false: The rule applies to objects based on their last modified time.
     * @var bool|null
     */
    #[XmlElement(rename: 'IsAccessTime', type: 'bool')]
    public ?bool $isAccessTime;
    
    /**
     * Specifies whether to convert the storage class of non-Standard objects back to Standard after the objects are accessed. This parameter takes effect only when the IsAccessTime parameter is set to true. Valid values:*   true: converts the storage class of the objects to Standard.*   false: does not convert the storage class of the objects to Standard.
     * @var bool|null
     */
    #[XmlElement(rename: 'ReturnToStdWhenVisit', type: 'bool')]
    public ?bool $returnToStdWhenVisit;
    
    /**
     * Specifies whether to convert the storage class of objects whose sizes are less than 64 KB to IA, Archive, or Cold Archive based on their last access time. Valid values:*   true: converts the storage class of objects that are smaller than 64 KB to IA, Archive, or Cold Archive. Objects that are smaller than 64 KB are charged as 64 KB. Objects that are greater than or equal to 64 KB are charged based on their actual sizes. If you set this parameter to true, the storage fees may increase.*   false: does not convert the storage class of an object that is smaller than 64 KB.
     * @var bool|null
     */
    #[XmlElement(rename: 'AllowSmallFile', type: 'bool')]
    public ?bool $allowSmallFile;
    

    /**
     * NoncurrentVersionTransition constructor.
     * @param int|null $noncurrentDays The number of days from when the objects became previous versions to when the lifecycle rule takes effect.
     * @param string|null $storageClass The storage class to which objects are converted.
     * @param bool|null $isAccessTime Specifies whether the lifecycle rule applies to objects based on their last access time.
     * @param bool|null $returnToStdWhenVisit Specifies whether to convert the storage class of non-Standard objects back to Standard after the objects are accessed.
     * @param bool|null $allowSmallFile Specifies whether to convert the storage class of objects whose sizes are less than 64 KB to IA, Archive, or Cold Archive based on their last access time.
     */
    public function __construct(
        ?int $noncurrentDays = null,
        ?string $storageClass = null,
        ?bool $isAccessTime = null,
        ?bool $returnToStdWhenVisit = null,
        ?bool $allowSmallFile = null
    )
    {   
        $this->noncurrentDays = $noncurrentDays;
        $this->storageClass = $storageClass;
        $this->isAccessTime = $isAccessTime;
        $this->returnToStdWhenVisit = $returnToStdWhenVisit;
        $this->allowSmallFile = $allowSmallFile;
    }
}