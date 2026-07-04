<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\TagBody;
use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * Class GetAccessPointResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetAccessPointResult extends ResultModel
{
    /**
     * The container that stores the information about access point.
     * @var GetAccessPoint|null
     */
    #[TagBody(rename: 'GetAccessPointResult', type: GetAccessPoint::class, format: 'xml')]
    public ?GetAccessPoint $getAccessPoint;

    /**
     * GetAccessPointResult constructor.
     * @param GetAccessPoint|null $getAccessPoint The container that stores the information about access point.
     */
    public function __construct(
        ?GetAccessPoint $getAccessPoint = null
    )
    {
        $this->getAccessPoint = $getAccessPoint;
    }
}