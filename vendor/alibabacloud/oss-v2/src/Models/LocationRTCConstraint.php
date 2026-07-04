<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LocationRTCConstraint
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LocationRTCConstraint')]
final class LocationRTCConstraint extends Model
{
    /**
     * The regions where RTC is supported.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Location', type: 'string')]
    public ?array $locations;

    /**
     * LocationRTCConstraint constructor.
     * @param array<string>|null $locations The regions where RTC is supported.
     */
    public function __construct(
        ?array $locations = null
    )
    {
        $this->locations = $locations;
    }
}