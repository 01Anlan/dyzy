<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CertificateConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CertificateConfiguration')]
final class CertificateConfiguration extends Model
{
    /**
     * Specifies whether to overwrite the certificate. Valid values:- true: overwrites the certificate.- false: does not overwrite the certificate.
     * @var bool|null
     */
    #[XmlElement(rename: 'Force', type: 'bool')]
    public ?bool $force;

    /**
     * Specifies whether to delete the certificate. Valid values:- true: deletes the certificate.- false: does not delete the certificate.
     * @var bool|null
     */
    #[XmlElement(rename: 'DeleteCertificate', type: 'bool')]
    public ?bool $deleteCertificate;

    /**
     * The ID of the certificate.
     * @var string|null
     */
    #[XmlElement(rename: 'CertId', type: 'string')]
    public ?string $certId;

    /**
     * The public key of the certificate.
     * @var string|null
     */
    #[XmlElement(rename: 'Certificate', type: 'string')]
    public ?string $certificate;

    /**
     * The private key of the certificate.
     * @var string|null
     */
    #[XmlElement(rename: 'PrivateKey', type: 'string')]
    public ?string $privateKey;

    /**
     * The ID of the certificate. If the Force parameter is not set to true, the OSS server checks whether the value of the Force parameter matches the current certificate ID. If the value does not match the certificate ID, an error is returned.noticeIf you do not specify the PreviousCertId parameter when you bind a certificate, you must set the Force parameter to true./notice
     * @var string|null
     */
    #[XmlElement(rename: 'PreviousCertId', type: 'string')]
    public ?string $previousCertId;


    /**
     * CertificateConfiguration constructor.
     * @param bool|null $force Specifies whether to overwrite the certificate.
     * @param bool|null $deleteCertificate Specifies whether to delete the certificate.
     * @param string|null $certId The ID of the certificate.
     * @param string|null $certificate The public key of the certificate.
     * @param string|null $privateKey The private key of the certificate.
     * @param string|null $previousCertId The ID of the certificate.
     */
    public function __construct(
        ?bool $force = null,
        ?bool $deleteCertificate = null,
        ?string $certId = null,
        ?string $certificate = null,
        ?string $privateKey = null,
        ?string $previousCertId = null
    )
    {
        $this->force = $force;
        $this->deleteCertificate = $deleteCertificate;
        $this->certId = $certId;
        $this->certificate = $certificate;
        $this->privateKey = $privateKey;
        $this->previousCertId = $previousCertId;
    }
}