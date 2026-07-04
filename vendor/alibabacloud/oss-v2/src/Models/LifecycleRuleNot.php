<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleRuleNot
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Not')]
final class LifecycleRuleNot extends Model
{
    /**
     * The tag of the objects to which the lifecycle rule does not apply.
     * @var Tag|null
     */
    #[XmlElement(rename: 'Tag', type: Tag::class)]
    public ?Tag $tag;

    /**
     * The prefix in the names of the objects to which the lifecycle rule does not apply.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;


    /**
     * LifecycleRuleNot constructor.
     * @param Tag|null $tag The tag of the objects to which the lifecycle rule does not apply.
     * @param string|null $prefix The prefix in the names of the objects to which the lifecycle rule does not apply.
     */
    public function __construct(
        ?Tag $tag = null,
        ?string $prefix = null
    )
    {
        $this->tag = $tag;
        $this->prefix = $prefix;
    }
}