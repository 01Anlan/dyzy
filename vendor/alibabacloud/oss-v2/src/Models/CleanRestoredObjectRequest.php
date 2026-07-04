<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the CleanRestoredObject operation.
 * Class CleanRestoredObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CleanRestoredObjectRequest extends RequestModel
{
    /**
     * The name of the bucket
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * CleanRestoredObjectRequest constructor.
     * @param string|null $bucket The name of the bucket
     * @param string|null $key The name of the object.
     * @param string|null $versionId Version of the object.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $versionId = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->versionId = $versionId;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}