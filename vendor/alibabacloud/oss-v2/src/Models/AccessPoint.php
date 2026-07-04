<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class AccessPoint
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AccessPoint')]
final class AccessPoint extends Model
{
    /**
     * The network origin of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'NetworkOrigin', type: 'string')]
    public ?string $networkOrigin;

    /**
     * The container that stores the information about the VPC.
     * @var AccessPointVpcConfiguration|null
     */
    #[XmlElement(rename: 'VpcConfiguration', type: AccessPointVpcConfiguration::class)]
    public ?AccessPointVpcConfiguration $vpcConfiguration;

    /**
     * The status of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * The name of the bucket for which the access point is configured.
     * @var string|null
     */
    #[XmlElement(rename: 'Bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessPointName', type: 'string')]
    public ?string $accessPointName;

    /**
     * The alias of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'Alias', type: 'string')]
    public ?string $alias;

    /**
     * AccessPoint constructor.
     * @param string|null $networkOrigin The network origin of the access point.
     * @param AccessPointVpcConfiguration|null $vpcConfiguration The container that stores the information about the VPC.
     * @param string|null $status The status of the access point.
     * @param string|null $bucket The name of the bucket for which the access point is configured.
     * @param string|null $accessPointName The name of the access point.
     * @param string|null $alias The alias of the access point.
     */
    public function __construct(
        ?string $networkOrigin = null,
        ?AccessPointVpcConfiguration $vpcConfiguration = null,
        ?string $status = null,
        ?string $bucket = null,
        ?string $accessPointName = null,
        ?string $alias = null
    )
    {
        $this->networkOrigin = $networkOrigin;
        $this->vpcConfiguration = $vpcConfiguration;
        $this->status = $status;
        $this->bucket = $bucket;
        $this->accessPointName = $accessPointName;
        $this->alias = $alias;
    }
}