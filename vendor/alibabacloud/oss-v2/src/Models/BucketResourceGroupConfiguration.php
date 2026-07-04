<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class BucketResourceGroupConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'BucketResourceGroupConfiguration')]
final class BucketResourceGroupConfiguration extends Model
{
    /**
     * The ID of the resource group to which the bucket belongs.
     * @var string|null
     */
    #[XmlElement(rename: 'ResourceGroupId', type: 'string')]
    public ?string $resourceGroupId;

    /**
     * BucketResourceGroupConfiguration constructor.
     * @param string|null $resourceGroupId The ID of the resource group to which the bucket belongs.
     */
    public function __construct(
        ?string $resourceGroupId = null
    )
    {
        $this->resourceGroupId = $resourceGroupId;
    }
}