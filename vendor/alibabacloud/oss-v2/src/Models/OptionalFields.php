<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class OptionalFields
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'OptionalFields')]
final class OptionalFields extends Model
{
    /**
     * The configuration fields that are included in inventory lists. Available configuration fields:*   Size: the size of the object.*   LastModifiedDate: the time when the object was last modified.*   ETag: the ETag of the object. It is used to identify the content of the object.*   StorageClass: the storage class of the object.*   IsMultipartUploaded: specifies whether the object is uploaded by using multipart upload.*   EncryptionStatus: the encryption status of the object.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Field', type: 'string')]
    public ?array $fields;

    /**
     * OptionalFields constructor.
     * @param array<string>|null $fields The configuration fields that are included in inventory lists.
     */
    public function __construct(
        ?array $fields = null
    )
    {
        $this->fields = $fields;
    }
}