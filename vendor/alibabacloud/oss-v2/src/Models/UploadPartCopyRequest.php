<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the UploadPartCopy operation.
 * Class UploadPartCopyRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class UploadPartCopyRequest extends RequestModel
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
     * The number of parts.
     * @var int|null
     */
    public ?int $partNumber;

    /**
     * The ID that identifies the object to which the parts to upload belong.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The name of the source bucket.
     * @var string|null
     */
    public ?string $sourceBucket;

    /**
     * The path of the source object.
     * @var string|null
     */
    public ?string $sourceKey;

    /**
     * The version ID of the source object.
     * @var string|null
     */
    public ?string $sourceVersionId;

    /**
     * The range of bytes to copy data from the source object. For example, if you specify bytes to 0 to 9, the system transfers byte 0 to byte 9, a total of 10 bytes.brDefault value: null- If the x-oss-copy-source-range request header is not specified, the entire source object is copied.- If the x-oss-copy-source-range request header is specified, the response contains the length of the entire object and the range of bytes to be copied for this operation. For example, Content-Range: bytes 0~9/44 indicates that the length of the entire object is 44 bytes. The range of bytes to be copied is byte 0 to byte 9.- If the specified range does not conform to the range conventions, OSS copies the entire object and does not include Content-Range in the response.
     * @var string|null
     */
    public ?string $sourceRange;

    /**
     * The copy operation condition. If the ETag value of the source object is the same as the ETag value provided by the user, OSS copies data. Otherwise, OSS returns 412 Precondition Failed.brDefault value: null
     * @var string|null
     */
    public ?string $ifMatch;

    /**
     * The object transfer condition. If the input ETag value does not match the ETag value of the object, the system transfers the object normally and returns 200 OK. Otherwise, OSS returns 304 Not Modified.brDefault value: null
     * @var string|null
     */
    public ?string $ifNoneMatch;

    /**
     * The object transfer condition. If the specified time is earlier than the actual modified time of the object, the system transfers the object normally and returns 200 OK. Otherwise, OSS returns 304 Not Modified.brDefault value: nullbrTime format: ddd, dd MMM yyyy HH:mm:ss GMT. Example: Fri, 13 Nov 2015 14:47:53 GMT.
     * @var string|null
     */
    public ?string $ifModifiedSince;

    /**
     * The object transfer condition. If the specified time is the same as or later than the actual modified time of the object, OSS transfers the object normally and returns 200 OK. Otherwise, OSS returns 412 Precondition Failed.brDefault value: null
     * @var string|null
     */
    public ?string $ifUnmodifiedSince;

    /**
     * Specify the speed limit value. The speed limit value ranges from  245760 to 838860800, with a unit of bit/s.
     * @var int|null
     */
    public ?int $trafficLimit;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * UploadPartCopyRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param int|null $partNumber The number of parts.
     * @param string|null $uploadId The ID that identifies the object to which the parts to upload belong.
     * @param string|null $sourceBucket The name of the source bucket.
     * @param string|null $sourceKey The path of the source object.
     * @param string|null $sourceVersionId The version ID of the source object.
     * @param string|null $sourceRange The range of bytes to copy data from the source object.
     * @param string|null $ifMatch If the ETag value of the source object is the same as the ETag value provided by the user, OSS copies data.
     * @param string|null $ifNoneMatch If the input ETag value does not match the ETag value of the object the system transfers the object normally and returns 200 OK.
     * @param string|null $ifModifiedSince If the specified time is earlier than the actual modified time of the object, the system transfers the object normally and returns 200 OK.
     * @param string|null $ifUnmodifiedSince If the specified time is the same as or later than the actual modified time of the object, OSS transfers the object normally and returns 200 OK.
     * @param int|null $trafficLimit Specify the speed limit value.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?int $partNumber = null,
        ?string $uploadId = null,
        ?string $sourceBucket = null,
        ?string $sourceKey = null,
        ?string $sourceVersionId = null,
        ?string $sourceRange = null,
        ?string $ifMatch = null,
        ?string $ifNoneMatch = null,
        ?string $ifModifiedSince = null,
        ?string $ifUnmodifiedSince = null,
        ?int $trafficLimit = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->partNumber = $partNumber;
        $this->uploadId = $uploadId;
        $this->sourceBucket = $sourceBucket;
        $this->sourceKey = $sourceKey;
        $this->sourceVersionId = $sourceVersionId;
        $this->sourceRange = $sourceRange;
        $this->ifMatch = $ifMatch;
        $this->ifNoneMatch = $ifNoneMatch;
        $this->ifModifiedSince = $ifModifiedSince;
        $this->ifUnmodifiedSince = $ifUnmodifiedSince;
        $this->trafficLimit = $trafficLimit;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
