<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;
use AlibabaCloud\Oss\V2\Annotation\TagQuery;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

class PutApiRequest  extends RequestModel
{
    use ModelTrait;

    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: '', type: 'string')]
    private ?string $bucket;

    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'path', rename: '', type: 'string')]
    private ?string $key;

    #[TagHeader(rename: 'x-oss-str', type: 'string')]
    private ?string $strHeader;

    #[TagHeader(rename: 'x-oss-int', type: 'int')]
    private ?int $intHeader;

    #[TagHeader(rename: 'x-oss-bool', type: 'bool')]
    private ?bool $boolHeader;

    #[TagHeader(rename: 'x-oss-float', type: 'float')]
    private ?float $floatHeader;

    #[TagHeader(rename: 'x-oss-isotime', type: 'Datetime')]
    private ?\Datetime $isotimeHeader;

    #[TagHeader(rename: 'x-oss-httptime', type: 'Datetime', format:'httptime')]
    private ?\Datetime $httptimeHeader;

    #[TagHeader(rename: 'x-oss-unixtime', type: 'Datetime', format:'unixtime')]
    private ?\Datetime $unixtimeHeader;

    #[TagQuery(rename: 'param-str', type: 'string')]
    private ?string $strParam;

    #[TagQuery(rename: 'param-int', type: 'int')]
    private ?int $intParam;

    #[TagQuery(rename: 'param-bool', type: 'bool')]
    private ?bool $boolParam;

    #[TagQuery(rename: 'param-float', type: 'float')]
    private ?float $floatParam;

    #[TagQuery(rename: 'param-isotime', type: 'Datetime')]
    private ?\Datetime $isotimeParam;

    #[TagQuery(rename: 'param-httptime', type: 'Datetime', format:'httptime')]
    private ?\Datetime $httptimeParam;

    #[TagQuery(rename: 'param-unixtime', type: 'Datetime', format:'unixtime')]
    private ?\Datetime $unixtimeParam;

    #[TagBody(rename: 'Configuration', type: 'xml')]
    private ?RootConfiguration $configuration;

    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $strHeader = null,
        ?int $intHeader = null,
        ?bool $boolHeader = null,
        ?float $floatHeader = null,
        ?\Datetime $isotimeHeader = null,
        ?\Datetime $httptimeHeader = null,
        ?\Datetime $unixtimeHeader = null,
        ?string $strParam = null,
        ?int $intParam = null,
        ?bool $boolParam = null,
        ?float $floatParam = null,
        ?\Datetime $isotimeParam = null,
        ?\Datetime $httptimeParam = null,
        ?\Datetime $unixtimeParam = null,
        ?RootConfiguration $configuration = null
    ) {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->strHeader = $strHeader;
        $this->intHeader = $intHeader;
        $this->boolHeader = $boolHeader;
        $this->floatHeader = $floatHeader;
        $this->isotimeHeader = $isotimeHeader;
        $this->httptimeHeader = $httptimeHeader;
        $this->unixtimeHeader = $unixtimeHeader;
        $this->strParam = $strParam;
        $this->intParam = $intParam;
        $this->boolParam = $boolParam;
        $this->floatParam = $floatParam;
        $this->isotimeParam = $isotimeParam;
        $this->httptimeParam = $httptimeParam;
        $this->unixtimeParam = $unixtimeParam;
        $this->configuration = $configuration;
    }
}
