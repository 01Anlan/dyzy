<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketWorm operation.
 * Class AbortBucketWormResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketWormResult extends ResultModel
{
    /**
     * The container that stores the information about retention policies of the bucket.
     * @var WormConfiguration|null
     */
    #[TagBody(rename: 'WormConfiguration', type: WormConfiguration::class, format: 'xml')]
    public ?WormConfiguration $wormConfiguration;

    /**
     * GetBucketWormRequest constructor.
     * @param WormConfiguration|null $wormConfiguration The container that stores the information about retention policies of the bucket.
     */
    public function __construct(
        ?WormConfiguration $wormConfiguration = null
    )
    {
        $this->wormConfiguration = $wormConfiguration;
    }
}