<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class TransferAccelerationConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'TransferAccelerationConfiguration')]
final class TransferAccelerationConfiguration extends Model
{
    /**
     * Whether the transfer acceleration is enabled for this bucket.
     * @var bool|null
     */
    #[XmlElement(rename: 'Enabled', type: 'bool')]
    public ?bool $enabled;

    /**
     * TransferAccelerationConfiguration constructor.
     * @param bool|null $enabled Whether the transfer acceleration is enabled for this bucket.
     */
    public function __construct(
        ?bool $enabled = null
    )
    {
        $this->enabled = $enabled;
    }
}