<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationLocation
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationLocation')]
final class ReplicationLocation extends Model
{
    /**
     * The container that stores regions in which the destination bucket can be located with TransferType specified.
     * @var LocationTransferTypeConstraint|null
     */
    #[XmlElement(rename: 'LocationTransferTypeConstraint', type: LocationTransferTypeConstraint::class)]
    public ?LocationTransferTypeConstraint $locationTransferTypeConstraint;

    /**
     * The container that stores regions in which the RTC can be enabled.
     * @var LocationRTCConstraint|null
     */
    #[XmlElement(rename: 'LocationRTCConstraint', type: LocationRTCConstraint::class)]
    public ?LocationRTCConstraint $locationRTCConstraint;

    /**
     * The regions in which the destination bucket can be located.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Location', type: 'string')]
    public ?array $locations;

    /**
     * ReplicationLocation constructor.
     * @param LocationTransferTypeConstraint|null $locationTransferTypeConstraint The container that stores regions in which the destination bucket can be located with TransferType specified.
     * @param LocationRTCConstraint|null $locationRTCConstraint The container that stores regions in which the RTC can be enabled.
     * @param array<string>|null $locations The regions in which the destination bucket can be located.
     */
    public function __construct(
        ?LocationTransferTypeConstraint $locationTransferTypeConstraint = null,
        ?LocationRTCConstraint $locationRTCConstraint = null,
        ?array $locations = null
    )
    {
        $this->locationTransferTypeConstraint = $locationTransferTypeConstraint;
        $this->locationRTCConstraint = $locationRTCConstraint;
        $this->locations = $locations;
    }
}