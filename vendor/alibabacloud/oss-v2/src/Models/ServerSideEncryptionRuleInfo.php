<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ServerSideEncryptionRuleInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ServerSideEncryptionRule')]
final class ServerSideEncryptionRuleInfo extends Model
{
    /**
     * The key that is managed by Key Management Service (KMS).
     * @var string|null
     */
    #[XmlElement(rename: 'KMSMasterKeyID', type: 'string')]
    public ?string $kmsMasterKeyId;

    /**
     * The default server-side encryption method.Valid values: KMS, AES-256, and SM4.
     * @var string|null
     */
    #[XmlElement(rename: 'SSEAlgorithm', type: 'string')]
    public ?string $sseAlgorithm;

    /**
     * The algorithm that is used to encrypt objects. If you do not configure this parameter, objects are encrypted by using AES-256. This parameter is valid only when SSEAlgorithm is set to KMS.Valid value: SM4.
     * @var string|null
     */
    #[XmlElement(rename: 'KMSDataEncryption', type: 'string')]
    public ?string $kmsDataEncryption;

    /**
     * ServerSideEncryptionRuleInfo constructor.
     * @param string|null $kmsMasterKeyId The key that is managed by Key Management Service (KMS).
     * @param string|null $sseAlgorithm The default server-side encryption method.
     * @param string|null $kmsDataEncryption The algorithm that is used to encrypt objects.
     */
    public function __construct(
        ?string $kmsMasterKeyId = null,
        ?string $sseAlgorithm = null,
        ?string $kmsDataEncryption = null
    )
    {
        $this->kmsMasterKeyId = $kmsMasterKeyId;
        $this->sseAlgorithm = $sseAlgorithm;
        $this->kmsDataEncryption = $kmsDataEncryption;
    }
}
