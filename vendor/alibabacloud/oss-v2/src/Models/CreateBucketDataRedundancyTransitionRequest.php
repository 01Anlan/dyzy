<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the CreateBucketDataRedundancyTransition operation.
 * Class CreateBucketDataRedundancyTransitionRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CreateBucketDataRedundancyTransitionRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The redundancy type to which you want to convert the bucket. You can only convert the redundancy type of a bucket from LRS to ZRS.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'x-oss-target-redundancy-type', type: 'string')]
    public ?string $targetRedundancyType;

    /**
     * CreateBucketDataRedundancyTransitionRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $targetRedundancyType The redundancy type to which you want to convert the bucket.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $targetRedundancyType = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->targetRedundancyType = $targetRedundancyType;
        parent::__construct($options);
    }
}