<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\TagBody;
use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * Class ListAccessPointsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListAccessPointsResult extends ResultModel
{
    /**
     * The container that stores the information about all access points.
     * @var ListAccessPoints|null
     */
    #[TagBody(rename: 'ListAccessPointsResult', type: ListAccessPoints::class, format: 'xml')]
    public ?ListAccessPoints $listAccessPoints;

    /**
     * ListAccessPointsResult constructor.
     * @param ListAccessPoints|null $listAccessPoints The container that stores the information about all access points.
     */
    public function __construct(
        ?ListAccessPoints $listAccessPoints = null
    )
    {
        $this->listAccessPoints = $listAccessPoints;
    }
}