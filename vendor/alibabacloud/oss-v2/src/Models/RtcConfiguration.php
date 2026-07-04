<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RtcConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RtcConfiguration')]
final class RtcConfiguration extends Model
{
    /**
     * The container that stores the status of RTC.
     * @var ReplicationTimeControl|null
     */
    #[XmlElement(rename: 'RTC', type: ReplicationTimeControl::class)]
    public ?ReplicationTimeControl $rtc;

    /**
     * The ID of the data replication rule for which you want to configure RTC.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * RtcConfiguration constructor.
     * @param ReplicationTimeControl|null $rtc The container that stores the status of RTC.
     * @param string|null $id The ID of the data replication rule for which you want to configure RTC.
     */
    public function __construct(
        ?ReplicationTimeControl $rtc = null,
        ?string $id = null
    )
    {
        $this->rtc = $rtc;
        $this->id = $id;
    }
}