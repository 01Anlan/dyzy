<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetBucketPublicAccessBlock operation.
 * Class GetBucketPublicAccessBlockRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketPublicAccessBlockRequest extends RequestModel
{
    /**
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * GetBucketPublicAccessBlockRequest constructor.
     * @param string|null $bucket
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        parent::__construct($options);
    }
}