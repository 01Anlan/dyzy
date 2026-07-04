<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the InitiateMultipartUpload operation.
 * Class InitiateMultipartUploadRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class InitiateMultipartUploadRequest extends RequestModel
{
    /**
     * The name of the bucket to which the object is uploaded by the multipart upload task.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object that is uploaded by the multipart upload task.
     * @var string|null
     */
    public ?string $key;

    /**
     * The method used to encode the object name in the response. Only URL encoding is supported. The object name can contain characters encoded in UTF-8. However, the XML 1.0 standard cannot be used to parse specific control characters, such as characters whose ASCII values range from 0 to 10. You can configure the encoding-type parameter to encode object names that include characters that cannot be parsed by XML 1.0 in the response.brDefault value: null
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The storage class of the bucket. Default value: Standard.  Valid values:- Standard- IA- Archive- ColdArchive
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The metadata of the object that you want to upload.
     * @var array|null
     */
    public ?array $metadata;

    /**
     * The caching behavior of the web page when the object is downloaded. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $cacheControl;

    /**
     * The name of the object when the object is downloaded. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $contentDisposition;

    /**
     * The content encoding format of the object when the object is downloaded. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $contentEncoding;


    /**
     * The content length of an HTTP request.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     * The MD5 value for an HTTP request body.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * The content type of an HTTP request.
     * @var string|null
     */
    public ?string $contentType;

    /**
     * The expiration time of the request. Unit: milliseconds. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $expires;

    /**
     * The server-side encryption method that is used to encrypt each part of the object that you want to upload. Valid values: **AES256**, **KMS**, and **SM4**. You must activate Key Management Service (KMS) before you set this header to KMS. If you specify this header in the request, this header is included in the response. OSS uses the method specified by this header to encrypt each uploaded part. When you download the object, the x-oss-server-side-encryption header is included in the response and the header value is set to the algorithm that is used to encrypt the object.
     * @var string|null
     */
    public ?string $serverSideEncryption;

    /**
     * The algorithm that is used to encrypt the object that you want to upload. If this header is not specified, the object is encrypted by using AES-256. This header is valid only when **x-oss-server-side-encryption** is set to KMS. Valid value: SM4.
     * @var string|null
     */
    public ?string $serverSideDataEncryption;

    /**
     * The ID of the CMK that is managed by KMS. This header is valid only when **x-oss-server-side-encryption** is set to KMS.
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * The tag of the object. You can configure multiple tags for the object. Example: TagA=A&TagB=B. The key and value of a tag must be URL-encoded. If a tag does not contain an equal sign (=), the value of the tag is considered an empty string.
     * @var string|null
     */
    public ?string $tagging;

    /**
     * Specifies whether the InitiateMultipartUpload operation overwrites the existing object that has the same name as the object that you want to upload. When versioning is enabled or suspended for the bucket to which you want to upload the object, the **x-oss-forbid-overwrite** header does not take effect. In this case, the InitiateMultipartUpload operation overwrites the existing object that has the same name as the object that you want to upload.   - If you do not specify the **x-oss-forbid-overwrite** header or set the **x-oss-forbid-overwrite** header to **false**, the object that is uploaded by calling the PutObject operation overwrites the existing object that has the same name.   - If the value of **x-oss-forbid-overwrite** is set to **true**, existing objects cannot be overwritten by objects that have the same names. If you specify the **x-oss-forbid-overwrite** request header, the queries per second (QPS) performance of OSS is degraded. If you want to use the **x-oss-forbid-overwrite** request header to perform a large number of operations (QPS greater than 1,000), contact technical support
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * To disable the feature that Content-Type is automatically added based on the object name if not specified.
     * @var bool|null
     */
    public ?bool $disableAutoDetectMimeType;

    /**
     * The total size when using client side encryption, only valid in EncryptionClient.
     * @var int|null
     */
    public ?int $cseDataSize;

    /**
     * The total size when using client side encryption, only valid in EncryptionClient.
     * must aligned to the secret iv length.
     * @var int|null
     */
    public ?int $csePartSize;

    /**
     * InitiateMultipartUploadRequest constructor.
     * @param string|null $bucket The name of the bucket to which the object is uploaded by the multipart upload task.
     * @param string|null $key The name of the object that is uploaded by the multipart upload task.
     * @param string|null $encodingType The method used to encode the object name in the response.
     * @param string|null $storageClass The storage class of the bucket.
     * @param array|null $metadata The metadata of the object that you want to upload.
     * @param string|null $cacheControl The caching behavior of the web page when the object is downloaded.
     * @param string|null $contentDisposition The name of the object when the object is downloaded.
     * @param string|null $contentEncoding The content encoding format of the object when the object is downloaded.
     * @param int|null $contentLength The content length of an HTTP request.
     * @param string|null $contentMd5 The MD5 value for an HTTP request body.
     * @param string|null $contentType The content type of an HTTP request.
     * @param string|null $expires The expiration time of the request.
     * @param string|null $serverSideEncryption The server-side encryption method that is used to encrypt each part of the object that you want to upload.
     * @param string|null $serverSideDataEncryption The algorithm that is used to encrypt the object that you want to upload.
     * @param string|null $serverSideEncryptionKeyId The ID of the CMK that is managed by KMS.
     * @param string|null $tagging The tag of the object.
     * @param bool|null $forbidOverwrite Specifies whether the InitiateMultipartUpload operation overwrites the existing object that has the same name as the object that you want to upload.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param bool|null $disableAutoDetectMimeType To disable the feature that Content-Type is automatically added based on the object name if not specified.
     * @param int|null $cseDataSize The total size when using client side encryption, only valid in EncryptionClient.
     * @param int|null $csePartSize The part size when using client side encryption, only valid in EncryptionClient.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $encodingType = null,
        ?string $storageClass = null,
        ?array $metadata = null,
        ?string $cacheControl = null,
        ?string $contentDisposition = null,
        ?string $contentEncoding = null,
        ?int $contentLength = null,
        ?string $contentMd5 = null,
        ?string $contentType = null,
        ?string $expires = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null,
        ?string $tagging = null,
        ?bool $forbidOverwrite = null,
        ?string $requestPayer = null,
        ?bool $disableAutoDetectMimeType = null,
        ?int $cseDataSize = null,
        ?int $csePartSize = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->encodingType = $encodingType;
        $this->storageClass = $storageClass;
        $this->metadata = $metadata;
        $this->cacheControl = $cacheControl;
        $this->contentDisposition = $contentDisposition;
        $this->contentEncoding = $contentEncoding;
        $this->contentLength = $contentLength;
        $this->contentMd5 = $contentMd5;
        $this->contentType = $contentType;
        $this->expires = $expires;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
        $this->tagging = $tagging;
        $this->forbidOverwrite = $forbidOverwrite;
        $this->requestPayer = $requestPayer;
        $this->disableAutoDetectMimeType = $disableAutoDetectMimeType;
        $this->cseDataSize = $cseDataSize;
        $this->csePartSize = $csePartSize;
        parent::__construct($options);
    }
}
