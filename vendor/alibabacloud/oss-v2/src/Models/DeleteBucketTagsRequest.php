<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the DeleteBucketTags operation.
 * Class DeleteBucketTagsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteBucketTagsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'tagging', type: 'string')]
    public ?string $tagging;

    /**
     * DeleteBucketTagsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $tagging
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $tagging = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->tagging = $tagging;
        parent::__construct($options);
    }
}