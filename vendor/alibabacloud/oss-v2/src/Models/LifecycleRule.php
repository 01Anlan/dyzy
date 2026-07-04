<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleRule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LifecycleRule')]
final class LifecycleRule extends Model
{
    /**
     * The prefix in the names of the objects to which the rule applies. The prefixes specified by different rules cannot overlap.*   If Prefix is specified, this rule applies only to objects whose names contain the specified prefix in the bucket.*   If Prefix is not specified, this rule applies to all objects in the bucket.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * The conversion of the storage class of objects that match the lifecycle rule when the objects expire. The storage class of the objects can be converted to IA, Archive, and ColdArchive. The storage class of Standard objects in a Standard bucket can be converted to IA, Archive, or Cold Archive. The period of time from when the objects expire to when the storage class of the objects is converted to Archive must be longer than the period of time from when the objects expire to when the storage class of the objects is converted to IA. For example, if the validity period is set to 30 for objects whose storage class is converted to IA after the validity period, the validity period must be set to a value greater than 30 for objects whose storage class is converted to Archive.  Either Days or CreatedBeforeDate is required.
     * @var array<LifecycleRuleTransition>|null
     */
    #[XmlElement(rename: 'Transition', type: LifecycleRuleTransition::class)]
    public ?array $transitions;

    /**
     * The tag of the objects to which the lifecycle rule applies. You can specify multiple tags.
     * @var array<Tag>|null
     */
    #[XmlElement(rename: 'Tag', type: Tag::class)]
    public ?array $tags;

    /**
     * The conversion of the storage class of previous versions of the objects that match the lifecycle rule when the previous versions expire. The storage class of the previous versions can be converted to IA or Archive. The period of time from when the previous versions expire to when the storage class of the previous versions is converted to Archive must be longer than the period of time from when the previous versions expire to when the storage class of the previous versions is converted to IA.
     * @var array<NoncurrentVersionTransition>|null
     */
    #[XmlElement(rename: 'NoncurrentVersionTransition', type: NoncurrentVersionTransition::class)]
    public ?array $noncurrentVersionTransitions;

    /**
     * The container that stores the Not parameter that is used to filter objects.
     * @var LifecycleRuleFilter|null
     */
    #[XmlElement(rename: 'Filter', type: LifecycleRuleFilter::class)]
    public ?LifecycleRuleFilter $filter;

    /**
     * Timestamp for when access tracking was enabled.
     * @var int|null
     */
    #[XmlElement(rename: 'AtimeBase', type: 'int')]
    public ?int $atimeBase;

    /**
     * The ID of the lifecycle rule. The ID can contain up to 255 characters. If you do not specify the ID, OSS automatically generates a unique ID for the lifecycle rule.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * The delete operation to perform on objects based on the lifecycle rule. For an object in a versioning-enabled bucket, the delete operation specified by this parameter is performed only on the current version of the object.The period of time from when the objects expire to when the objects are deleted must be longer than the period of time from when the objects expire to when the storage class of the objects is converted to IA or Archive.
     * @var LifecycleRuleExpiration|null
     */
    #[XmlElement(rename: 'Expiration', type: LifecycleRuleExpiration::class)]
    public ?LifecycleRuleExpiration $expiration;

    /**
     * The delete operation that you want OSS to perform on the parts that are uploaded in incomplete multipart upload tasks when the parts expire.
     * @var LifecycleRuleAbortMultipartUpload|null
     */
    #[XmlElement(rename: 'AbortMultipartUpload', type: LifecycleRuleAbortMultipartUpload::class)]
    public ?LifecycleRuleAbortMultipartUpload $abortMultipartUpload;

    /**
     * The delete operation that you want OSS to perform on the previous versions of the objects that match the lifecycle rule when the previous versions expire.
     * @var NoncurrentVersionExpiration|null
     */
    #[XmlElement(rename: 'NoncurrentVersionExpiration', type: NoncurrentVersionExpiration::class)]
    public ?NoncurrentVersionExpiration $noncurrentVersionExpiration;

    /**
     * Specifies whether to enable the rule. Valid values:*   Enabled: enables the rule. OSS periodically executes the rule.*   Disabled: does not enable the rule. OSS ignores the rule.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;


    /**
     * LifecycleRule constructor.
     * @param string|null $prefix The prefix in the names of the objects to which the rule applies.
     * @param array<LifecycleRuleTransition>|null $transitions The conversion of the storage class of objects that match the lifecycle rule when the objects expire.
     * @param array<Tag>|null $tags The tag of the objects to which the lifecycle rule applies.
     * @param array<NoncurrentVersionTransition>|null $noncurrentVersionTransitions The conversion of the storage class of previous versions of the objects that match the lifecycle rule when the previous versions expire.
     * @param LifecycleRuleFilter|null $filter The container that stores the Not parameter that is used to filter objects.
     * @param int|null $atimeBase Timestamp for when access tracking was enabled.
     * @param string|null $id The ID of the lifecycle rule.
     * @param LifecycleRuleExpiration|null $expiration The delete operation to perform on objects based on the lifecycle rule.
     * @param LifecycleRuleAbortMultipartUpload|null $abortMultipartUpload The delete operation that you want OSS to perform on the parts that are uploaded in incomplete multipart upload tasks when the parts expire.
     * @param NoncurrentVersionExpiration|null $noncurrentVersionExpiration The delete operation that you want OSS to perform on the previous versions of the objects that match the lifecycle rule when the previous versions expire.
     * @param string|null $status Specifies whether to enable the rule.
     */
    public function __construct(
        ?string $prefix = null,
        ?array $transitions = null,
        ?array $tags = null,
        ?array $noncurrentVersionTransitions = null,
        ?LifecycleRuleFilter $filter = null,
        ?int $atimeBase = null,
        ?string $id = null,
        ?LifecycleRuleExpiration $expiration = null,
        ?LifecycleRuleAbortMultipartUpload $abortMultipartUpload = null,
        ?NoncurrentVersionExpiration $noncurrentVersionExpiration = null,
        ?string $status = null
    )
    {
        $this->prefix = $prefix;
        $this->transitions = $transitions;
        $this->tags = $tags;
        $this->noncurrentVersionTransitions = $noncurrentVersionTransitions;
        $this->filter = $filter;
        $this->atimeBase = $atimeBase;
        $this->id = $id;
        $this->expiration = $expiration;
        $this->abortMultipartUpload = $abortMultipartUpload;
        $this->noncurrentVersionExpiration = $noncurrentVersionExpiration;
        $this->status = $status;
    }
}