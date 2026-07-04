<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the DescribeRegions operation.
 * Class DescribeRegionsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DescribeRegionsRequest extends RequestModel
{
    /**
     * The region ID of the request.
     * @var string|null
     */
    public ?string $regions;

    /**
     * DescribeRegionsRequest constructor.
     * @param string|null $regions The region ID of the request.
     * @param array|null $options
     */
    public function __construct(
        ?string $regions = null,
        ?array $options = null
    )
    {
        $this->regions = $regions;
        parent::__construct($options);
    }
}