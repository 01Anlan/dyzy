<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRuleCondition
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RoutingRuleCondition')]
final class RoutingRuleCondition extends Model
{
    /**
     * The prefix of object names. Only objects whose names contain the specified prefix match the rule.
     * @var string|null
     */
    #[XmlElement(rename: 'KeyPrefixEquals', type: 'string')]
    public ?string $keyPrefixEquals;

    /**
     * Only objects that match this suffix can match this rule.
     * @var string|null
     */
    #[XmlElement(rename: 'KeySuffixEquals', type: 'string')]
    public ?string $keySuffixEquals;

    /**
     * The HTTP status code. The rule is matched only when the specified object is accessed and the specified HTTP status code is returned. If the redirection rule is the mirroring-based back-to-origin rule, the value of this parameter is 404.
     * @var int|null
     */
    #[XmlElement(rename: 'HttpErrorCodeReturnedEquals', type: 'int')]
    public ?int $httpErrorCodeReturnedEquals;

    /**
     * This rule can only be matched if the request contains the specified header and the value is the specified value.
     * This container can specify up to 10.
     * @var array<RoutingRuleIncludeHeader>|null
     */
    #[XmlElement(rename: 'IncludeHeader', type: RoutingRuleIncludeHeader::class)]
    public ?array $includeHeaders;

    /**
     * RoutingRuleCondition constructor.
     * @param string|null $keyPrefixEquals The prefix of object names.
     * @param string|null $keySuffixEquals Only objects that match this suffix can match this rule.
     * @param int|null $httpErrorCodeReturnedEquals The HTTP status code.
     * @param array<RoutingRuleIncludeHeader>|null $includeHeaders This rule can only be matched if the request contains the specified header and the value is the specified value.
     */
    public function __construct(
        ?string $keyPrefixEquals = null,
        ?string $keySuffixEquals = null,
        ?int $httpErrorCodeReturnedEquals = null,
        ?array $includeHeaders = null
    )
    {
        $this->keyPrefixEquals = $keyPrefixEquals;
        $this->keySuffixEquals = $keySuffixEquals;
        $this->httpErrorCodeReturnedEquals = $httpErrorCodeReturnedEquals;
        $this->includeHeaders = $includeHeaders;
    }
}