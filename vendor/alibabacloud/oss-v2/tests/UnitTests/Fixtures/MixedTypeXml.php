<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;
use UnitTests\Fixtures\BaiscTypeXml;
use UnitTests\Fixtures\BaiscTypeListXml;

#[XmlRoot(name:'MixedType')]
class MixedTypeXml  extends Model
{
    #[XmlElement(rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[XmlElement(rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[XmlElement(rename:'BasicTypeFiled', type: BaiscTypeXml::class)]
    public ?BaiscTypeXml $xmlValue;


    #[XmlElement(rename:'BasicTypeListFiled', type: BaiscTypeListXml::class)]
    public ?BaiscTypeListXml $xmlListValue;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?BaiscTypeXml $xmlValue = null,
        ?BaiscTypeListXml $xmlListValue = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->xmlValue = $xmlValue;
        $this->xmlListValue = $xmlListValue;
    }
}