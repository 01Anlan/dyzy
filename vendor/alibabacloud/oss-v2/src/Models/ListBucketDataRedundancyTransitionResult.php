<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the ListBucketDataRedundancyTransition operation.
 * Class ListBucketDataRedundancyTransitionResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListBucketDataRedundancyTransitionResult extends ResultModel
{
    /**
     * The container for listed redundancy type change tasks.
     * @var ListBucketDataRedundancyTransition|null
     */
    #[TagBody(rename: 'ListBucketDataRedundancyTransition', type: ListBucketDataRedundancyTransition::class, format: 'xml')]
    public ?ListBucketDataRedundancyTransition $listBucketDataRedundancyTransition;

    /**
     * ListBucketDataRedundancyTransitionRequest constructor.
     * @param ListBucketDataRedundancyTransition|null $listBucketDataRedundancyTransition The container for listed redundancy type change tasks.
     */
    public function __construct(
        ?ListBucketDataRedundancyTransition $listBucketDataRedundancyTransition = null
    )
    {
        $this->listBucketDataRedundancyTransition = $listBucketDataRedundancyTransition;
    }
}
