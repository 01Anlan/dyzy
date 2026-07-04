<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutSymlink operation.
 * Class PutSymlinkRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutSymlinkRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The full path of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The target object to which the symbolic link points.
     * The naming conventions for target objects are the same as those for objects.
     * - Similar to ObjectName, TargetObjectName must be URL-encoded.
     * - The target object to which a symbolic link points cannot be a symbolic link.
     * @var string|null
     */
    public ?string $target;

    /**
     * The access control list (ACL) of the object. Default value: default. Valid values:- default: The ACL of the object is the same as that of the bucket in which the object is stored. - private: The ACL of the object is private. Only the owner of the object and authorized users can read and write this object. - public-read: The ACL of the object is public-read. Only the owner of the object and authorized users can read and write this object. Other users can only read the object. Exercise caution when you set the object ACL to this value. - public-read-write: The ACL of the object is public-read-write. All users can read and write this object. Exercise caution when you set the object ACL to this value. For more information about the ACL, see [ACL](~~100676~~).
     * Sees ObjectACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The storage class of the bucket. Default value: Standard.  Valid values:- Standard- IA- Archive- ColdArchive
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The metadata of the object that you want to symlink.
     * @var array|null
     */
    public ?array $metadata;

    /**
     * Specifies whether the PutSymlink operation overwrites the object that has the same name as that of the symbolic link you want to create.   - If the value of x-oss-forbid-overwrite is not specified or set to false, existing objects can be overwritten by objects that have the same names.   - If the value of x-oss-forbid-overwrite is set to true, existing objects cannot be overwritten by objects that have the same names. If you specify the x-oss-forbid-overwrite request header, the queries per second (QPS) performance of OSS is degraded. If you want to use the x-oss-forbid-overwrite request header to perform a large number of operations (QPS greater than 1,000), contact technical support.  The x-oss-forbid-overwrite request header is invalid when versioning is enabled or suspended for the destination bucket. In this case, the object with the same name can be overwritten.
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * PutSymlinkRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $target The target object to which the symbolic link points.
     * @param string|null $acl The ACL of the object.
     * @param string|null $storageClass The storage class of the bucket.
     * @param array|null $metadata The metadata of the object that you want to symlink.
     * @param bool|null $forbidOverwrite Specifies whether the PutSymlink operation overwrites the object that has the same name as that of the symbolic link you want to create.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     * @param string|null $symlinkTarget The target object to which the symbolic link points. The symlinkTarget parameter has the same functionality as the target parameter. It is the normalized name of target. If both exist simultaneously, the value of symlinkTarget will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $target = null,
        ?string $acl = null,
        ?string $storageClass = null,
        ?array $metadata = null,
        ?bool $forbidOverwrite = null,
        ?string $requestPayer = null,
        ?array $options = null,
        ?string $objectAcl = null,
        ?string $symlinkTarget = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->target = $symlinkTarget ?? $target;
        $this->acl = $objectAcl ?? $acl;
        $this->storageClass = $storageClass;
        $this->metadata = $metadata;
        $this->forbidOverwrite = $forbidOverwrite;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
