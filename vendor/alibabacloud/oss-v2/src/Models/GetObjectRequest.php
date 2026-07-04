<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the GetObject operation.
 * Class GetObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetObjectRequest extends RequestModel
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
     * The version ID of the object that you want to query.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * If the ETag specified in the request matches the ETag value of the object, OSS transmits the object and returns 200 OK. If the ETag specified in the request does not match the ETag value of the object, OSS returns 412 Precondition Failed. The ETag value of an object is used to check whether the content of the object has changed. You can check data integrity by using the ETag value. Default value: null
     * @var string|null
     */
    public ?string $ifMatch;

    /**
     * If the ETag specified in the request does not match the ETag value of the object, OSS transmits the object and returns 200 OK. If the ETag specified in the request matches the ETag value of the object, OSS returns 304 Not Modified. You can specify both the **If-Match** and **If-None-Match** headers in a request. Default value: null
     * @var string|null
     */
    public ?string $ifNoneMatch;

    /**
     * If the time specified in this header is earlier than the object modified time or is invalid, OSS returns the object and 200 OK. If the time specified in this header is later than or the same as the object modified time, OSS returns 304 Not Modified. The time must be in GMT. Example: `Fri, 13 Nov 2015 14:47:53 GMT`.Default value: null
     * @var string|null
     */
    public ?string $ifModifiedSince;

    /**
     * If the time specified in this header is the same as or later than the object modified time, OSS returns the object and 200 OK. If the time specified in this header is earlier than the object modified time, OSS returns 412 Precondition Failed.                               The time must be in GMT. Example: `Fri, 13 Nov 2015 14:47:53 GMT`.You can specify both the **If-Modified-Since** and **If-Unmodified-Since** headers in a request. Default value: null
     * @var string|null
     */
    public ?string $ifUnmodifiedSince;

    /**
     * The content range of the object to be returned.
     * If the value of Range is valid, the total size of the object and the content range are returned.
     * For example, Content-Range: bytes 0~9/44 indicates that the total size of the object is 44 bytes,
     * and the range of data returned is the first 10 bytes.
     * However, if the value of Range is invalid, the entire object is returned,
     * and the response does not include the Content-Range parameter.
     * @var string|null
     */
    public ?string $rangeHeader;

    /**
     * Specify standard behaviors to download data by range
     * If the value is "standard", the download behavior is modified when the specified range is not within the valid range.
     * For an object whose size is 1,000 bytes:
     * 1) If you set Range: bytes to 500-2000, the value at the end of the range is invalid.
     * In this case, OSS returns HTTP status code 206 and the data that is within the range of byte 500 to byte 999.
     * 2) If you set Range: bytes to 1000-2000, the value at the start of the range is invalid.
     * In this case, OSS returns HTTP status code 416 and the InvalidRange error code.
     * @var string|null
     */
    public ?string $rangeBehavior;

    /**
     * The cache-control header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseCacheControl;

    /**
     * The content-disposition header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseContentDisposition;

    /**
     * The content-encoding header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseContentEncoding;

    /**
     * The content-language header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseContentLanguage;

    /**
     * The content-type header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseContentType;

    /**
     * The expires header in the response that OSS returns.
     * @var string|null
     */
    public ?string $responseExpires;

    /**
     * Specify the speed limit value. The speed limit value ranges from 245760 to 838860800, with a unit of bit/s.
     * @var int|null
     */
    public ?int $trafficLimit;

    /**
     * Image processing parameters
     * @var string|null
     */
    public ?string $process;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * Progress callback function
     * @var callable|null
     */
    public $progressFn;

    /**
     * GetObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param string|null $versionId Used to reference a specific version of the object.
     * @param string|null $ifMatch If the ETag specified in the request matches the ETag value of the object, the object and 200 OK are returned.
     * @param string|null $ifNoneMatch If the ETag specified in the request does not match the ETag value of the object, the object and 200 OK are returned.
     * @param string|null $ifModifiedSince If the time specified in this header is earlier than the object modified time or is invalid, the object and 200 OK are returned.
     * @param string|null $ifUnmodifiedSince If the time specified in this header is the same as or later than the object modified time, the object and 200 OK are returned.
     * @param string|null $rangeHeader The content range of the object to be returned.
     * @param string|null $rangeBehavior Specify standard behaviors to download data by range.
     * @param string|null $responseCacheControl The cache-control header to be returned in the response.
     * @param string|null $responseContentDisposition The content-disposition header to be returned in the response.
     * @param string|null $responseContentEncoding The content-encoding header to be returned in the response.
     * @param string|null $responseContentLanguage The content-language header to be returned in the response.
     * @param string|null $responseContentType The content-type header to be returned in the response.
     * @param string|null $responseExpires The expires header to be returned in the response.
     * @param string|null $trafficLimit Specify the speed limit value.
     * @param string|null $process Image processing parameters
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param callable|null $progressFn Progress callback function
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
        ?string $rangeHeader = null,
        ?string $rangeBehavior = null,
        ?string $responseCacheControl = null,
        ?string $responseContentDisposition = null,
        ?string $responseContentEncoding = null,
        ?string $responseContentLanguage = null,
        ?string $responseContentType = null,
        ?string $responseExpires = null,
        ?string $trafficLimit = null,
        ?string $process = null,
        ?string $requestPayer = null,
        ?callable $progressFn = null,
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
        $this->rangeHeader = $rangeHeader;
        $this->rangeBehavior = $rangeBehavior;
        $this->responseCacheControl = $responseCacheControl;
        $this->responseContentDisposition = $responseContentDisposition;
        $this->responseContentEncoding = $responseContentEncoding;
        $this->responseContentLanguage = $responseContentLanguage;
        $this->responseContentType = $responseContentType;
        $this->responseExpires = $responseExpires;
        $this->trafficLimit = $trafficLimit;
        $this->process = $process;
        $this->requestPayer = $requestPayer;
        $this->progressFn = $progressFn;
        parent::__construct($options);
    }
}
