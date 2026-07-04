<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketLocation operation.
 * Class GetBucketLocationResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketLocationResult extends ResultModel
{
    /**
     * The region in which the bucket resides.Examples: oss-cn-hangzhou, oss-cn-shanghai, oss-cn-qingdao, oss-cn-beijing, oss-cn-zhangjiakou, oss-cn-hongkong, oss-cn-shenzhen, oss-us-west-1, oss-us-east-1, and oss-ap-southeast-1.For more information about the regions in which buckets reside, see [Regions and endpoints](~~31837~~).
     * @var string|null
     */
    public ?string $location;

    /**
     * GetBucketLocationResult constructor.
     * @param string|null $location The region in which the bucket resides.
     */
    public function __construct(
        ?string $location = null
    )
    {
        $this->location = $location;
    }
}
