<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleRuleFilter
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Filter')]
final class LifecycleRuleFilter extends Model
{
    /**
     * The condition that is matched by objects to which the lifecycle rule does not apply.
     * @var array<LifecycleRuleNot>|null
     */
    #[XmlElement(rename: 'Not', type: LifecycleRuleNot::class)]
    public ?array $nots;

    /**
     * This lifecycle rule only applies to files larger than this size.
     * @var int|null
     */
    #[XmlElement(rename: 'ObjectSizeGreaterThan', type: 'int')]
    public ?int $objectSizeGreaterThan;

    /**
     * This lifecycle rule only applies to files smaller than this size.
     * @var int|null
     */
    #[XmlElement(rename: 'ObjectSizeLessThan', type: 'int')]
    public ?int $objectSizeLessThan;


    /**
     * LifecycleRuleFilter constructor.
     * @param array<LifecycleRuleNot>|null $nots The condition that is matched by objects to which the lifecycle rule does not apply.
     * @param int|null $objectSizeGreaterThan This lifecycle rule only applies to files larger than this size.
     * @param int|null $objectSizeLessThan This lifecycle rule only applies to files smaller than this size.
     */
    public function __construct(
        ?array $nots = null,
        ?int   $objectSizeGreaterThan = null,
        ?int   $objectSizeLessThan = null
    )
    {
        $this->nots = $nots;
        $this->objectSizeGreaterThan = $objectSizeGreaterThan;
        $this->objectSizeLessThan = $objectSizeLessThan;
    }
}