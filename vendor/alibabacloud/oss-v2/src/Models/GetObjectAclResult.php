<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetObjectAcl operation.
 * Class GetObjectAclResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetObjectAclResult extends ResultModel
{

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The container that stores information about the object owner.
     * @var Owner|null
     */
    public ?Owner $owner;

    /**
     * The class of the container that stores the ACL information.
     * @var AccessControlList|null
     */
    public ?AccessControlList $accessControlList;

    public function __construct(
        ?string $versionId = null,
        ?Owner $owner = null,
        ?AccessControlList $accessControlList = null
    )
    {
        $this->versionId = $versionId;
        $this->owner = $owner;
        $this->accessControlList = $accessControlList;
    }
}
