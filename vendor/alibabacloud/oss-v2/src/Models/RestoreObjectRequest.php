<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the RestoreObject operation.
 * Class RestoreObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class RestoreObjectRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The full path of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     *  The version number of the object that you want to restore.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The request body schema.
     * @var RestoreRequest|null
     */
    public ?RestoreRequest $restoreRequest;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * RestoreObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $versionId The version number of the object that you want to restore.
     * @param RestoreRequest|null $restoreRequest The request body schema.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $versionId = null,
        ?RestoreRequest $restoreRequest = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->versionId = $versionId;
        $this->restoreRequest = $restoreRequest;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
