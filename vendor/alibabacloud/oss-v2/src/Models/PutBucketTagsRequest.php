<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketTags operation.
 * Class PutBucketTagsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketTagsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var Tagging|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'Tagging', type: 'xml')]
    public ?Tagging $tagging;

    /**
     * PutBucketTagsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param Tagging|null $tagging The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?Tagging $tagging = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->tagging = $tagging;
        parent::__construct($options);
    }
}