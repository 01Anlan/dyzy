<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRuleLuaConfig
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RoutingRuleLuaConfig')]
final class RoutingRuleLuaConfig extends Model
{   
    /**
     * The name of the Lua script.
     * @var string|null
     */
    #[XmlElement(rename: 'Script', type: 'string')]
    public ?string $script;

    /**
     * RoutingRuleLuaConfig constructor.
     * @param string|null $script The name of the Lua script.
     */
    public function __construct(
        ?string $script = null
    )
    {   
        $this->script = $script;
    }
}