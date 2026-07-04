<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RequestPaymentConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RequestPaymentConfiguration')]
final class RequestPaymentConfiguration extends Model
{
    /**
     * Indicates who pays the download and request fees.
     * @var string|null
     */
    #[XmlElement(rename: 'Payer', type: 'string')]
    public ?string $payer;

    /**
     * RequestPaymentConfiguration constructor.
     * @param string|null $payer Indicates who pays the download and request fees.
     */
    public function __construct(
        ?string $payer = null
    )
    {
        $this->payer = $payer;
    }
}