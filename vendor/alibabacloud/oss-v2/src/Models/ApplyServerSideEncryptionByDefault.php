<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ApplyServerSideEncryptionByDefault
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ApplyServerSideEncryptionByDefault')]
final class ApplyServerSideEncryptionByDefault extends Model
{
    /**
     * The default server-side encryption method. Valid values: KMS, AES256, and SM4. You are charged when you call API operations to encrypt or decrypt data by using CMKs managed by KMS. For more information, see [Billing of KMS](~~52608~~). If the default server-side encryption method is configured for the destination bucket and ReplicaCMKID is configured in the CRR rule:*   If objects in the source bucket are not encrypted, they are encrypted by using the default encryption method of the destination bucket after they are replicated.*   If objects in the source bucket are encrypted by using SSE-KMS or SSE-OSS, they are encrypted by using the same method after they are replicated.For more information, see [Use data replication with server-side encryption](~~177216~~).
     * @var string|null
     */
    #[XmlElement(rename: 'SSEAlgorithm', type: 'string')]
    public ?string $sseAlgorithm;

    /**
     * The CMK ID that is specified when SSEAlgorithm is set to KMS and a specified CMK is used for encryption. In other cases, leave this parameter empty.
     * @var string|null
     */
    #[XmlElement(rename: 'KMSMasterKeyID', type: 'string')]
    public ?string $kmsMasterKeyID;

    /**
     * The algorithm that is used to encrypt objects. If this parameter is not specified, objects are encrypted by using AES256. This parameter is valid only when SSEAlgorithm is set to KMS. Valid value: SM4.
     * @var string|null
     */
    #[XmlElement(rename: 'KMSDataEncryption', type: 'string')]
    public ?string $kmsDataEncryption;


    /**
     * ApplyServerSideEncryptionByDefault constructor.
     * @param string|null $sseAlgorithm The default server-side encryption method.
     * @param string|null $kmsMasterKeyID The CMK ID that is specified when SSEAlgorithm is set to KMS and a specified CMK is used for encryption.
     * @param string|null $kmsDataEncryption The algorithm that is used to encrypt objects.
     */
    public function __construct(
        ?string $sseAlgorithm = null,
        ?string $kmsMasterKeyID = null,
        ?string $kmsDataEncryption = null
    )
    {
        $this->sseAlgorithm = $sseAlgorithm;
        $this->kmsMasterKeyID = $kmsMasterKeyID;
        $this->kmsDataEncryption = $kmsDataEncryption;
    }
}