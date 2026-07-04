<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;


#[XmlRoot(name:'BasicTypeList')]
class BaiscTypeListXml extends Model
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?array $strValues;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?array $intValues;

    #[XmlElement(rename:'BoolValue', type: 'bool')]
    public ?array $boolValues;

    #[XmlElement(rename:'FloatValue', type: 'float')]
    public ?array $floatValues;

    public function __construct(
        ?array $strValues = null,
        ?array $intValues = null,
        ?array $boolValues = null,
        ?array $floatValues = null
    ) {
        $this->strValues = $strValues;
        $this->intValues = $intValues;
        $this->boolValues = $boolValues;
        $this->floatValues = $floatValues;
    }
}