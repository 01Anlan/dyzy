<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketLifecycle operation.
 * Class PutBucketLifecycleRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketLifecycleRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * Specifies whether to allow overlapped prefixes. Valid values:true: Overlapped prefixes are allowed.false: Overlapped prefixes are not allowed.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'header', rename: 'x-oss-allow-same-action-overlap', type: 'string')]
    public ?string $allowSameActionOverlap;

    /**
     * The container of the request body.
     * @var LifecycleConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'LifecycleConfiguration', type: 'xml')]
    public ?LifecycleConfiguration $lifecycleConfiguration;

    /**
     * PutBucketLifecycleRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $allowSameActionOverlap Specifies whether to allow overlapped prefixes.
     * @param LifecycleConfiguration|null $lifecycleConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $allowSameActionOverlap = null,
        ?LifecycleConfiguration $lifecycleConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->allowSameActionOverlap = $allowSameActionOverlap;
        $this->lifecycleConfiguration = $lifecycleConfiguration;
        parent::__construct($options);
    }
}