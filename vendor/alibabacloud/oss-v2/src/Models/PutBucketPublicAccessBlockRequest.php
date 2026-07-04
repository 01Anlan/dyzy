<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketPublicAccessBlock operation.
 * Class PutBucketPublicAccessBlockRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketPublicAccessBlockRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * Request body.
     * @var PublicAccessBlockConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'PublicAccessBlockConfiguration', type: 'xml')]
    public ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration;

    /**
     * PutBucketPublicAccessBlockRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param PublicAccessBlockConfiguration|null $publicAccessBlockConfiguration Request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->publicAccessBlockConfiguration = $publicAccessBlockConfiguration;
        parent::__construct($options);
    }
}