<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class GetAccessPoint
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'GetAccessPointResult')]
final class GetAccessPoint extends Model
{
    /**
     * The network origin of the access point. Valid values: vpc and internet. vpc: You can only use the specified VPC ID to access the access point. internet: You can use public endpoints and internal endpoints to access the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'NetworkOrigin', type: 'string')]
    public ?string $networkOrigin;

    /**
     * The ARN of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessPointArn', type: 'string')]
    public ?string $accessPointArn;

    /**
     * The alias of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'Alias', type: 'string')]
    public ?string $alias;

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
     * The ID of the Alibaba Cloud account for which the access point is configured.
     * @var string|null
     */
    #[XmlElement(rename: 'AccountId', type: 'string')]
    public ?string $accountId;

    /**
     * The container that stores the network origin information about the access point.
     * @var AccessPointEndpoints|null
     */
    #[XmlElement(rename: 'Endpoints', type: AccessPointEndpoints::class)]
    public ?AccessPointEndpoints $endpoints;

    /**
     * The container that stores the Block Public Access configurations.
     * @var PublicAccessBlockConfiguration|null
     */
    #[XmlElement(rename: 'PublicAccessBlockConfiguration', type: PublicAccessBlockConfiguration::class)]
    public ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration;

    /**
     * The time when the access point was created.
     * @var string|null
     */
    #[XmlElement(rename: 'CreationDate', type: 'string')]
    public ?string $creationDate;

    /**
     * The name of the access point.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessPointName', type: 'string')]
    public ?string $accessPointName;

    /**
     * The container that stores the information about the VPC.
     * @var AccessPointVpcConfiguration|null
     */
    #[XmlElement(rename: 'VpcConfiguration', type: AccessPointVpcConfiguration::class)]
    public ?AccessPointVpcConfiguration $vpcConfiguration;

    /**
     * GetAccessPoint constructor.
     * @param string|null $networkOrigin The network origin of the access point.
     * @param string|null $accessPointArn The ARN of the access point.
     * @param string|null $alias The alias of the access point.
     * @param string|null $status The status of the access point.
     * @param string|null $bucket The name of the bucket for which the access point is configured.
     * @param string|null $accountId The ID of the Alibaba Cloud account for which the access point is configured.
     * @param AccessPointEndpoints|null $endpoints The container that stores the network origin information about the access point.
     * @param PublicAccessBlockConfiguration|null $publicAccessBlockConfiguration The container that stores the Block Public Access configurations.
     * @param string|null $creationDate The time when the access point was created.
     * @param string|null $accessPointName The name of the access point.
     * @param AccessPointVpcConfiguration|null $vpcConfiguration The container that stores the information about the VPC.
     */
    public function __construct(
        ?string $networkOrigin = null,
        ?string $accessPointArn = null,
        ?string $alias = null,
        ?string $status = null,
        ?string $bucket = null,
        ?string $accountId = null,
        ?AccessPointEndpoints $endpoints = null,
        ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration = null,
        ?string $creationDate = null,
        ?string $accessPointName = null,
        ?AccessPointVpcConfiguration $vpcConfiguration = null
    )
    {
        $this->networkOrigin = $networkOrigin;
        $this->accessPointArn = $accessPointArn;
        $this->alias = $alias;
        $this->status = $status;
        $this->bucket = $bucket;
        $this->accountId = $accountId;
        $this->endpoints = $endpoints;
        $this->publicAccessBlockConfiguration = $publicAccessBlockConfiguration;
        $this->creationDate = $creationDate;
        $this->accessPointName = $accessPointName;
        $this->vpcConfiguration = $vpcConfiguration;
    }
}