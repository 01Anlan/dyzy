<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the GetBucketStat operation.
 * Class GetBucketStatRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketStatRequest extends RequestModel
{
    /**
     * The bucket about which you want to query the information.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * GetBucketStatRequest constructor.
     * @param string|null $bucket The bucket about which you want to query the information.
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
