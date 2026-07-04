<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\TagBody;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;
use AlibabaCloud\Oss\V2\Types\Model;

/**
 * Class CreateAccessPoint
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CreateAccessPointResult')]
final class CreateAccessPoint extends Model
{
    /**
     * The alias of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'Alias', type: 'string')]
    public ?string $alias;

    /**
     * The Alibaba Cloud Resource Name (ARN) of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessPointArn', type: 'string')]
    public ?string $accessPointArn;

    /**
     * CreateAccessPoint constructor.
     * @param string|null $alias The alias of the access point.
     * @param string|null $accessPointArn The Alibaba Cloud Resource Name (ARN) of the access point.
     */
    public function __construct(
        ?string $alias = null,
        ?string $accessPointArn = null
    )
    {
        $this->alias = $alias;
        $this->accessPointArn = $accessPointArn;
    }
}