<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutBucketAcl operation.
 * Class PutBucketAclRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketAclRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The ACL that you want to configure or modify for the bucket. The x-oss-acl header is included in PutBucketAcl requests to configure or modify the ACL of the bucket. If this header is not included, the ACL configurations do not take effect.Valid values:*   public-read-write: All users can read and write objects in the bucket. Exercise caution when you set the value to public-read-write.*   public-read: Only the owner and authorized users of the bucket can read and write objects in the bucket. Other users can only read objects in the bucket. Exercise caution when you set the value to public-read.*   private: Only the owner and authorized users of this bucket can read and write objects in the bucket. Other users cannot access objects in the bucket.
     * Sees BucketACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * PutBucketAclRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $acl The ACL that you want to configure or modify for the bucket.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $acl = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->acl = $acl;
        parent::__construct($options);
    }
}
