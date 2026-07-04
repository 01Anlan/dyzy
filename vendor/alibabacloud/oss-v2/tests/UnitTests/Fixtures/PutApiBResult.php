<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;

class PutApiBResult  extends ResultModel
{
    use ModelTrait;

    #[TagHeader(rename: 'x-oss-str', type: 'string')]
    private ?string $strHeader;

    #[TagHeader(rename: 'x-oss-int', type: 'int')]
    private ?int $intHeader;

    #[TagHeader(rename: 'x-oss-bool', type: 'bool')]
    private ?bool $boolHeader;

    #[TagHeader(rename: 'x-oss-float', type: 'float')]
    private ?float $floatHeader;

    #[XmlElement(rename: 'Id', type: 'string')]
    private ?string $id;

    #[XmlElement(rename: 'Text', type: 'string')]
    private ?string $text;

    #[XmlElement(rename: 'SubConfiguration', type: SubConfiguration::class)]
    private ?array $subConfiguration;

    public function __construct(
        ?string $strHeader = null,
        ?int $intHeader = null,
        ?bool $boolHeader = null,
        ?float $floatHeader = null,
        ?string $id = null,
        ?string $text = null,
        ?array $subConfiguration = null
    ) {
        $this->strHeader = $strHeader;
        $this->intHeader = $intHeader;
        $this->boolHeader = $boolHeader;
        $this->floatHeader = $floatHeader;
        $this->id = $id;
        $this->text = $text;
        $this->subConfiguration = $subConfiguration;
    }
}
