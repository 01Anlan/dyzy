<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryGroup
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Group')]
final class MetaQueryGroup extends Model
{
    /**
     * The value for the grouped aggregation.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;

    /**
     * The number of results in the grouped aggregation.
     * @var int|null
     */
    #[XmlElement(rename: 'Count', type: 'int')]
    public ?int $count;

    /**
     * MetaQueryGroup constructor.
     * @param string|null $value The value for the grouped aggregation.
     * @param int|null $count The number of results in the grouped aggregation.
     */
    public function __construct(
        ?string $value = null,
        ?int $count = null
    )
    {
        $this->value = $value;
        $this->count = $count;
    }
}