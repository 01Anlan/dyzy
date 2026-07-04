<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\TagBody;
use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * Class CreateAccessPointResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CreateAccessPointResult extends ResultModel
{
    /**
     * The container that stores create access point result.
     * @var CreateAccessPoint|null
     */
    #[TagBody(rename: 'CreateAccessPointResult', type: CreateAccessPoint::class, format: 'xml')]
    public ?CreateAccessPoint $createAccessPoint;

    /**
     * CreateAccessPointResult constructor.
     * @param CreateAccessPoint|null $createAccessPoint The container that stores create access point result.
     */
    public function __construct(
        ?CreateAccessPoint $createAccessPoint = null
    )
    {
        $this->createAccessPoint = $createAccessPoint;
    }
}