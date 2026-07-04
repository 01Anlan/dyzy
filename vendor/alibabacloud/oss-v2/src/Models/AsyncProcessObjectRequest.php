<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the AsyncProcessObject operation.
 * Class AsyncProcessObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class AsyncProcessObjectRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * Image async processing parameters
     * @var string|null
     */
    public ?string $process;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * AsyncProcessObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param string|null $process Image async processing parameters
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $process = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->process = $process;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
