<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the GetBucketInfo operation.
 * Class GetBucketInfoRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketInfoRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * GetBucketInfoRequest constructor.
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
