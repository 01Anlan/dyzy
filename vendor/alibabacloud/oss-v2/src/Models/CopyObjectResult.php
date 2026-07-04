<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the CopyObject operation.
 * Class CopyObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CopyObjectResult extends ResultModel
{
    /**
     * The 64-bit CRC value of the object.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The version ID of the source object.
     * @var string|null
     */
    public ?string $sourceVersionId;

    /**
     * The time when the destination object was last modified.
     * @var \DateTime|null
     */
    public ?\DateTime $lastModified;

    /**
     * The entity tag (ETag). An ETag is created when an object is created to identify the content of the object.
     * @var string|null
     */
    public ?string $etag;


    /**
     * The method used to encrypt objects on the specified OSS server. Valid values:- AES256: Keys managed by OSS are used for encryption and decryption (SSE-OSS). - KMS: Keys managed by Key Management Service (KMS) are used for encryption and decryption. - SM4: The SM4 block cipher algorithm is used for encryption and decryption.
     * @var string|null
     */
    public ?string $serverSideEncryption;

    /**
     * The server side data encryption algorithm.
     * @var string|null
     */
    public ?string $serverSideDataEncryption;

    /**
     * The ID of the CMK that is managed by KMS. This header is valid only when **x-oss-server-side-encryption** is set to KMS.
     * @var string|null
     */
    public ?string $serverSideEncryptionKeyId;

    /**
     * CopyObjectResult constructor.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     * @param string|null $versionId Version of the object.
     * @param string|null $sourceVersionId The version ID of the source object.
     * @param \DateTime|null $lastModified The time when the returned objects were last modified.
     * @param string|null $etag The entity tag (ETag). An ETag is created when an object is created to identify the content of the object.
     * @param string|null $serverSideEncryption The method used to encrypt objects on the specified OSS server.
     * @param string|null $serverSideDataEncryption The server side data encryption algorithm.
     * @param string|null $serverSideEncryptionKeyId The ID of the CMK that is managed by KMS.
     */
    public function __construct(
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?string $sourceVersionId = null,
        ?\DateTime $lastModified = null,
        ?string $etag = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null
    )
    {
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->sourceVersionId = $sourceVersionId;
        $this->lastModified = $lastModified;
        $this->etag = $etag;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
    }
}
