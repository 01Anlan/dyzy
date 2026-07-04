<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQuery
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MetaQuery')]
final class MetaQuery extends Model
{
    /**
     * The maximum number of objects to return. Valid values: 0 to 100. If this parameter is not set or is set to 0, up to 100 objects are returned.
     * @var int|null
     */
    #[XmlElement(rename: 'MaxResults', type: 'int')]
    public ?int $maxResults;

    /**
     * The query conditions. A query condition includes the following elements:*   Operation: the operator. Valid values: eq (equal to), gt (greater than), gte (greater than or equal to), lt (less than), lte (less than or equal to), match (fuzzy query), prefix (prefix query), and (AND), or (OR), and not (NOT).*   Field: the field name.*   Value: the field value.*   SubQueries: the subquery conditions. Options that are included in this element are the same as those of simple query. You need to set subquery conditions only when Operation is set to and, or, or not.
     * @var string|null
     */
    #[XmlElement(rename: 'Query', type: 'string')]
    public ?string $query;

    /**
     * The field based on which the results are sorted.
     * @var string|null
     */
    #[XmlElement(rename: 'Sort', type: 'string')]
    public ?string $sort;

    /**
     * The sort order.
     * Sees MetaQueryOrderType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Order', type: 'string')]
    public ?string $order;

    /**
     * The container that stores the information about aggregate operations.
     * @var MetaQueryAggregations|null
     */
    #[XmlElement(rename: 'Aggregations', type: MetaQueryAggregations::class)]
    public ?MetaQueryAggregations $aggregations;

    /**
     * The pagination token used to obtain information in the next request. The object information is returned in alphabetical order starting from the value of NextToken.
     * @var string|null
     */
    #[XmlElement(rename: 'NextToken', type: 'string')]
    public ?string $nextToken;

    /**
     * The type of multimedia that you want to query.
     * @var MetaQueryMediaTypes|null
     */
    #[XmlElement(rename: 'MediaTypes', type: MetaQueryMediaTypes::class)]
    public ?MetaQueryMediaTypes $mediaType;

    /**
     * The query conditions
     * @var string|null
     */
    #[XmlElement(rename: 'SimpleQuery', type: 'string')]
    public ?string $simpleQuery;

    /**
     * MetaQuery constructor.
     * @param int|null $maxResults The maximum number of objects to return.
     * @param string|null $query The query conditions.
     * @param string|null $sort The field based on which the results are sorted.
     * @param string|null $order The sort order.
     * @param MetaQueryAggregations|null $aggregations The container that stores the information about aggregate operations.
     * @param string|null $nextToken The pagination token used to obtain information in the next request.
     * @param MetaQueryMediaTypes|null $mediaTypes The type of multimedia that you want to query.
     * @param string|null $simpleQuery The query conditions.
     */
    public function __construct(
        ?int $maxResults = null,
        ?string $query = null,
        ?string $sort = null,
        ?string $order = null,
        ?MetaQueryAggregations $aggregations = null,
        ?string $nextToken = null,
        ?MetaQueryMediaTypes $mediaTypes = null,
        ?string $simpleQuery = null
    )
    {
        $this->maxResults = $maxResults;
        $this->query = $query;
        $this->sort = $sort;
        $this->order = $order;
        $this->aggregations = $aggregations;
        $this->nextToken = $nextToken;
        $this->mediaType = $mediaTypes;
        $this->simpleQuery = $simpleQuery;
    }
}