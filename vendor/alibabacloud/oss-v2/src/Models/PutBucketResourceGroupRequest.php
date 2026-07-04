<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketResourceGroup operation.
 * Class PutBucketResourceGroupRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketResourceGroupRequest extends RequestModel
{
    /**
     * The bucket for which you want to modify the ID of the resource group.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var BucketResourceGroupConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'BucketResourceGroupConfiguration', type: 'xml')]
    public ?BucketResourceGroupConfiguration $bucketResourceGroupConfiguration;

    /**
     * PutBucketResourceGroupRequest constructor.
     * @param string|null $bucket The bucket for which you want to modify the ID of the resource group.
     * @param BucketResourceGroupConfiguration|null $bucketResourceGroupConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?BucketResourceGroupConfiguration $bucketResourceGroupConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->bucketResourceGroupConfiguration = $bucketResourceGroupConfiguration;
        parent::__construct($options);
    }
}