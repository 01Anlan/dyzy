<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the SealAppendObject operation.
 * Class SealAppendObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class SealAppendObjectResult extends ResultModel
{
    /**
     * @var string|null
     */
    public ?string $sealedTime;

    /**
     * SealAppendObjectResult constructor.
     * @param string|null $sealedTime
     */
    public function __construct(
        ?string $sealedTime = null
    )
    {
        $this->sealedTime = $sealedTime;
    }
}