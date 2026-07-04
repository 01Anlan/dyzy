<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class HttpsConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'HttpsConfiguration')]
final class HttpsConfiguration extends Model
{
    /**
     * The container that stores TLS version configurations.
     * @var TLS|null
     */
    #[XmlElement(rename: 'TLS', type: TLS::class)]
    public ?TLS $tls;

    /**
     * @var CipherSuite|null
     */
    #[XmlElement(rename: 'CipherSuite', type: CipherSuite::class)]
    public ?CipherSuite $cipherSuite;

    /**
     * HttpsConfiguration constructor.
     * @param TLS|null $tls The container that stores TLS version configurations.
     * @param CipherSuite|null $cipherSuite
     */
    public function __construct(
        ?TLS $tls = null,
        ?CipherSuite $cipherSuite = null
    )
    {
        $this->tls = $tls;
        $this->cipherSuite = $cipherSuite;
    }
}