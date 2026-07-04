<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CreateAccessPointConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CreateAccessPointConfiguration')]
final class CreateAccessPointConfiguration extends Model
{
    /**
     * The container that stores the information about the VPC.
     * @var AccessPointVpcConfiguration|null
     */
    #[XmlElement(rename: 'VpcConfiguration', type: AccessPointVpcConfiguration::class)]
    public ?AccessPointVpcConfiguration $vpcConfiguration;

    /**
     * The name of the access point. The name of the access point must meet the following naming rules:*   The name must be unique in a region of your Alibaba Cloud account.*   The name cannot end with -ossalias.*   The name can contain only lowercase letters, digits, and hyphens (-). It cannot start or end with a hyphen (-).*   The name must be 3 to 19 characters in length.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessPointName', type: 'string')]
    public ?string $accessPointName;

    /**
     * The network origin of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'NetworkOrigin', type: 'string')]
    public ?string $networkOrigin;

    /**
     * CreateAccessPointConfiguration constructor.
     * @param AccessPointVpcConfiguration|null $vpcConfiguration The container that stores the information about the VPC.
     * @param string|null $accessPointName The name of the access point.
     * @param string|null $networkOrigin The network origin of the access point.
     */
    public function __construct(
        ?AccessPointVpcConfiguration $vpcConfiguration = null,
        ?string $accessPointName = null,
        ?string $networkOrigin = null
    )
    {
        $this->vpcConfiguration = $vpcConfiguration;
        $this->accessPointName = $accessPointName;
        $this->networkOrigin = $networkOrigin;
    }
}