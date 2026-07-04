<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAggregation
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Aggregation')]
final class MetaQueryAggregation extends Model
{
    /**
     * The field name.
     * @var string|null
     */
    #[XmlElement(rename: 'Field', type: 'string')]
    public ?string $field;

    /**
     * The operator for aggregate operations.
     * @var string|null
     */
    #[XmlElement(rename: 'Operation', type: 'string')]
    public ?string $operation;

    /**
     * The result of the aggregate operation.
     * @var int|null
     */
    #[XmlElement(rename: 'Value', type: 'int')]
    public ?int $value;

    /**
     * The grouped aggregations.
     * @var MetaQueryGroups|null
     */
    #[XmlElement(rename: 'Groups', type: MetaQueryGroups::class)]
    public ?MetaQueryGroups $groups;

    /**
     * MetaQueryAggregation constructor.
     * @param string|null $field The field name.
     * @param string|null $operation The operator for aggregate operations.
     * @param int|null $value The result of the aggregate operation.
     * @param MetaQueryGroups|null $groups The result of the aggregate operation.
     */
    public function __construct(
        ?string $field = null,
        ?string $operation = null,
        ?int $value = null,
        ?MetaQueryGroups $groups = null
    )
    {
        $this->field = $field;
        $this->operation = $operation;
        $this->value = $value;
        $this->groups = $groups;
    }
}