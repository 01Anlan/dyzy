<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRules
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RoutingRules')]
final class RoutingRules extends Model
{
    /**
     * The specified redirection rule or mirroring-based back-to-origin rule. You can specify up to 20 rules.
     * @var array<RoutingRule>|null
     */
    #[XmlElement(rename: 'RoutingRule', type: RoutingRule::class)]
    public ?array $routingRules;

    /**
     * RoutingRules constructor.
     * @param array<RoutingRule>|null $routingRules The specified redirection rule or mirroring-based back-to-origin rule.
     */
    public function __construct(
        ?array $routingRules = null
    )
    {
        $this->routingRules = $routingRules;
    }
}