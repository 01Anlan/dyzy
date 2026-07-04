<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetBucketDataRedundancyTransition operation.
 * Class GetBucketDataRedundancyTransitionRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketDataRedundancyTransitionRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The ID of the redundancy change task.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'x-oss-redundancy-transition-taskid', type: 'string')]
    public ?string $redundancyTransitionTaskid;

    /**
     * GetBucketDataRedundancyTransitionRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $redundancyTransitionTaskid The ID of the redundancy change task.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $redundancyTransitionTaskid = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->redundancyTransitionTaskid = $redundancyTransitionTaskid;
        parent::__construct($options);
    }
}