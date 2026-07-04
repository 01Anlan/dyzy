<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleRuleAbortMultipartUpload
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AbortMultipartUpload')]
final class LifecycleRuleAbortMultipartUpload extends Model
{
    /**
     * The number of days from when the objects were last modified to when the lifecycle rule takes effect.
     * @var int|null
     */
    #[XmlElement(rename: 'Days', type: 'int')]
    public ?int $days;

    /**
     * The date based on which the lifecycle rule takes effect. OSS performs the specified operation on data whose last modified date is earlier than this date. Specify the time in the ISO 8601 standard. The time must be at 00:00:00 in UTC.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'CreatedBeforeDate', type: 'DateTime')]
    public ?\DateTime $createdBeforeDate;


    /**
     * LifecycleRuleAbortMultipartUpload constructor.
     * @param int|null $days The number of days from when the objects were last modified to when the lifecycle rule takes effect.
     * @param \DateTime|null $createdBeforeDate The date based on which the lifecycle rule takes effect.
     */
    public function __construct(
        ?int $days = null,
        ?\DateTime $createdBeforeDate = null
    )
    {
        $this->days = $days;
        $this->createdBeforeDate = $createdBeforeDate;
    }
}