<?php

namespace UnitTests\Annotation;

use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

#[XmlRoot(name:'XmlRoot')]
class XmlObject
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[XmlElement(rename:'BoolValue', type: 'bool')]
    public ?bool $boolValue;

    #[XmlElement(rename:'FloatValue', type: 'float')]
    public ?float $floatValue;

    #[XmlElement(rename:'StrValueList', type: 'string')]
    public ?array $strValueLists;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?bool $boolValue = null,
        ?float $floatValue = null,
        ?array $strValueLists = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->boolValue = $boolValue;
        $this->floatValue = $floatValue;
        $this->strValueLists = $strValueLists;
    }
}
