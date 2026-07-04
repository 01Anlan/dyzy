<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketDataRedundancyTransition operation.
 * Class GetBucketDataRedundancyTransitionResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketDataRedundancyTransitionResult extends ResultModel
{
    /**
     * The container for a specific redundancy type change task.
     * @var BucketDataRedundancyTransition|null
     */
    #[TagBody(rename: 'BucketDataRedundancyTransition', type: BucketDataRedundancyTransition::class, format: 'xml')]
    public ?BucketDataRedundancyTransition $bucketDataRedundancyTransition;

    /**
     * GetBucketDataRedundancyTransitionRequest constructor.
     * @param BucketDataRedundancyTransition|null $bucketDataRedundancyTransition The container for a specific redundancy type change task.
     */
    public function __construct(
        ?BucketDataRedundancyTransition $bucketDataRedundancyTransition = null
    )
    {
        $this->bucketDataRedundancyTransition = $bucketDataRedundancyTransition;
    }
}
