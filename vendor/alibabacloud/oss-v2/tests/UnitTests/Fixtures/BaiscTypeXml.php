<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;


#[XmlRoot(name:'BasicType')]
class BaiscTypeXml  extends Model
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[XmlElement(rename:'BoolValue', type: 'bool')]
    public ?bool $boolValue;

    #[XmlElement(rename:'FloatValue', type: 'float')]
    public ?float $floatValue;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?bool $boolValue = null,
        ?float $floatValue = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->boolValue = $boolValue;
        $this->floatValue = $floatValue;
    }
}