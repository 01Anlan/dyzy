<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketAcl operation.
 * Class GetBucketAclResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketAclResult extends ResultModel
{
    /**
     * The container that stores the information about the bucket owner.
     * @var Owner|null
     */
    public ?Owner $owner;

    /**
     * The class of the container that stores the ACL information.
     * @var AccessControlList|null
     */
    public ?AccessControlList $accessControlList;

    /**
     * AccessControlPolicy constructor.
     *
     * @param Owner|null $owner The container that stores the information about the bucket owner.
     * @param AccessControlList|null $accessControlList The class of the container that stores the ACL information.
     */
    public function __construct(
        ?Owner $owner = null,
        ?AccessControlList $accessControlList = null
    )
    {
        $this->owner = $owner;
        $this->accessControlList = $accessControlList;
    }
}
