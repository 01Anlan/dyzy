<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the ListUserDataRedundancyTransition operation.
 * Class ListUserDataRedundancyTransitionResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListUserDataRedundancyTransitionResult extends ResultModel
{
    /**
     * @var ListBucketDataRedundancyTransition|null
     */
    #[TagBody(rename: 'ListBucketDataRedundancyTransition', type: ListBucketDataRedundancyTransition::class, format: 'xml')]
    public ?ListBucketDataRedundancyTransition $listBucketDataRedundancyTransition;

    /**
     * ListUserDataRedundancyTransitionRequest constructor.
     * @param ListBucketDataRedundancyTransition|null $listBucketDataRedundancyTransition
     */
    public function __construct(
        ?ListBucketDataRedundancyTransition $listBucketDataRedundancyTransition = null
    )
    {
        $this->listBucketDataRedundancyTransition = $listBucketDataRedundancyTransition;
    }
}
