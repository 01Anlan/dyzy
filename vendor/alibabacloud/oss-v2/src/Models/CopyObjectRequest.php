<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the CopyObject operation.
 * Class CopyObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CopyObjectRequest extends RequestModel
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
     * The access control list (ACL) of the destination object when the object is created. Default value: default.Valid values:*   default: The ACL of the object is the same as the ACL of the bucket in which the object is stored.*   private: The ACL of the object is private. Only the owner of the object and authorized users have read and write permissions on the object. Other users do not have permissions on the object.*   public-read: The ACL of the object is public-read. Only the owner of the object and authorized users have read and write permissions on the object. Other users have only read permissions on the object. Exercise caution when you set the ACL of the bucket to this value.*   public-read-write: The ACL of the object is public-read-write. All users have read and write permissions on the object. Exercise caution when you set the ACL of the bucket to this value.For more information about ACLs, see [Object ACL](~~100676~~).
     * Sees ObjectACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The storage class of the object that you want to upload. Default value: Standard. If you specify a storage class when you upload the object, the storage class applies regardless of the storage class of the bucket to which you upload the object. For example, if you set **x-oss-storage-class** to Standard when you upload an object to an IA bucket, the storage class of the uploaded object is Standard.Valid values:*   Standard*   IA*   Archive*   ColdArchiveFor more information about storage classes, see [Overview](~~51374~~).
     * Sees StorageClassType for supported values.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The metadata of the object that you want to upload.
     * @var array<string,string>|null
     */
    public ?array $metadata;

    /**
     * The caching behavior of the web page when the object is downloaded.
     * @var string|null
     */
    public ?string $cacheControl;

    /**
     * The method that is used to access the object.
     * @var string|null
     */
    public ?string $contentDisposition;

    /**
     * The method that is used to encode the object.
     * @var string|null
     */
    public ?string $contentEncoding;

    /**
     * The size of the data in the HTTP message body. Unit: bytes.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     *  The MD5 hash of the object that you want to upload.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     *  A standard MIME type describing the format of the contents.
     * @var string|null
     */
    public ?string $contentType;

    /**
     * The expiration time of the cache in UTC.
     * @var string|null
     */
    public ?string $expires;

    /**
     * The method that is used to configure the metadata of the destination object. Default value: COPY.*   **COPY**: The metadata of the source object is copied to the destination object. The **x-oss-server-side-encryption** attribute of the source object is not copied to the destination object. The **x-oss-server-side-encryption** header in the CopyObject request specifies the method that is used to encrypt the destination object.*   **REPLACE**: The metadata that you specify in the request is used as the metadata of the destination object.  If the path of the source object is the same as the path of the destination object and versioning is disabled for the bucket in which the source and destination objects are stored, the metadata that you specify in the CopyObject request is used as the metadata of the destination object regardless of the value of the x-oss-metadata-directive header.
     * @var string|null
     */
    public ?string $metadataDirective;

    /**
     * The entropy coding-based encryption algorithm that OSS uses to encrypt an object when you create the object. The valid values of the header are **AES256** and **KMS**. You must activate Key Management Service (KMS) in the OSS console before you can use the KMS encryption algorithm. Otherwise, the KmsServiceNotEnabled error is returned.*   If you do not specify the **x-oss-server-side-encryption** header in the CopyObject request, the destination object is not encrypted on the server regardless of whether the source object is encrypted on the server.*   If you specify the **x-oss-server-side-encryption** header in the CopyObject request, the destination object is encrypted on the server after the CopyObject operation is performed regardless of whether the source object is encrypted on the server. In addition, the response to a CopyObject request contains the **x-oss-server-side-encryption** header whose value is the encryption algorithm of the destination object. When the destination object is downloaded, the **x-oss-server-side-encryption** header is included in the response. The value of this header is the encryption algorithm of the destination object.
     * @var string|null
     */
    public ?string $serverSideEncryption;

    /**
     * The server side data encryption algorithm. Invalid value: SM4
     * @var string|null
     */
    public ?string $serverSideDataEncryption;

    /**
     * The ID of the customer master key (CMK) that is managed by KMS. This parameter is available only if you set **x-oss-server-side-encryption** to KMS.
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * The tag of the destination object. You can add multiple tags to the destination object. Example: TagA=A\&TagB=B.  The tag key and tag value must be URL-encoded. If a key-value pair does not contain an equal sign (=), the tag value is considered an empty string.
     * @var string|null
     */
    public ?string $tagging;

    /**
     * The method that is used to add tags to the destination object. Default value: Copy. Valid values:*   **Copy**: The tags of the source object are copied to the destination object.*   **Replace**: The tags that you specify in the request are added to the destination object.
     * @var string|null
     */
    public ?string $taggingDirective;

    /**
     * Specifies whether the CopyObject operation overwrites objects with the same name. The **x-oss-forbid-overwrite** request header does not take effect when versioning is enabled or suspended for the destination bucket. In this case, the CopyObject operation overwrites the existing object that has the same name as the destination object.*   If you do not specify the **x-oss-forbid-overwrite** header or set the header to **false**, an existing object that has the same name as the object that you want to copy is overwritten.*****   If you set the **x-oss-forbid-overwrite** header to **true**, an existing object that has the same name as the object that you want to copy is not overwritten.If you specify the **x-oss-forbid-overwrite** header, the queries per second (QPS) performance of OSS may be degraded. If you want to specify the **x-oss-forbid-overwrite** header in a large number of requests (QPS greater than 1,000), contact technical support. Default value: false.
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

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
     * Progress callback function
     * @var callable|null
     */
    public $progressFn;

    /**
     * CopyObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $sourceBucket The name of the source bucket.
     * @param string|null $sourceKey The path of the source object.
     * @param string|null $sourceVersionId The version ID of the source object.
     * @param string|null $ifMatch If the ETag specified in the request matches the ETag value of the object, the object and 200 OK are returned.
     * @param string|null $ifNoneMatch If the ETag specified in the request does not match the ETag value of the object, the object and 200 OK are returned.
     * @param string|null $ifModifiedSince If the time specified in this header is earlier than the object modified time or is invalid, the object and 200 OK are returned.
     * @param string|null $ifUnmodifiedSince If the time specified in this header is the same as or later than the object modified time, the object and 200 OK are returned.
     * @param string|null $acl The access control list (ACL) of the object.
     * @param string|null $storageClass The storage class of the object.
     * @param array|null $metadata The metadata of the object that you want to upload.
     * @param string|null $cacheControl The caching behavior of the web page when the object is downloaded.
     * @param string|null $contentDisposition The method that is used to access the object.
     * @param string|null $contentEncoding The method that is used to encode the object.
     * @param string|null $contentLength The size of the data in the HTTP message body.
     * @param string|null $contentMd5 The Content-MD5 header value is a string calculated by using the MD5 algorithm.
     * @param string|null $contentType A standard MIME type describing the format of the contents.
     * @param string|null $expires The expiration time of the cache in UTC.
     * @param string|null $metadataDirective The method that is used to configure the metadata of the destination object.
     * @param string|null $serverSideEncryption The encryption method on the server side when an object is created.
     * @param string|null $serverSideDataEncryption The server side data encryption algorithm.
     * @param string|null $serverSideEncryptionKeyId The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @param string|null $tagging The tags that are specified for the object by using a key-value pair.
     * @param string|null $taggingDirective The method that is used to configure tags for the destination object.
     * @param bool|null $forbidOverwrite Specifies whether the CopyObject operation overwrites objects with the same name.
     * @param int|null $trafficLimit Specify the speed limit value.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param callable|null $progressFn Progress callback function, it works in Copier.Copy only.
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $sourceBucket = null,
        ?string $sourceKey = null,
        ?string $sourceVersionId = null,
        ?string $ifMatch = null,
        ?string $ifNoneMatch = null,
        ?string $ifModifiedSince = null,
        ?string $ifUnmodifiedSince = null,
        ?string $acl = null,
        ?string $storageClass = null,
        ?array $metadata = null,
        ?string $cacheControl = null,
        ?string $contentDisposition = null,
        ?string $contentEncoding = null,
        ?string $contentLength = null,
        ?string $contentMd5 = null,
        ?string $contentType = null,
        ?string $expires = null,
        ?string $metadataDirective = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null,
        ?string $tagging = null,
        ?string $taggingDirective = null,
        ?bool $forbidOverwrite = null,
        ?int $trafficLimit = null,
        ?string $requestPayer = null,
        ?callable $progressFn = null,
        ?array $options = null,
        ?string $objectAcl = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->sourceBucket = $sourceBucket;
        $this->sourceKey = $sourceKey;
        $this->sourceVersionId = $sourceVersionId;
        $this->ifMatch = $ifMatch;
        $this->ifNoneMatch = $ifNoneMatch;
        $this->ifModifiedSince = $ifModifiedSince;
        $this->ifUnmodifiedSince = $ifUnmodifiedSince;
        $this->acl = $objectAcl ?? $acl;
        $this->storageClass = $storageClass;
        $this->metadata = $metadata;
        $this->cacheControl = $cacheControl;
        $this->contentDisposition = $contentDisposition;
        $this->contentEncoding = $contentEncoding;
        $this->contentLength = $contentLength;
        $this->contentMd5 = $contentMd5;
        $this->contentType = $contentType;
        $this->expires = $expires;
        $this->metadataDirective = $metadataDirective;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
        $this->tagging = $tagging;
        $this->taggingDirective = $taggingDirective;
        $this->forbidOverwrite = $forbidOverwrite;
        $this->trafficLimit = $trafficLimit;
        $this->requestPayer = $requestPayer;
        $this->progressFn = $progressFn;
        parent::__construct($options);
    }
}
