<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutObjectAcl operation.
 * Class PutObjectAclRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutObjectAclRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The access control list (ACL) of the object.
     * Sees ObjectACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The version id of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * PutObjectAclRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param string|null $acl The access control list (ACL) of the object.
     * @param string|null $versionId The version id of the object.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $acl = null,
        ?string $versionId = null,
        ?string $requestPayer = null,
        ?array $options = null,
        ?string $objectAcl = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->acl = $objectAcl ?? $acl;
        $this->versionId = $versionId;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
