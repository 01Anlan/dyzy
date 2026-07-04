<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class WormConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'WormConfiguration')]
final class WormConfiguration extends Model
{
    /**
     * The status of the retention policy. Valid values:- InProgress: indicates that the retention policy is in the InProgress state. By default, a retention policy is in the InProgress state after it is created. The policy remains in this state for 24 hours.- Locked: indicates that the retention policy is in the Locked state.
     * Sees BucketWormStateType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'State', type: 'string')]
    public ?string $state;

    /**
     * The number of days for which objects can be retained.
     * @var int|null
     */
    #[XmlElement(rename: 'RetentionPeriodInDays', type: 'int')]
    public ?int $retentionPeriodInDays;

    /**
     * The time at which the retention policy was created.
     * @var string|null
     */
    #[XmlElement(rename: 'CreationDate', type: 'string')]
    public ?string $creationDate;

    /**
     * The time at which the retention policy will be expired.
     * @var string|null
     */
    #[XmlElement(rename: 'ExpirationDate', type: 'string')]
    public ?string $expirationDate;

    /**
     * The ID of the retention policy.Note If the specified retention policy ID that is used to query the retention policy configurations of the bucket does not exist, OSS returns the 404 error code.
     * @var string|null
     */
    #[XmlElement(rename: 'WormId', type: 'string')]
    public ?string $wormId;


    /**
     * WormConfiguration constructor.
     * @param string|null $wormId The ID of the retention policy.
     * @param string|null $state The status of the retention policy.
     * @param int|null $retentionPeriodInDays The number of days for which objects can be retained.
     * @param string|null $creationDate The time at which the retention policy was created.
     * @param string|null $expirationDate The time at which the retention policy will be expired.
     */
    public function __construct(
        ?string $state = null,
        ?int $retentionPeriodInDays = null,
        ?string $creationDate = null,
        ?string $expirationDate = null,
        ?string $wormId = null
    )
    {
        $this->state = $state;
        $this->retentionPeriodInDays = $retentionPeriodInDays;
        $this->creationDate = $creationDate;
        $this->expirationDate = $expirationDate;
        $this->wormId = $wormId;
    }
}