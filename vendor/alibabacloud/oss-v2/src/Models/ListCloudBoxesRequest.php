<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;

/**
 * The request for the list cloud boxes operation.
 * Class ListCloudBoxesRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListCloudBoxesRequest extends RequestModel
{
    /**
     * The name of the bucket from which the list operation begins.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'marker', type: 'string')]
    public ?string $marker;

    /**
     * The maximum number of buckets that can be returned in the single query.
     * Valid values: 1 to 1000.
     * @var int|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'max-keys', type: 'int')]
    public ?int $maxKeys;

    /**
     * The prefix that the names of returned buckets must contain.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'prefix', type: 'string')]
    public ?string $prefix;

    /**
     * ListCloudBoxesRequest constructor.
     * @param string|null $marker The name of the bucket from which the list operation begins.
     * @param int|null $maxKeys The maximum number of buckets that can be returned in the single query.
     * @param string|null $prefix The prefix that the names of returned buckets must contain.
     * @param array|null $options
     */
    public function __construct(
        ?string $marker = null,
        ?int $maxKeys = null,
        ?string $prefix = null,
        ?array $options = null
    )
    {
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->prefix = $prefix;
        parent::__construct($options);
    }
}