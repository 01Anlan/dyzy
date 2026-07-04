<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the GetBucketLocation operation.
 * Class GetBucketLocationRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketLocationRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * GetBucketLocationRequest constructor.
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
