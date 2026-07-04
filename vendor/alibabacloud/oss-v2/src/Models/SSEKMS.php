<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class SSEKMS
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'SSEKMS')]
final class SSEKMS extends Model
{
    /**
     * The ID of the key that is managed by Key Management Service (KMS).
     * @var string|null
     */
    #[XmlElement(rename: 'KeyId', type: 'string')]
    public ?string $keyId;

    /**
     * SSEKMS constructor.
     * @param string|null $keyId The ID of the key that is managed by Key Management Service (KMS).
     */
    public function __construct(
        ?string $keyId = null
    )
    {
        $this->keyId = $keyId;
    }
}