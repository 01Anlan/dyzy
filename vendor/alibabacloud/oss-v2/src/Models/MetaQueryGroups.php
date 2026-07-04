<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryGroups
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Groups')]
final class MetaQueryGroups extends Model
{
    /**
     * The grouped aggregations.
     * @var array<MetaQueryGroup>|null
     */
    #[XmlElement(rename: 'Group', type: MetaQueryGroup::class)]
    public ?array $groups;

    /**
     * MetaQueryGroups constructor.
     * @param array<MetaQueryGroup>|null $groups The grouped aggregations.
     */
    public function __construct(
        ?array $groups = null
    )
    {
        $this->groups = $groups;
    }
}