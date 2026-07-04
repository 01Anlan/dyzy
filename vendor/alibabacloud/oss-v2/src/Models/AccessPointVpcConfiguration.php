<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class AccessPointVpcConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AccessPointVpcConfiguration')]
final class AccessPointVpcConfiguration extends Model
{   
    /**
     * The ID of the VPC that is required only when the NetworkOrigin parameter is set to vpc.
     * @var string|null
     */
    #[XmlElement(rename: 'VpcId', type: 'string')]
    public ?string $vpcId;

    /**
     * AccessPointVpcConfiguration constructor.
     * @param string|null $vpcId The ID of the VPC that is required only when the NetworkOrigin parameter is set to vpc.
     */
    public function __construct(
        ?string $vpcId = null
    )
    {   
        $this->vpcId = $vpcId;
    }
}