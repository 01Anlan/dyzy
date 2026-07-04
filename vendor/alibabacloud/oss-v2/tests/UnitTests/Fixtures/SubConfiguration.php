<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;


#[XmlRoot(name: 'SubConfiguration')]
class SubConfiguration  extends Model
{
    use ModelTrait;

    #[XmlElement(rename: 'StrField', type: 'string')]
    private ?string $strField;

    #[XmlElement(rename: 'IntField', type: 'int')]
    private ?int $intField;

    public function __construct(
        ?string $strField = null,
        ?int $intField = null
    ) {
        $this->strField = $strField;
        $this->intField = $intField;
    }
}
