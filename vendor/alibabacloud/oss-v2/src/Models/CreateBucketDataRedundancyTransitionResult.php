<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the CreateBucketDataRedundancyTransition operation.
 * Class CreateBucketDataRedundancyTransitionResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CreateBucketDataRedundancyTransitionResult extends ResultModel
{
    /**
     * The container in which the redundancy type conversion task is stored.
     * @var BucketDataRedundancyTransition|null
     */
    #[TagBody(rename: 'BucketDataRedundancyTransition', type: BucketDataRedundancyTransition::class, format: 'xml')]
    public ?BucketDataRedundancyTransition $bucketDataRedundancyTransition;

    /**
     * CreateBucketDataRedundancyTransitionRequest constructor.
     * @param BucketDataRedundancyTransition|null $bucketDataRedundancyTransition The container in which the redundancy type conversion task is stored.
     */
    public function __construct(
        ?BucketDataRedundancyTransition $bucketDataRedundancyTransition = null
    )
    {
        $this->bucketDataRedundancyTransition = $bucketDataRedundancyTransition;
    }
}
