<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RoutingRule')]
final class RoutingRule extends Model
{
    /**
     * The operation to perform after the rule is matched.  This parameter must be specified if RoutingRule is specified.
     * @var RoutingRuleRedirect|null
     */
    #[XmlElement(rename: 'Redirect', type: RoutingRuleRedirect::class)]
    public ?RoutingRuleRedirect $redirect;

    /**
     * The sequence number that is used to match and run the redirection rules. OSS matches redirection rules based on this parameter. If a match succeeds, only the rule is run and the subsequent rules are not run.  This parameter must be specified if RoutingRule is specified.
     * @var int|null
     */
    #[XmlElement(rename: 'RuleNumber', type: 'int')]
    public ?int $ruleNumber;

    /**
     * The matching condition. If all of the specified conditions are met, the rule is run. A rule is considered matched only when the rule meets the conditions that are specified by all nodes in Condition.  This parameter must be specified if RoutingRule is specified.
     * @var RoutingRuleCondition|null
     */
    #[XmlElement(rename: 'Condition', type: RoutingRuleCondition::class)]
    public ?RoutingRuleCondition $condition;

    /**
     * The Lua script config of this rule.
     * @var RoutingRuleLuaConfig|null
     */
    #[XmlElement(rename: 'LuaConfig', type: RoutingRuleLuaConfig::class)]
    public ?RoutingRuleLuaConfig $luaConfig;

    /**
     * RoutingRule constructor.
     * @param RoutingRuleRedirect|null $redirect The operation to perform after the rule is matched.
     * @param int|null $ruleNumber The sequence number that is used to match and run the redirection rules.
     * @param RoutingRuleCondition|null $condition The matching condition.
     */
    public function __construct(
        ?RoutingRuleRedirect $redirect = null,
        ?int $ruleNumber = null,
        ?RoutingRuleCondition $condition = null,
        ?RoutingRuleLuaConfig $luaConfig = null
    )
    {
        $this->redirect = $redirect;
        $this->ruleNumber = $ruleNumber;
        $this->condition = $condition;
        $this->luaConfig = $luaConfig;
    }
}