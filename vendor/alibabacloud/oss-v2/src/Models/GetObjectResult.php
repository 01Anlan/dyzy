<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetObject operation.
 * Class GetObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetObjectResult extends ResultModel
{
    /**
     * Size of the body in bytes. -1 indicates that the Content-Length does not exist.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     * The portion of the object returned in the response.
     * @var string|null
     */
    public ?string $contentRange;

    /**
     * A standard MIME type describing the format of the object data.
     * @var string|null
     */
    public ?string $contentType;

    /**
     * The entity tag (ETag). An ETag is created when an object is created to identify the content of the object.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The time when the returned objects were last modified.
     * @var \DateTime|null
     */
    public ?\DateTime $lastModified;

    /**
     * Content-Md5 for the uploaded object.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * A map of metadata to store with the object.
     * @var array|null
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
     * The expiration time of the cache in UTC.
     * @var string|null
     */
    public ?string $expires;

    /**
     * The 64-bit CRC value of the object.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * The storage class of the object.
     * @var string|null
     */
    public ?string $storageClass;

    /**
     * The type of the object.
     * @var string|null
     */
    public ?string $objectType;

    /**
     *  Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The number of tags added to the object.
     * This header is included in the response only when you have read permissions on tags.
     * @var int|null
     */
    public ?int $taggingCount;

    /**
     * If the requested object is encrypted by using a server-side encryption algorithm based on entropy encoding,
     * OSS automatically decrypts the object and returns the decrypted object after OSS receives the GetObject request.
     * The x-oss-server-side-encryption header is included in the response to indicate
     * the encryption algorithm used to encrypt the object on the server.
     * @var string|null
     */
    public ?string $serverSideEncryption;

    /**
     * The server side data encryption algorithm.
     * @var string|null
     */
    public ?string $serverSideDataEncryption;

    /**
     * The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * The position for the next append operation.
     * If the type of the object is Appendable, this header is included in the response.
     * @var int|null
     */
    public ?int $nextAppendPosition;

    /**
     * The lifecycle information about the object.
     * If lifecycle rules are configured for the object, this header is included in the response.
     * This header contains the following parameters: expiry-date that indicates the expiration time of the object,
     * and rule-id that indicates the ID of the matched lifecycle rule.
     * @var string|null
     */
    public ?string $expiration;

    /**
     * The status of the object when you restore an object.
     *  If the storage class of the bucket is Archive and a RestoreObject request is submitted.
     * @var string|null
     */
    public ?string $restore;

    /**
     * The result of an event notification that is triggered for the object.
     * @var string|null
     */
    public ?string $processStatus;

    /**
     * Specifies whether the object retrieved was (true) or was not (false) a Delete  Marker.
     * @var bool|null
     */
    public ?bool $deleteMarker;

    /**
     * Object data.
     * @var \Psr\Http\Message\StreamInterface|null
     */
    public ?\Psr\Http\Message\StreamInterface $body;

    /**
     * GetObjectResult constructor.
     * @param int|null $contentLength Size of the body in bytes.
     * @param string|null $contentRange The portion of the object returned in the response.
     * @param string|null $contentType A standard MIME type describing the format of the object data.
     * @param string|null $etag The entity tag (ETag).
     * @param \DateTime|null $lastModified The time when the returned objects were last modified.
     * @param string|null $contentMd5 Content-Md5 for the uploaded object.
     * @param array|null $metadata A map of metadata to store with the object.
     * @param string|null $cacheControl The caching behavior of the web page when the object is downloaded.
     * @param string|null $contentDisposition The method that is used to access the object.
     * @param string|null $contentEncoding The method that is used to encode the object.
     * @param string|null $expires The expiration time of the cache in UTC.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     * @param string|null $storageClass The storage class of the object.
     * @param string|null $objectType The type of the object.
     * @param string|null $versionId Version of the object.
     * @param int|null $taggingCount The number of tags added to the object.
     * @param string|null $serverSideEncryption If the requested object is encrypted by using a server-side encryption algorithm based on entropy encoding.
     * @param string|null $serverSideDataEncryption The server side data encryption algorithm.
     * @param string|null $serverSideEncryptionKeyId The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @param int|null $nextAppendPosition The position for the next append operation.
     * @param string|null $expiration The lifecycle information about the object.
     * @param string|null $restore The status of the object when you restore an object.
     * @param string|null $processStatus The result of an event notification that is triggered for the object.
     * @param bool|null $deleteMarker Specifies whether the object retrieved was (true) or was not (false) a Delete Marker.
     * @param \Psr\Http\Message\StreamInterface|null $body Object data.
     */
    public function __construct(
        ?int $contentLength = null,
        ?string $contentRange = null,
        ?string $contentType = null,
        ?string $etag = null,
        ?\DateTime $lastModified = null,
        ?string $contentMd5 = null,
        ?array $metadata = null,
        ?string $cacheControl = null,
        ?string $contentDisposition = null,
        ?string $contentEncoding = null,
        ?string $expires = null,
        ?string $hashCrc64 = null,
        ?string $storageClass = null,
        ?string $objectType = null,
        ?string $versionId = null,
        ?int $taggingCount = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null,
        ?int $nextAppendPosition = null,
        ?string $expiration = null,
        ?string $restore = null,
        ?string $processStatus = null,
        ?bool $deleteMarker = null,
        ?\Psr\Http\Message\StreamInterface $body = null
    )
    {
        $this->contentLength = $contentLength;
        $this->contentRange = $contentRange;
        $this->contentType = $contentType;
        $this->etag = $etag;
        $this->lastModified = $lastModified;
        $this->contentMd5 = $contentMd5;
        $this->metadata = $metadata;
        $this->cacheControl = $cacheControl;
        $this->contentDisposition = $contentDisposition;
        $this->contentEncoding = $contentEncoding;
        $this->expires = $expires;
        $this->storageClass = $storageClass;
        $this->hashCrc64 = $hashCrc64;
        $this->objectType = $objectType;
        $this->versionId = $versionId;
        $this->taggingCount = $taggingCount;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
        $this->nextAppendPosition = $nextAppendPosition;
        $this->expiration = $expiration;
        $this->restore = $restore;
        $this->processStatus = $processStatus;
        $this->deleteMarker = $deleteMarker;
        $this->body = $body;
    }
}
