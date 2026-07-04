<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InventoryOSSBucketDestination
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InventoryOSSBucketDestination')]
final class InventoryOSSBucketDestination extends Model
{
    /**
     * The name of the bucket in which exported inventory lists are stored.
     * @var string|null
     */
    #[XmlElement(rename: 'Bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The prefix of the path in which the exported inventory lists are stored.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * The container that stores the encryption method of the exported inventory lists.
     * @var InventoryEncryption|null
     */
    #[XmlElement(rename: 'Encryption', type: InventoryEncryption::class)]
    public ?InventoryEncryption $encryption;

    /**
     * The format of exported inventory lists. The exported inventory lists are CSV objects compressed by using GZIP.
     * Sees InventoryFormatType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Format', type: 'string')]
    public ?string $format;

    /**
     * The ID of the account to which permissions are granted by the bucket owner.
     * @var string|null
     */
    #[XmlElement(rename: 'AccountId', type: 'string')]
    public ?string $accountId;

    /**
     * The Alibaba Cloud Resource Name (ARN) of the role that has the permissions to read all objects from the source bucket and write objects to the destination bucket. Format: `acs:ram::uid:role/rolename`.
     * @var string|null
     */
    #[XmlElement(rename: 'RoleArn', type: 'string')]
    public ?string $roleArn;

    /**
     * InventoryOSSBucketDestination constructor.
     * @param string|null $bucket The name of the bucket in which exported inventory lists are stored.
     * @param string|null $prefix The prefix of the path in which the exported inventory lists are stored.
     * @param InventoryEncryption|null $encryption The container that stores the encryption method of the exported inventory lists.
     * @param string|null $format The format of exported inventory lists.
     * @param string|null $accountId The ID of the account to which permissions are granted by the bucket owner.
     * @param string|null $roleArn The Alibaba Cloud Resource Name (ARN) of the role that has the permissions to read all objects from the source bucket and write objects to the destination bucket.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $prefix = null,
        ?InventoryEncryption $encryption = null,
        ?string $format = null,
        ?string $accountId = null,
        ?string $roleArn = null
    )
    {
        $this->bucket = $bucket;
        $this->prefix = $prefix;
        $this->encryption = $encryption;
        $this->format = $format;
        $this->accountId = $accountId;
        $this->roleArn = $roleArn;
    }
}