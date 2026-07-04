<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LocationTransferType
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LocationTransferType')]
final class LocationTransferType extends Model
{
    /**
     * The regions in which the destination bucket can be located.
     * @var string|null
     */
    #[XmlElement(rename: 'Location', type: 'string')]
    public ?string $location;
    /**
     * The container that stores the transfer type.
     * @var TransferTypes|null
     */
    #[XmlElement(rename: 'TransferTypes', type: TransferTypes::class)]
    public ?TransferTypes $transferTypes;

    /**
     * LocationTransferType constructor.
     * @param string|null $location The regions in which the destination bucket can be located.
     * @param TransferTypes|null $transferTypes The container that stores the transfer type.
     */
    public function __construct(
        ?string $location = null,
        ?TransferTypes $transferTypes = null
    )
    {
        $this->location = $location;
        $this->transferTypes = $transferTypes;
    }
}