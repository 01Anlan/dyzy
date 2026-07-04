<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketTags operation.
 * Class GetBucketTagsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketTagsResult extends ResultModel
{
    /**
     * The container that stores the returned tags of the bucket. If no tags are configured for the bucket, an XML message body is returned in which the Tagging element is empty.
     * @var Tagging|null
     */
    #[TagBody(rename: 'Tagging', type: Tagging::class, format: 'xml')]
    public ?Tagging $tagging;

    /**
     * GetBucketTagsRequest constructor.
     * @param Tagging|null $tagging The container that stores the returned tags of the bucket.
     */
    public function __construct(
        ?Tagging $tagging = null
    )
    {
        $this->tagging = $tagging;
    }
}
