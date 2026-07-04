<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LocationTransferTypeConstraint
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LocationTransferTypeConstraint')]
final class LocationTransferTypeConstraint extends Model
{
    /**
     * The container that stores regions in which the destination bucket can be located with the TransferType information.
     * @var array<LocationTransferType>|null
     */
    #[XmlElement(rename: 'LocationTransferType', type: LocationTransferType::class)]
    public ?array $locationTransferTypes;

    /**
     * LocationTransferTypeConstraint constructor.
     * @param array<LocationTransferType>|null $locationTransferTypes The container that stores regions in which the destination bucket can be located with the TransferType information.
     */
    public function __construct(
        ?array $locationTransferTypes = null
    )
    {
        $this->locationTransferTypes = $locationTransferTypes;
    }
}