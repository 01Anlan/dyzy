<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the HeadObject operation.
 * Class HeadObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class HeadObjectRequest extends RequestModel
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
     * The version ID of the object for which you want to query metadata.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * If the ETag value that is specified in the request matches the ETag value of the object, OSS returns 200 OK and the metadata of the object. Otherwise, OSS returns 412 precondition failed. Default value: null.
     * @var string|null
     */
    public ?string $ifMatch;

    /**
     * If the ETag value that is specified in the request does not match the ETag value of the object, OSS returns 200 OK and the metadata of the object. Otherwise, OSS returns 304 Not Modified. Default value: null.
     * @var string|null
     */
    public ?string $ifNoneMatch;

    /**
     * If the time that is specified in the request is earlier than the time when the object is modified, OSS returns 200 OK and the metadata of the object. Otherwise, OSS returns 304 not modified. Default value: null.
     * @var string|null
     */
    public ?string $ifModifiedSince;

    /**
     * If the time that is specified in the request is later than or the same as the time when the object is modified, OSS returns 200 OK and the metadata of the object. Otherwise, OSS returns 412 precondition failed. Default value: null.
     * @var string|null
     */
    public ?string $ifUnmodifiedSince;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * HeadObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $versionId The version ID of the object for which you want to query metadata.
     * @param string|null $ifMatch If the ETag value that is specified in the request matches the ETag value of the object, OSS returns 200 OK and the metadata of the object.
     * @param string|null $ifNoneMatch If the ETag value that is specified in the request does not match the ETag value of the object, OSS returns 200 OK and the metadata of the object.
     * @param string|null $ifModifiedSince If the time that is specified in the request is earlier than the time when the object is modified, OSS returns 200 OK and the metadata of the object.
     * @param string|null $ifUnmodifiedSince If the time that is specified in the request is later than or the same as the time when the object is modified, OSS returns 200 OK and the metadata of the object.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $versionId = null,
        ?string $ifMatch = null,
        ?string $ifNoneMatch = null,
        ?string $ifModifiedSince = null,
        ?string $ifUnmodifiedSince = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->versionId = $versionId;
        $this->ifMatch = $ifMatch;
        $this->ifNoneMatch = $ifNoneMatch;
        $this->ifModifiedSince = $ifModifiedSince;
        $this->ifUnmodifiedSince = $ifUnmodifiedSince;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
