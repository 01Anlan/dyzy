<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the DescribeRegions operation.
 * Class DescribeRegionsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DescribeRegionsResult extends ResultModel
{
    /**
     * The information about the regions.
     * @var array<RegionInfo>|null
     */
    public ?array $regionInfos;

    /**
     * DescribeRegionsRequest constructor.
     * @param array<RegionInfo>|null $regionInfos The information about the regions.
     */
    public function __construct(
        ?array $regionInfos = null
    )
    {
        $this->regionInfos = $regionInfos;
    }
}
