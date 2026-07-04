<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;

/**
 * Class BucketInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
final class BucketInfo extends Model
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $name;

    /**
     * Indicates whether access tracking is enabled for the bucket.Valid values:*   Enabled            *   Disabled
     * @var string|null
     */
    public ?string $accessMonitor;

    /**
     * The region in which the bucket is located.
     * @var string|null
     */
    public ?string $location;

    /**
     * The time when the bucket is created. The time is in UTC.
     * @var \DateTime|null
     */
    public ?\DateTime $creationDate;

    /**
     * The public endpoint of the bucket.
     * @var string|null
     */
    public ?string $extranetEndpoint;

    /**
     * The internal endpoint of the bucket.
     * @var string|null
     */
    public ?string $intranetEndpoint;

    /**
     * The ACL of the bucket.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The redundancy type of the bucket.
     * Sees DataRedundancyType for supported values.
     * @var string|null
     */
    public ?string $dataRedundancyType;

    /**
     * The owner of the bucket.
     * @var Owner|null
     */
    public ?Owner $owner;

    /**
     * The storage class of the bucket.
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The ID of the resource group to which the bucket belongs.
     * @var string|null
     */
    public ?string $resourceGroupId;

    /**
     * The server-side encryption configurations of the bucket.
     * @var ServerSideEncryptionRuleInfo |null
     */
    public ?ServerSideEncryptionRuleInfo $sseRule;

    /**
     * The versioning status of the bucket.
     * Sees BucketVersioningStatusType for supported values.
     * @var string|null
     */
    public ?string $versioning;

    /**
     * Indicates whether transfer acceleration is enabled for the bucket.Valid values:*   Enabled            *   Disabled
     * @var string|null
     */
    public ?string $transferAcceleration;

    /**
     * Indicates whether cross-region replication (CRR) is enabled for the bucket.Valid values:*   Enabled            *   Disabled
     * @var string|null
     */
    public ?string $crossRegionReplication;

    /**
     * The description of the bucket.
     * @var string|null
     */
    public ?string $comment;

    /**
     * Indicates whether Block Public Access is enabled for the bucket.
     * true: Block Public Access is enabled. false: Block Public Access is disabled.
     * @var bool|null
     */
    public ?bool $blockPublicAccess;

    /**
     * The log configurations of the bucket.
     * @var BucketPolicy|null
     */
    public ?BucketPolicy $bucketPolicy;


    /**
     * BucketInfo constructor.
     * @param string|null $name The name of the bucket.
     * @param string|null $accessMonitor Indicates whether access tracking is enabled for the bucket.
     * @param string|null $location The region in which the bucket is located.
     * @param \DateTime|null $creationDate The time when the bucket is created.
     * @param string|null $extranetEndpoint The public endpoint of the bucket.
     * @param string|null $intranetEndpoint The internal endpoint of the bucket.
     * @param string|null $acl The ACL of the bucket.
     * @param string|null $dataRedundancyType The redundancy type of the bucket.
     * @param Owner|null $owner The owner of the bucket.
     * @param string|null $storageClass The storage class of the bucket.
     * @param string|null $resourceGroupId The ID of the resource group to which the bucket belongs.
     * @param ServerSideEncryptionRuleInfo |null $sseRule The server-side encryption configurations of the bucket.
     * @param string|null $versioning The versioning status of the bucket.
     * @param string|null $transferAcceleration Indicates whether transfer acceleration is enabled for the bucket.
     * @param string|null $crossRegionReplication Indicates whether cross-region replication (CRR) is enabled for the bucket.
     * @param string|null $comment The description of the bucket.
     * @param bool|null $blockPublicAccess Indicates whether Block Public Access is enabled for the bucket.
     * @param BucketPolicy|null $bucketPolicy The log configurations of the bucket.
     */
    public function __construct(
        ?string $name = null,
        ?string $accessMonitor = null,
        ?string $location = null,
        ?\DateTime $creationDate = null,
        ?string $extranetEndpoint = null,
        ?string $intranetEndpoint = null,
        ?string $acl = null,
        ?string $dataRedundancyType = null,
        ?Owner $owner = null,
        ?string $storageClass = null,
        ?string $resourceGroupId = null,
        ?ServerSideEncryptionRuleInfo $sseRule = null,
        ?string $versioning = null,
        ?string $transferAcceleration = null,
        ?string $crossRegionReplication = null,
        ?string $comment = null,
        ?bool $blockPublicAccess = null,
        ?BucketPolicy $bucketPolicy = null
    )
    {
        $this->name = $name;
        $this->accessMonitor = $accessMonitor;
        $this->location = $location;
        $this->creationDate = $creationDate;
        $this->extranetEndpoint = $extranetEndpoint;
        $this->intranetEndpoint = $intranetEndpoint;
        $this->acl = $acl;
        $this->dataRedundancyType = $dataRedundancyType;
        $this->owner = $owner;
        $this->storageClass = $storageClass;
        $this->resourceGroupId = $resourceGroupId;
        $this->sseRule = $sseRule;
        $this->versioning = $versioning;
        $this->transferAcceleration = $transferAcceleration;
        $this->crossRegionReplication = $crossRegionReplication;
        $this->comment = $comment;
        $this->blockPublicAccess = $blockPublicAccess;
        $this->bucketPolicy = $bucketPolicy;
    }
}
