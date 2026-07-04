<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\ProgressStream;
use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the AppendObject operation.
 * Class AppendObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class AppendObjectRequest extends RequestModel
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
     * The position from which the AppendObject operation starts. Each time an AppendObject operation succeeds, the x-oss-next-append-position header is included in the response to specify the position from which the next AppendObject operation starts. The value of position in the first AppendObject operation performed on an object must be 0. The value of position in subsequent AppendObject operations performed on the object is the current length of the object. For example, if the value of position specified in the first AppendObject request is 0 and the value of content-length is 65536, the value of position in the second AppendObject request must be 65536. - If the value of position in the AppendObject request is 0 and the name of the object that you want to append is unique, you can set headers such as x-oss-server-side-encryption in an AppendObject request in the same way as you set in a PutObject request. If you add the x-oss-server-side-encryption header to an AppendObject request, the x-oss-server-side-encryption header is included in the response to the request. If you want to modify metadata, you can call the CopyObject operation. - If you call an AppendObject operation to append a 0 KB object whose position value is valid to an Appendable object, the status of the Appendable object is not changed.
     * @var int|null
     */
    public ?int $position;

    /**
     * The access control list (ACL) of the object. Default value: default.  Valid values:- default: The ACL of the object is the same as that of the bucket in which the object is stored. - private: The ACL of the object is private. Only the owner of the object and authorized users can read and write this object. - public-read: The ACL of the object is public-read. Only the owner of the object and authorized users can read and write this object. Other users can only read the object. Exercise caution when you set the object ACL to this value. - public-read-write: The ACL of the object is public-read-write. All users can read and write this object. Exercise caution when you set the object ACL to this value. For more information about the ACL, see [ACL](~~100676~~).
     * Sees ObjectACLType for supported values.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The storage class of the object that you want to upload. Valid values:- Standard- IA- ArchiveIf you specify the object storage class when you upload an object, the storage class of the uploaded object is the specified value regardless of the storage class of the bucket to which the object is uploaded. If you set x-oss-storage-class to Standard when you upload an object to an IA bucket, the object is stored as a Standard object. For more information about storage classes, see the "Overview" topic in Developer Guide. notice The value that you specify takes effect only when you call the AppendObject operation on an object for the first time.
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
     * The web page caching behavior for the object. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $cacheControl;

    /**
     * The name of the object when the object is downloaded. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $contentDisposition;

    /**
     * The encoding format of the object content. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $contentEncoding;

    /**
     * The size of the data in the HTTP message body.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     * The Content-MD5 header value is a string calculated by using the MD5 algorithm. The header is used to check whether the content of the received message is the same as that of the sent message. To obtain the value of the Content-MD5 header, calculate a 128-bit number based on the message content except for the header, and then encode the number in Base64. Default value: null.Limits: none.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * The content type of an HTTP request.
     * @var string|null
     */
    public ?string $contentType;

    /**
     * The expiration time. For more information, see **[RFC 2616](https://www.ietf.org/rfc/rfc2616.txt)**. Default value: null.
     * @var string|null
     */
    public ?string $expires;

    /**
     * The method used to encrypt objects on the specified OSS server. Valid values:- AES256: Keys managed by OSS are used for encryption and decryption (SSE-OSS). - KMS: Keys managed by Key Management Service (KMS) are used for encryption and decryption. - SM4: The SM4 block cipher algorithm is used for encryption and decryption.
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
     * The ID of the CMK that is managed by KMS. This header is valid only when **x-oss-server-side-encryption** is set to KMS.
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * The tags that are specified for the object by using a key-value pair.
     * You can specify multiple tags for an object. Example: TagA=A&TagB=B.
     * @var string|null
     */
    public ?string $tagging;

    /**
     * Specifies whether the AppendObject operation overwrites objects with the same name. The x-oss-forbid-overwrite request header does not take effect when versioning is enabled or suspended for the destination bucket. In this case, the AppendObject operation overwrites the existing object that has the same name as the destination object.
     * If you do not specify the x-oss-forbid-overwrite header or set the header to false, an existing object that has the same name as the object that you want to copy is overwritten.
     * If you set the x-oss-forbid-overwrite header to true, an existing object that has the same name as the object that you want to copy is not overwritten.
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

    /**
     * Specify the speed limit value. The speed limit value ranges from  245760 to 838860800, with a unit of bit/s.
     * @var int|null
     */
    public ?int $trafficLimit;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * The request body.
     * @var \Psr\Http\Message\StreamInterface|null
     */
    public ?\Psr\Http\Message\StreamInterface $body;

    /**
     * Progress callback function
     * @var callable|null
     */
    public $progressFn;

    /**
     * AppendObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param int|null $position The position from which the AppendObject operation starts.
     * @param string|null $acl The access control list (ACL) of the object.
     * @param string|null $storageClass The storage class of the object that you want to upload.
     * @param array|null $metadata The metadata of the object that you want to upload.
     * @param string|null $cacheControl The web page caching behavior for the object.
     * @param string|null $contentDisposition The name of the object when the object is downloaded.
     * @param string|null $contentEncoding The encoding format of the object content.
     * @param string|null $contentLength The content length of an HTTP request.
     * @param string|null $contentMd5 The Content-MD5 header value is a string calculated by using the MD5 algorithm.
     * @param string|null $contentType The content type of an HTTP request.
     * @param string|null $expires The expiration time.
     * @param string|null $serverSideEncryption The method used to encrypt objects on the specified OSS server.
     * @param string|null $serverSideDataEncryption Specify the encryption algorithm for the object.
     * @param string|null $serverSideEncryptionKeyId The ID of the CMK that is managed by KMS.
     * @param string|null $tagging The tags that are specified for the object by using a key-value pair.
     * @param bool|null $forbidOverwrite Specifies whether the AppendObject operation overwrites objects with the same name.
     * @param int|null $trafficLimit Specify the speed limit value.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param \Psr\Http\Message\StreamInterface|null $body The request body.
     * @param callable|null $progressFn Progress callback function
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?int $position = null,
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
        $this->position = $position;
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
        $this->forbidOverwrite = $forbidOverwrite;
        $this->trafficLimit = $trafficLimit;
        $this->requestPayer = $requestPayer;
        $this->body = $body;
        $this->progressFn = $progressFn;
        parent::__construct($options);
    }
}
