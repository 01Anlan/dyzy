<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CnameInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CnameInfo')]
final class CnameInfo extends Model
{
    /**
     * The custom domain name.
     * @var string|null
     */
    #[XmlElement(rename: 'Domain', type: 'string')]
    public ?string $domain;

    /**
     * The time when the custom domain name was mapped.
     * @var string|null
     */
    #[XmlElement(rename: 'LastModified', type: 'string')]
    public ?string $lastModified;

    /**
     * The status of the domain name. Valid values:*   Enabled*   Disabled
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * The container in which the certificate information is stored.
     * @var CnameCertificate|null
     */
    #[XmlElement(rename: 'Certificate', type: CnameCertificate::class)]
    public ?CnameCertificate $certificate;

    /**
     * CnameInfo constructor.
     * @param string|null $domain The custom domain name.
     * @param string|null $lastModified The time when the custom domain name was mapped.
     * @param string|null $status The status of the domain name.
     * @param CnameCertificate|null $certificate The container in which the certificate information is stored.
     */
    public function __construct(
        ?string $domain = null,
        ?string $lastModified = null,
        ?string $status = null,
        ?CnameCertificate $certificate = null
    )
    {
        $this->domain = $domain;
        $this->lastModified = $lastModified;
        $this->status = $status;
        $this->certificate = $certificate;
    }
}