<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAggregations
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Aggregations')]
final class MetaQueryAggregations extends Model
{
    /**
     * @var array<MetaQueryAggregation>|null
     */
    #[XmlElement(rename: 'Aggregation', type: MetaQueryAggregation::class)]
    public ?array $aggregations;

    /**
     * MetaQueryAggregations constructor.
     * @param array<MetaQueryAggregation>|null $aggregations
     */
    public function __construct(
        ?array $aggregations = null
    )
    {
        $this->aggregations = $aggregations;
    }
}