<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;


#[XmlRoot(name: 'RootConfiguration')]
class RootConfiguration  extends Model
{
    use ModelTrait;

    #[XmlElement(rename: 'Id', type: 'string')]
    private ?string $id;

    #[XmlElement(rename: 'Text', type: 'string')]
    private ?string $text;

    #[XmlElement(rename: 'SubConfiguration', type: SubConfiguration::class)]
    private ?array $subConfiguration;

    public function __construct(
        ?string $id = null,
        ?string $text = null,
        ?array $subConfiguration = null
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->subConfiguration = $subConfiguration;
    }
}
