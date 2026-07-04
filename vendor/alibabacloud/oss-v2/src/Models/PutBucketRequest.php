<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutBucket operation.
 * Class PutBucketRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketRequest extends RequestModel
{
    /**
     * The name of the bucket. The name of a bucket must comply with the following naming conventions:*   The name can contain only lowercase letters, digits, and hyphens (-).*   It must start and end with a lowercase letter or a digit.*   The name must be 3 to 63 characters in length.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The access control list (ACL) of the bucket to be created. Valid values:*   public-read-write*   public-read*   private (default)For more information, see [Bucket ACL](~~31843~~).
     * Sees BucketACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The ID of the resource group.*   If you include the header in the request and specify the ID of the resource group, the bucket that you create belongs to the resource group. If the specified resource group ID is rg-default-id, the bucket that you create belongs to the default resource group.*   If you do not include the header in the request, the bucket that you create belongs to the default resource group.You can obtain the ID of a resource group in the Resource Management console or by calling the ListResourceGroups operation. For more information, see [View basic information of a resource group](~~151181~~) and [ListResourceGroups](~~158855~~).  You cannot configure a resource group for an Anywhere Bucket.
     * @var string|null
     */
    public ?string $resourceGroupId;

    /**
     * The container that stores the request body.
     * @var CreateBucketConfiguration|null
     */
    public ?CreateBucketConfiguration $createBucketConfiguration;

    /**
     * PutBucketRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $acl The access control list (ACL) of the bucket to be created.
     * @param string|null $resourceGroupId The ID of the resource group.
     * @param CreateBucketConfiguration|null $createBucketConfiguration The container that stores the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $acl = null,
        ?string $resourceGroupId = null,
        ?CreateBucketConfiguration $createBucketConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->acl = $acl;
        $this->resourceGroupId = $resourceGroupId;
        $this->createBucketConfiguration = $createBucketConfiguration;
        parent::__construct($options);
    }
}
