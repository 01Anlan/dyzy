<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketInfo operation.
 * Class GetBucketInfoResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketInfoResult extends ResultModel
{
    /**
     * The container that stores the information about the bucket.
     * @var BucketInfo|null
     */
    public ?BucketInfo $bucketInfo;

    /**
     * GetBucketInfoResult constructor.
     * @param BucketInfo|null $bucketInfo The container that stores the information about the bucket.
     */
    public function __construct(
        ?BucketInfo $bucketInfo = null
    )
    {
        $this->bucketInfo = $bucketInfo;
    }
}
