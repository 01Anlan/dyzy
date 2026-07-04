<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

class PutApiARequest  extends RequestModel
{
    use ModelTrait;

    #[RequiredProperty()]
    #[TagProperty(tag: 'input', position: 'host', rename: '', type: 'string')]
    private ?string $bucket;

    #[RequiredProperty()]
    #[TagProperty(tag: 'input', position: 'path', rename: '', type: 'string')]
    private ?string $key;

    #[TagProperty(tag: 'input', position: 'header', rename: 'x-oss-str', type: 'string')]
    private ?string $strHeader;

    #[TagProperty(tag: 'input', position: 'query', rename: 'param-str', type: 'string')]
    private ?string $strParam;

    #[TagProperty(tag: 'input', position: 'body', rename: '', type: 'string')]
    private ?string $configuration;

    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $strHeader = null,
        ?string $strParam = null,
        ?string $configuration = null
    ) {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->strHeader = $strHeader;
        $this->strParam = $strParam;
        $this->configuration = $configuration;
    }
}
