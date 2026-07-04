<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRuleIncludeHeader
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'IncludeHeader')]
final class RoutingRuleIncludeHeader extends Model
{
    /**
     * The key of the header.
     * The rule is matched only when the specified header is included in the request and the header value equals the value specified by Equals.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The value of the header.
     * The rule is matched only when the header specified by Key is included in the request and the header value equals the specified value.
     * @var string|null
     */
    #[XmlElement(rename: 'Equals', type: 'string')]
    public ?string $equals;

    /**
     * @var string|null
     */
    #[XmlElement(rename: 'StartsWith', type: 'string')]
    public ?string $startsWith;

    /**
     * @var string|null
     */
    #[XmlElement(rename: 'EndsWith', type: 'string')]
    public ?string $endsWith;


    /**
     * RoutingRuleIncludeHeader constructor.
     * @param string|null $key The key of the header.
     * @param string|null $equals The value of the header.
     * @param string|null $startsWith
     * @param string|null $endsWith
     */
    public function __construct(
        ?string $key = null,
        ?string $equals = null,
        ?string $startsWith = null,
        ?string $endsWith = null
    )
    {
        $this->key = $key;
        $this->equals = $equals;
        $this->startsWith = $startsWith;
        $this->endsWith = $endsWith;
    }
}