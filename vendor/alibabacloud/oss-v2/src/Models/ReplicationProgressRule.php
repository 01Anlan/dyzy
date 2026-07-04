<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationProgressRule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationProgressRule')]
final class ReplicationProgressRule extends Model
{
    /**
     * The ID of the data replication rule.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * The container that stores prefixes. You can specify up to 10 prefixes in each data replication rule.
     * @var ReplicationPrefixSet|null
     */
    #[XmlElement(rename: 'PrefixSet', type: ReplicationPrefixSet::class)]
    public ?ReplicationPrefixSet $prefixSet;

    /**
     * The operations that are synchronized to the destination bucket.*   ALL: PUT, DELETE, and ABORT operations are synchronized to the destination bucket.*   PUT: Write operations are synchronized to the destination bucket, including PutObject, PostObject, AppendObject, CopyObject, PutObjectACL, InitiateMultipartUpload, UploadPart, UploadPartCopy, and CompleteMultipartUpload.
     * @var string|null
     */
    #[XmlElement(rename: 'Action', type: 'string')]
    public ?string $action;

    /**
     * The container that stores the information about the destination bucket.
     * @var ReplicationDestination|null
     */
    #[XmlElement(rename: 'Destination', type: ReplicationDestination::class)]
    public ?ReplicationDestination $destination;

    /**
     * The status of the data replication task. Valid values:*   starting: OSS creates a data replication task after a data replication rule is configured.*   doing: The replication rule is effective and the replication task is in progress.*   closing: OSS clears a data replication task after the corresponding data replication rule is deleted.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * Specifies whether to replicate historical data that exists before data replication is enabled from the source bucket to the destination bucket.*   enabled (default): replicates historical data to the destination bucket.*   disabled: ignores historical data and replicates only data uploaded to the source bucket after data replication is enabled for the source bucket.
     * @var string|null
     */
    #[XmlElement(rename: 'HistoricalObjectReplication', type: 'string')]
    public ?string $historicalObjectReplication;

    /**
     * The container that stores the progress of the data replication task. This parameter is returned only when the data replication task is in the doing state.
     * @var ReplicationProgressInformation|null
     */
    #[XmlElement(rename: 'Progress', type: ReplicationProgressInformation::class)]
    public ?ReplicationProgressInformation $progress;

    /**
     * ReplicationProgressRule constructor.
     * @param string|null $id The ID of the data replication rule.
     * @param ReplicationPrefixSet|null $prefixSet The container that stores prefixes.
     * @param string|null $action The operations that are synchronized to the destination bucket.
     * @param ReplicationDestination|null $destination The container that stores the information about the destination bucket.
     * @param string|null $status The status of the data replication task.
     * @param string|null $historicalObjectReplication Specifies whether to replicate historical data that exists before data replication is enabled from the source bucket to the destination bucket.
     * @param ReplicationProgressInformation|null $progress The container that stores the progress of the data replication task.
     */
    public function __construct(
        ?string $id = null,
        ?ReplicationPrefixSet $prefixSet = null,
        ?string $action = null,
        ?ReplicationDestination $destination = null,
        ?string $status = null,
        ?string $historicalObjectReplication = null,
        ?ReplicationProgressInformation $progress = null
    )
    {
        $this->id = $id;
        $this->prefixSet = $prefixSet;
        $this->action = $action;
        $this->destination = $destination;
        $this->status = $status;
        $this->historicalObjectReplication = $historicalObjectReplication;
        $this->progress = $progress;
    }
}