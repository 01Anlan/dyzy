<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the AbortMultipartUpload operation.
 * Class AbortMultipartUploadRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class AbortMultipartUploadRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The full path of the object that you want to upload.
     * @var string|null
     */
    public ?string $key;

    /**
     * The ID of the multipart upload task.
     * @var string|null
     */
    public ?string $uploadId;


    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * AbortMultipartUploadRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object that you want to upload.
     * @param string|null $uploadId The ID of the multipart upload task.
     * @param string|null $requestPayer
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $uploadId = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
