<?php

namespace UnitTests\Annotation;

use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

class ModelObject
{
    #[RequiredProperty()]
    #[TagProperty(tag: 'input', position: 'nop', rename:'StrValue', type: 'string')]
    public ?string $strValue;

    #[TagProperty(tag: 'input', position: 'nop', rename:'IntValue', type: 'int')]
    public ?int $intValue;

    #[TagProperty(tag: 'input', position: 'nop', rename:'BoolValue', type: 'bool')]
    public ?bool $boolValue;

    #[TagProperty(tag: 'input', position: 'nop', rename:'FloatValue', type: 'float')]
    public ?float $floatValue;

    #[TagProperty(tag: 'input', position: 'nop', rename:'StrValueList', type: 'string')]
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
