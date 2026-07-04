<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;

/**
 * The result for the InitiateBucketWorm operation.
 * Class AbortBucketWormResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class InitiateBucketWormResult extends ResultModel
{
    /**
     * The ID of the retention policy.
     * @var string|null
     */
    #[TagHeader(rename: 'x-oss-worm-id', type: 'string')]
    public ?string $wormId;

    /**
     * InitiateBucketWormRequest constructor.
     * @param string|null $wormId The ID of the retention policy.
     */
    public function __construct(
        ?string $wormId = null
    )
    {
        $this->wormId = $wormId;
    }
}