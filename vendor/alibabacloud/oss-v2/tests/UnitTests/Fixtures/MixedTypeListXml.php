<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

#[XmlRoot(name:'MixedTypeList')]
class MixedTypeListXml  extends Model
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[XmlElement(rename:'BasicTypeFiled', type: BaiscTypeXml::class)]
    public ?array $xmlValues;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?array $xmlValues = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->xmlValues = $xmlValues;
    }
}