<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorHeaders
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorHeaders')]
final class MirrorHeaders extends Model
{
    /**
     * The headers to pass through to the origin. This parameter takes effect only when the value of RedirectType is Mirror. Each specified header can be up to 1,024 bytes in length and can contain only letters, digits, and hyphens (-). You can specify up to 10 headers.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Pass', type: 'string')]
    public ?array $passs;

    /**
     * The headers that are not allowed to pass through to the origin. This parameter takes effect only when the value of RedirectType is Mirror. Each header can be up to 1,024 bytes in length and can contain only letters, digits, and hyphens (-). You can specify up to 10 headers. This parameter is used together with PassAll.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Remove', type: 'string')]
    public ?array $removes;

    /**
     * The headers that are sent to the origin. The specified headers are configured in the data returned by the origin regardless of whether the headers are contained in the request. This parameter takes effect only when the value of RedirectType is Mirror. You can specify up to 10 headers.
     * @var array<MirrorHeaderSet>|null
     */
    #[XmlElement(rename: 'Set', type: MirrorHeaderSet::class)]
    public ?array $sets;

    /**
     * Specifies whether to pass through all request headers other than the following headers to the origin. This parameter takes effect only when the value of RedirectType is Mirror.*   Headers such as content-length, authorization2, authorization, range, and date*   Headers that start with oss-, x-oss-, and x-drs-Default value: false.Valid values:*   true            *   false
     * @var bool|null
     */
    #[XmlElement(rename: 'PassAll', type: 'bool')]
    public ?bool $passAll;


    /**
     * MirrorHeaders constructor.
     * @param array<string>|null $passs The headers to pass through to the origin.
     * @param array<string>|null $removes The headers that are not allowed to pass through to the origin.
     * @param array<MirrorHeaderSet>|null $sets The headers that are sent to the origin.
     * @param bool|null $passAll Specifies whether to pass through all request headers other than the following headers to the origin.
     */
    public function __construct(
        ?array $passs = null,
        ?array $removes = null,
        ?array $sets = null,
        ?bool $passAll = null
    )
    {
        $this->passs = $passs;
        $this->removes = $removes;
        $this->sets = $sets;
        $this->passAll = $passAll;
    }
}