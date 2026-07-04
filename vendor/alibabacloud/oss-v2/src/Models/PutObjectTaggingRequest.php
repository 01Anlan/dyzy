<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutObjectTagging operation.
 * Class PutObjectTaggingRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutObjectTaggingRequest extends RequestModel
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
     * The request body schema.
     * @var Tagging|null
     */
    public ?Tagging $tagging;

    /**
     * The version id of the target object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * PutObjectTaggingRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param Tagging|null $tagging The request body schema.
     * @param string|null $versionId The version id of the target object.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?Tagging $tagging = null,
        ?string $versionId = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->tagging = $tagging;
        $this->versionId = $versionId;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
