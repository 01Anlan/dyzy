<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAddress
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Address')]
final class MetaQueryAddress extends Model
{
    /**
     * The full address.
     * @var string|null
     */
    #[XmlElement(rename: 'AddressLine', type: 'string')]
    public ?string $addressLine;

    /**
     * The city.
     * @var string|null
     */
    #[XmlElement(rename: 'City', type: 'string')]
    public ?string $city;

    /**
     * The district.
     * @var string|null
     */
    #[XmlElement(rename: 'District', type: 'string')]
    public ?string $district;

    /**
     * The language of the address. The value follows the BCP 47 format.
     * @var string|null
     */
    #[XmlElement(rename: 'Language', type: 'string')]
    public ?string $language;

    /**
     * The province.
     * @var string|null
     */
    #[XmlElement(rename: 'Province', type: 'string')]
    public ?string $province;

    /**
     * The street.
     * @var string|null
     */
    #[XmlElement(rename: 'Township', type: 'string')]
    public ?string $township;

    /**
     * The country.
     * @var string|null
     */
    #[XmlElement(rename: 'Country', type: 'string')]
    public ?string $country;

    /**
     * MetaQueryAddress constructor.
     * @param string|null $addressLine The full address.
     * @param string|null $city The city.
     * @param string|null $district The district.
     * @param string|null $language The language of the address.
     * @param string|null $province The province.
     * @param string|null $township The street.
     * @param string|null $country The country.
     */
    public function __construct(
        ?string $addressLine = null,
        ?string $city = null,
        ?string $district = null,
        ?string $language = null,
        ?string $province = null,
        ?string $township = null,
        ?string $country = null
    )
    {
        $this->addressLine = $addressLine;
        $this->city = $city;
        $this->district = $district;
        $this->language = $language;
        $this->province = $province;
        $this->township = $township;
        $this->country = $country;
    }
}