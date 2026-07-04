<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * Class ObjectACLType
 * @package AlibabaCloud\Oss\V2\Models
 */
class ObjectACLType
{
    /**
     * Only the object owner is allowed to perform read and write operations on the object.
     * Other users cannot access the object.
     */
    const PRIVATE = 'private';

    /**
     * ObjectACLPublicRead Only the object owner can write data to the object.
     * Other users, including anonymous users, can only read the object.
     */
    const PUBLIC_READ = 'public-read';

    /**
     * All users, including anonymous users, can perform read and write operations on the object.
     */
    const PUBLIC_READ_WRITE = 'public-read-write';

    /**
     * The ACL of the object is the same as that of the bucket in which the object is stored.
     */
    const DEFAULT = 'default';
}

