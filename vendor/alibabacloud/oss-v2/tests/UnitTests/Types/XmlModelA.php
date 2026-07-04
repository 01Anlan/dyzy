<?php

namespace UnitTests\Types;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;


#[XmlRoot(name:'XmlRootA')]
class XmlModelA extends Model
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[XmlElement(rename:'BoolValue', type: 'bool')]
    public ?bool $boolValue;

    #[XmlElement(rename:'FloatValue', type: 'float')]
    public ?float $floatValue;

    #[XmlElement(rename:'StrValueList', type: '[]string')]
    public ?array $strValueList;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?bool $boolValue = null,
        ?float $floatValue = null,
        ?array $strValueList = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->boolValue = $boolValue;
        $this->floatValue = $floatValue;
        $this->strValueList = $strValueList;

        #print(get_defined_vars());
    }
}
