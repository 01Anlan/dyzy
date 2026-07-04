<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CnameCertificate
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CnameCertificate')]
final class CnameCertificate extends Model
{
    /**
     * The source of the certificate.Valid values:*   CAS            *   Upload
     * @var string|null
     */
    #[XmlElement(rename: 'Type', type: 'string')]
    public ?string $type;

    /**
     * The ID of the certificate.
     * @var string|null
     */
    #[XmlElement(rename: 'CertId', type: 'string')]
    public ?string $certId;

    /**
     * The status of the certificate.Valid values:*   Enabled            *   Disabled
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * The time when the certificate was bound.
     * @var string|null
     */
    #[XmlElement(rename: 'CreationDate', type: 'string')]
    public ?string $creationDate;

    /**
     * The signature of the certificate.
     * @var string|null
     */
    #[XmlElement(rename: 'Fingerprint', type: 'string')]
    public ?string $fingerprint;

    /**
     * The time when the certificate takes effect.
     * @var string|null
     */
    #[XmlElement(rename: 'ValidStartDate', type: 'string')]
    public ?string $validStartDate;

    /**
     * The time when the certificate expires.
     * @var string|null
     */
    #[XmlElement(rename: 'ValidEndDate', type: 'string')]
    public ?string $validEndDate;

    /**
     * CnameCertificate constructor.
     * @param string|null $type The source of the certificate.
     * @param string|null $certId The ID of the certificate.
     * @param string|null $status The status of the certificate.
     * @param string|null $creationDate The time when the certificate was bound.
     * @param string|null $fingerprint The signature of the certificate.
     * @param string|null $validStartDate The time when the certificate takes effect.
     * @param string|null $validEndDate The time when the certificate expires.
     */
    public function __construct(
        ?string $type = null,
        ?string $certId = null,
        ?string $status = null,
        ?string $creationDate = null,
        ?string $fingerprint = null,
        ?string $validStartDate = null,
        ?string $validEndDate = null
    )
    {
        $this->type = $type;
        $this->certId = $certId;
        $this->status = $status;
        $this->creationDate = $creationDate;
        $this->fingerprint = $fingerprint;
        $this->validStartDate = $validStartDate;
        $this->validEndDate = $validEndDate;
    }
}