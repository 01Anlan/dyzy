<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class SseKmsEncryptedObjects
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'SseKmsEncryptedObjects')]
final class SseKmsEncryptedObjects extends Model
{
    /**
     * Specifies whether to replicate objects that are encrypted by using SSE-KMS. Valid values:*   Enabled*   Disabled
     * Sees StatusType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * SseKmsEncryptedObjects constructor.
     * @param string|null $status Specifies whether to replicate objects that are encrypted by using SSE-KMS.
     */
    public function __construct(
        ?string $status = null
    )
    {
        $this->status = $status;
    }
}