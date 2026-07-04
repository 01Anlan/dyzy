<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetBucketArchiveDirectRead operation.
 * Class GetBucketArchiveDirectReadRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketArchiveDirectReadRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * GetBucketArchiveDirectReadRequest constructor.
     * @param string|null $bucket The name of the bucket.
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