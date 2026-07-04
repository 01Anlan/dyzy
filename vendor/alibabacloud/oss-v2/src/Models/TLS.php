<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class TLS
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'TLS')]
final class TLS extends Model
{
    /**
     * The TLS versions.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'TLSVersion', type: 'string')]
    public ?array $tlsVersions;
    /**
     * Specifies whether to enable TLS version management for the bucket.Valid values:*   true            *   false
     * @var bool|null
     */
    #[XmlElement(rename: 'Enable', type: 'bool')]
    public ?bool $enable;

    /**
     * TLS constructor.
     * @param array<string>|null $tlsVersions The TLS versions.
     * @param bool|null $enable Specifies whether to enable TLS version management for the bucket.
     */
    public function __construct(
        ?array $tlsVersions = null,
        ?bool $enable = null
    )
    {
        $this->tlsVersions = $tlsVersions;
        $this->enable = $enable;
    }
}