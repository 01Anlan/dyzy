<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketResourceGroup operation.
 * Class GetBucketResourceGroupResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketResourceGroupResult extends ResultModel
{
    /**
     * The container that stores the ID of the resource group.
     * @var BucketResourceGroupConfiguration|null
     */
    #[TagBody(rename: 'BucketResourceGroupConfiguration', type: BucketResourceGroupConfiguration::class, format: 'xml')]
    public ?BucketResourceGroupConfiguration $bucketResourceGroupConfiguration;

    /**
     * GetBucketResourceGroupRequest constructor.
     * @param BucketResourceGroupConfiguration|null $bucketResourceGroupConfiguration The container that stores the ID of the resource group.
     */
    public function __construct(
        ?BucketResourceGroupConfiguration $bucketResourceGroupConfiguration = null
    )
    {
        $this->bucketResourceGroupConfiguration = $bucketResourceGroupConfiguration;
    }
}
