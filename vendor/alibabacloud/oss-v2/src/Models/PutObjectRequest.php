<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutObject operation.
 * Class PutObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutObjectRequest extends RequestModel
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
     * The access control list (ACL) of the object. Default value: default. Valid values:- default: The ACL of the object is the same as that of the bucket in which the object is stored. - private: The ACL of the object is private. Only the owner of the object and authorized users can read and write this object. - public-read: The ACL of the object is public-read. Only the owner of the object and authorized users can read and write this object. Other users can only read the object. Exercise caution when you set the object ACL to this value. - public-read-write: The ACL of the object is public-read-write. All users can read and write this object. Exercise caution when you set the object ACL to this value. For more information about the ACL, see [ACL](~~100676~~).
     * Sees ObjectACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The storage class of the object. Default value: Standard.  Valid values:- Standard- IA- Archive- ColdArchive
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
     * The MD5 hash of the object that you want to upload.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * A standard MIME type describing the format of the contents.
     * @var string|null
     */
    public ?string $contentType;

    /**
     * The expiration time of the cache in UTC.
     * @var string|null
     */
    public ?string $expires;

    /**
     * The method that is used to encrypt the object on the OSS server when the object is created. Valid values: AES256, KMS, and SM4.
     * If you specify the header, the header is returned in the response.
     * OSS uses the method that is specified by this header to encrypt the uploaded object.
     * When you download the encrypted object, the x-oss-server-side-encryption header is included in the response and the header value is set to the algorithm that is used to encrypt the object.
     * @var string|null
     */
    public ?string $serverSideEncryption;

    /**
     * Specify the encryption algorithm for the object. Valid values: SM4.
     * If this option is not specified, it indicates that the Object uses AES256 encryption algorithm.
     * This option is only valid when x-oss-ser-side-encryption is KMS.
     * @var string|null
     */
    public ?string $serverSideDataEncryption;

    /**
     * The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * This header is valid only when the x-oss-server-side-encryption header is set to KMS.
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * The tag of the object. You can configure multiple tags for the object. Example: TagA=A&TagB=B.
     * The key and value of a tag must be URL-encoded.
     * If a tag does not contain an equal sign (=), the value of the tag is considered an empty string.
     * @var string|null
     */
    public ?string $tagging;

    /**
     * A callback parameter is a Base64-encoded string that contains multiple fields in the JSON format.
     * @var string|null
     */
    public ?string $callback;

    /**
     * Configure custom parameters by using the callback-var parameter.
     * @var string|null
     */
    public ?string $callbackVar;

    /**
     * Specifies whether the object that is uploaded by calling the PutObject operation overwrites the existing object that has the same name.
     * When versioning is enabled or suspended for the bucket to which you want to upload the object, the x-oss-forbid-overwrite header does not take effect.
     * In this case, the object that is uploaded by calling the PutObject operation overwrites the existing object that has the same name.
     * - If you do not specify the **x-oss-forbid-overwrite** header or set the x-oss-forbid-overwrite header to false,
     * the object that is uploaded by calling the PutObject operation overwrites the existing object that has the same name.
     * - If the value of x-oss-forbid-overwrite is set to **true**, existing objects cannot be overwritten by objects that have the same names.
     * If you specify the x-oss-forbid-overwrite request header, the queries per second (QPS) performance of OSS is degraded.
     * If you want to use the x-oss-forbid-overwrite request header to perform a large number of operations (QPS greater than 1,000), contact technical support.
     * Default value: false.
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

    /**
     * Specify the speed limit value. The speed limit value ranges from 245760 to 838860800, with a unit of bit/s.
     * @var int|null
     */
    public ?int $trafficLimit;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * Object data.
     * @var \Psr\Http\Message\StreamInterface|null
     */
    public ?\Psr\Http\Message\StreamInterface $body;

    /**
     * Progress callback function.
     * @var callable|null
     */
    public $progressFn;

    /**
     * PutObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $acl The access control list (ACL) of the object.
     * @param string|null $storageClass The storage class of the object.
     * @param array|null $metadata The tag of the object.
     * @param string|null $cacheControl The caching behavior of the web page when the object is downloaded.
     * @param string|null $contentDisposition The method that is used to access the object.
     * @param string|null $contentEncoding The method that is used to encode the object.
     * @param string|null $contentLength The size of the data in the HTTP message body.
     * @param string|null $contentMd5 The MD5 hash of the object that you want to upload.
     * @param string|null $contentType A standard MIME type describing the format of the contents.
     * @param string|null $expires The expiration time of the cache in UTC.
     * @param string|null $serverSideEncryption The method that is used to encrypt the object on the OSS server when the object is created.
     * @param string|null $serverSideDataEncryption Specify the encryption algorithm for the object.
     * @param string|null $serverSideEncryptionKeyId The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @param string|null $tagging The tags that are specified for the object by using a key-value pair.
     * @param string|null $callback A callback parameter is a Base64-encoded string that contains multiple fields in the JSON format.
     * @param string|null $callbackVar Configure custom parameters by using the callback-var parameter.
     * @param bool|null $forbidOverwrite Specifies whether the object that is uploaded by calling the PutObject operation overwrites the existing object that has the same name.
     * @param int|null $trafficLimit Specify the speed limit value.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param \Psr\Http\Message\StreamInterface|null $body Object data.
     * @param callable|null $progressFn Progress callback function.
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
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
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null,
        ?string $tagging = null,
        ?string $callback = null,
        ?string $callbackVar = null,
        ?bool $forbidOverwrite = null,
        ?int $trafficLimit = null,
        ?string $requestPayer = null,
        ?\Psr\Http\Message\StreamInterface $body = null,
        ?callable $progressFn = null,
        ?array $options = null,
        ?string $objectAcl = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
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
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
        $this->tagging = $tagging;
        $this->callback = $callback;
        $this->callbackVar = $callbackVar;
        $this->forbidOverwrite = $forbidOverwrite;
        $this->trafficLimit = $trafficLimit;
        $this->requestPayer = $requestPayer;
        $this->body = $body;
        $this->progressFn = $progressFn;
        parent::__construct($options);
    }
}
