<?php

namespace UnitTests\Fixtures;

use AlibabaCloud\Oss\V2\Types\ModelTrait;
use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

class PutApiResult  extends ResultModel
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

    #[TagHeader(rename: 'x-oss-isotime', type: 'DateTime')]
    private ?\Datetime $isotimeHeader;

    #[TagHeader(rename: 'x-oss-httptime', type: 'DateTime', format: 'httptime')]
    private ?\Datetime $httptimeHeader;

    #[TagHeader(rename: 'x-oss-unixtime', type: 'DateTime', format: 'unixtime')]
    private ?\Datetime $unixtimeHeader;

    #[TagHeader(rename: 'x-oss-prefix-', type: 'string', format: 'usermeta')]
    private ?array $arrayHeader;

    #[TagBody(rename: 'Configuration', type: RootConfiguration::class, format: 'xml')]
    private ?RootConfiguration $configuration;

    public function __construct(
        ?string $strHeader = null,
        ?int $intHeader = null,
        ?bool $boolHeader = null,
        ?float $floatHeader = null,
        ?\Datetime $isotimeHeader = null,
        ?\Datetime $httptimeHeader = null,
        ?\Datetime $unixtimeHeader = null,
        ?array $arrayHeader = null,
        ?RootConfiguration $configuration = null
    ) {
        $this->strHeader = $strHeader;
        $this->intHeader = $intHeader;
        $this->boolHeader = $boolHeader;
        $this->floatHeader = $floatHeader;
        $this->isotimeHeader = $isotimeHeader;
        $this->httptimeHeader = $httptimeHeader;
        $this->unixtimeHeader = $unixtimeHeader;
        $this->arrayHeader = $arrayHeader;
        $this->configuration = $configuration;
    }
}
