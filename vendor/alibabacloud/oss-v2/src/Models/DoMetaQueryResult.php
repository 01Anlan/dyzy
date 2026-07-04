<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * The result for the DoMetaQuery operation.
 * Class DoMetaQueryResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DoMetaQueryResult extends ResultModel
{
    /**
     * The list of file information.
     * @var MetaQueryAggregations|null
     */
    #[XmlElement(rename: 'Aggregations', type: MetaQueryAggregations::class)]
    public ?MetaQueryAggregations $aggregations;

    /**
     * The token that is used for the next query when the total number of objects exceeds the value of MaxResults.The value of NextToken is used to return the unreturned results in the next query.This parameter has a value only when not all objects are returned.
     * @var string|null
     */
    #[XmlElement(rename: 'NextToken', type: 'string')]
    public ?string $nextToken;

    /**
     * The list of file information.
     * @var MetaQueryFiles|null
     */
    #[XmlElement(rename: 'Files', type: MetaQueryFiles::class)]
    public ?MetaQueryFiles $files;

    /**
     * DoMetaQueryRequest constructor.
     * @param MetaQueryAggregations|null $aggregations The list of file information.
     * @param string|null $nextToken The token that is used for the next query when the total number of objects exceeds the value of MaxResults.
     * @param MetaQueryFiles|null $files The list of file information.
     */
    public function __construct(
        ?MetaQueryAggregations $aggregations = null,
        ?string $nextToken = null,
        ?MetaQueryFiles $files = null
    )
    {
        $this->aggregations = $aggregations;
        $this->nextToken = $nextToken;
        $this->files = $files;
    }
}
