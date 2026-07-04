<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * The access control list (ACL) for a bucket.
 * Class BucketACLType
 * @package AlibabaCloud\Oss\V2\Models
 */
class BucketACLType
{
    /**
     * Only the bucket owner can perform read and write operations on objects in the bucket.
     * Other users cannot access the objects in the bucket.
     */
    const PRIVATE = 'private';

    /**
     * Only the bucket owner can write data to objects in the bucket.
     * Other users, including anonymous users, can only read objects in the bucket.
     */
    const PUBLIC_READ = 'public-read';

    /**
     * All users, including anonymous users, can perform read and write operations on the bucket.
     */
    const PUBLIC_READ_WRITE = 'public-read-write';
}

