<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketLifecycle operation.
 * Class GetBucketLifecycleResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketLifecycleResult extends ResultModel
{

    /**
     * The container that stores the lifecycle rules configured for the bucket.
     * @var LifecycleConfiguration|null
     */
    #[TagBody(rename: 'LifecycleConfiguration', type: LifecycleConfiguration::class, format: 'xml')]
    public ?LifecycleConfiguration $lifecycleConfiguration;

    /**
     * GetBucketLifecycleRequest constructor.
     * @param LifecycleConfiguration|null $lifecycleConfiguration The container that stores the lifecycle rules configured for the bucket.
     */
    public function __construct(
        ?LifecycleConfiguration $lifecycleConfiguration = null
    )
    {
        $this->lifecycleConfiguration = $lifecycleConfiguration;
    }
}
