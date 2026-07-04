<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InventoryEncryption
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InventoryEncryption')]
final class InventoryEncryption extends Model
{
    /**
     * The container that stores information about the SSE-OSS encryption method.
     * @var string|null
     */
    #[XmlElement(rename: 'SSE-OSS', type: 'string')]
    public ?string $sseOss;

    /**
     * The container that stores the customer master key (CMK) used for SSE-KMS encryption.
     * @var SSEKMS|null
     */
    #[XmlElement(rename: 'SSE-KMS', type: SSEKMS::class)]
    public ?SSEKMS $sseKms;

    /**
     * InventoryEncryption constructor.
     * @param string|null $sseOss The container that stores information about the SSE-OSS encryption method.
     * @param SSEKMS|null $sseKms The container that stores the customer master key (CMK) used for SSE-KMS encryption.
     */
    public function __construct(
        ?string $sseOss = null,
        ?SSEKMS $sseKms = null
    )
    {
        $this->sseOss = $sseOss;
        $this->sseKms = $sseKms;
    }
}