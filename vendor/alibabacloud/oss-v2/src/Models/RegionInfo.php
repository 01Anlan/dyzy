<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RegionInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RegionInfo')]
final class RegionInfo extends Model
{
    /**
     * The region ID.
     * @var string|null
     */
    #[XmlElement(rename: 'Region', type: 'string')]
    public ?string $region;

    /**
     * The public endpoint of the region.
     * @var string|null
     */
    #[XmlElement(rename: 'InternetEndpoint', type: 'string')]
    public ?string $internetEndpoint;

    /**
     * The internal endpoint of the region.
     * @var string|null
     */
    #[XmlElement(rename: 'InternalEndpoint', type: 'string')]
    public ?string $internalEndpoint;

    /**
     * The acceleration endpoint of the region. The value is always oss-accelerate.aliyuncs.com.
     * @var string|null
     */
    #[XmlElement(rename: 'AccelerateEndpoint', type: 'string')]
    public ?string $accelerateEndpoint;


    /**
     * RegionInfo constructor.
     * @param string|null $region The region ID.
     * @param string|null $internetEndpoint The public endpoint of the region.
     * @param string|null $internalEndpoint The internal endpoint of the region.
     * @param string|null $accelerateEndpoint The acceleration endpoint of the region.
     */
    public function __construct(
        ?string $region = null,
        ?string $internetEndpoint = null,
        ?string $internalEndpoint = null,
        ?string $accelerateEndpoint = null
    )
    {
        $this->region = $region;
        $this->internetEndpoint = $internetEndpoint;
        $this->internalEndpoint = $internalEndpoint;
        $this->accelerateEndpoint = $accelerateEndpoint;
    }
}