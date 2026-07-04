<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAddresses
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Addresses')]
final class MetaQueryAddresses extends Model
{
    /**
     * @var array<MetaQueryAddress>|null
     */
    #[XmlElement(rename: 'Address', type: MetaQueryAddress::class)]
    public ?array $address;

    /**
     * MetaQueryAddresses constructor.
     * @param array|null $address
     */
    public function __construct(
        ?array $address = null
    )
    {
        $this->address = $address;
    }
}