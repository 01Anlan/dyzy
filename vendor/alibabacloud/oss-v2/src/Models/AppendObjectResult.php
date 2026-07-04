<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the AppendObject operation.
 * Class AppendObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class AppendObjectResult extends ResultModel
{
    /**
     * The position that must be provided in the next request, which is the current length of the object.
     * @var int|null
     */
    public ?int $nextPosition;

    /**
     * The ETag value that is returned by OSS after the object is uploaded.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The 64-bit CRC value of the object. This value is calculated based on the ECMA-182 standard.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

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
     * AppendObjectResult constructor.
     * @param int|null $nextPosition The position that must be provided in the next request, which is the current length of the object.
     * @param string|null $etag The ETag value that is returned by OSS after the object is uploaded.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     * @param string|null $versionId Version of the object.
     * @param string|null $serverSideEncryption The method used to encrypt objects on the specified OSS server.
     * @param string|null $serverSideDataEncryption The server side data encryption algorithm.
     * @param string|null $serverSideEncryptionKeyId The ID of the CMK that is managed by KMS.
     */
    public function __construct(
        ?int $nextPosition = null,
        ?string $etag = null,
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null
    )
    {
        $this->nextPosition = $nextPosition;
        $this->etag = $etag;
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
    }
}
