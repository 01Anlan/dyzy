<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleRuleExpiration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Expiration')]
final class LifecycleRuleExpiration extends Model
{
    /**
     * The number of days from when the objects were last modified to when the lifecycle rule takes effect.
     * @var int|null
     */
    #[XmlElement(rename: 'Days', type: 'int')]
    public ?int $days;

    /**
     * Specifies whether to automatically remove expired delete markers.*   true: Expired delete markers are automatically removed. If you set this parameter to true, you cannot specify the Days or CreatedBeforeDate parameter.*   false: Expired delete markers are not automatically removed. If you set this parameter to false, you must specify the Days or CreatedBeforeDate parameter.
     * @var bool|null
     */
    #[XmlElement(rename: 'ExpiredObjectDeleteMarker', type: 'bool')]
    public ?bool $expiredObjectDeleteMarker;

    /**
     * The date based on which the lifecycle rule takes effect. OSS performs the specified operation on data whose last modified date is earlier than this date. The value of this parameter is in the yyyy-MM-ddT00:00:00.000Z format.Specify the time in the ISO 8601 standard. The time must be at 00:00:00 in UTC.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'CreatedBeforeDate', type: 'DateTime')]
    public ?\DateTime $createdBeforeDate;


    /**
     * LifecycleRuleExpiration constructor.
     * @param int|null $days The number of days from when the objects were last modified to when the lifecycle rule takes effect.
     * @param bool|null $expiredObjectDeleteMarker Specifies whether to automatically remove expired delete markers.
     * @param \DateTime|null $createdBeforeDate The date based on which the lifecycle rule takes effect.
     */
    public function __construct(
        ?int $days = null,
        ?bool $expiredObjectDeleteMarker = null,
        ?\DateTime $createdBeforeDate = null
    )
    {
        $this->days = $days;
        $this->expiredObjectDeleteMarker = $expiredObjectDeleteMarker;
        $this->createdBeforeDate = $createdBeforeDate;
    }
}