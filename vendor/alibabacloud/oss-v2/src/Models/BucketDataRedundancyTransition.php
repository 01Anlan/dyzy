<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class BucketDataRedundancyTransition
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'BucketDataRedundancyTransition')]
final class BucketDataRedundancyTransition extends Model
{

    /**
     * The progress of the redundancy type change task in percentage. Valid values: 0 to 100. This element is available when the task is in the Processing or Finished state.
     * @var int|null
     */
    #[XmlElement(rename: 'ProcessPercentage', type: 'int')]
    public ?int $processPercentage;

    /**
     * The estimated period of time that is required for the redundancy type change task. Unit: hours. This element is available when the task is in the Processing or Finished state.
     * @var int|null
     */
    #[XmlElement(rename: 'EstimatedRemainingTime', type: 'int')]
    public ?int $estimatedRemainingTime;

    /**
     * The name of the bucket.
     * @var string|null
     */
    #[XmlElement(rename: 'Bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The ID of the redundancy type conversion task. The ID can be used to view and delete the redundancy type conversion task.
     * @var string|null
     */
    #[XmlElement(rename: 'TaskId', type: 'string')]
    public ?string $taskId;

    /**
     * The state of the redundancy type change task. Valid values:QueueingProcessingFinished
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * The time when the redundancy type change task was created.
     * @var string|null
     */
    #[XmlElement(rename: 'CreateTime', type: 'string')]
    public ?string $createTime;

    /**
     * The time when the redundancy type change task was performed. This element is available when the task is in the Processing or Finished state.
     * @var string|null
     */
    #[XmlElement(rename: 'StartTime', type: 'string')]
    public ?string $startTime;

    /**
     * The time when the redundancy type change task was finished. This element is available when the task is in the Finished state.
     * @var string|null
     */
    #[XmlElement(rename: 'EndTime', type: 'string')]
    public ?string $endTime;

    /**
     * BucketDataRedundancyTransition constructor.
     * @param int|null $processPercentage The progress of the redundancy type change task in percentage.
     * @param int|null $estimatedRemainingTime The estimated period of time that is required for the redundancy type change task.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $taskId The ID of the redundancy type conversion task.
     * @param string|null $status The state of the redundancy type change task.
     * @param string|null $createTime The time when the redundancy type change task was created.
     * @param string|null $startTime The time when the redundancy type change task was performed.
     * @param string|null $endTime The time when the redundancy type change task was finished.
     */
    public function __construct(
        ?int $processPercentage = null,
        ?int $estimatedRemainingTime = null,
        ?string $bucket = null,
        ?string $taskId = null,
        ?string $status = null,
        ?string $createTime = null,
        ?string $startTime = null,
        ?string $endTime = null
    )
    {
        $this->processPercentage = $processPercentage;
        $this->estimatedRemainingTime = $estimatedRemainingTime;
        $this->bucket = $bucket;
        $this->status = $status;
        $this->taskId = $taskId;
        $this->createTime = $createTime;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}