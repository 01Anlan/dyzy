<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the CompleteMultipartUpload operation.
 * Class CompleteMultipartUploadResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CompleteMultipartUploadResult extends ResultModel
{

    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the uploaded object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The URL that is used to access the uploaded object.
     * @var string|null
     */
    public ?string $location;

    /**
     * The ETag that is generated when an object is created. ETags are used to identify the content of objects.If an object is created by calling the CompleteMultipartUpload operation, the ETag value is not the MD5 hash of the object content but a unique value calculated based on a specific rule. The ETag of an object can be used to check whether the object content is modified. However, we recommend that you use the MD5 hash of an object rather than the ETag value of the object to verify data integrity.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The encoding type of the object name in the response. If this parameter is specified in the request, the object name is encoded in the response.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The 64-bit CRC value of the object. If encoding-type is specified in the request, the object name is encoded in the returned result.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * The version ID of the source object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * Callback result, it is valid only when the callback is set.
     * @var array|null
     */
    public ?array $callbackResult;

    /**
     * CompleteMultipartUploadResult constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the uploaded object.
     * @param string|null $location The URL that is used to access the uploaded object.
     * @param string|null $etag The ETag that is generated when an object is created.
     * @param string|null $encodingType The encoding type of the object name in the response.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     * @param string|null $versionId The version ID of the source object.
     * @param array|null $callbackResult Callback result.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $location = null,
        ?string $etag = null,
        ?string $encodingType = null,
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?array $callbackResult = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->location = $location;
        $this->etag = $etag;
        $this->encodingType = $encodingType;
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->callbackResult = $callbackResult;
    }
}
