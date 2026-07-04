<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetBucketInventory operation.
 * Class GetBucketInventoryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketInventoryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the inventory to be queried.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'inventoryId', type: 'string')]
    public ?string $inventoryId;

    /**
     * GetBucketInventoryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $inventoryId The name of the inventory to be queried.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $inventoryId = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->inventoryId = $inventoryId;
        parent::__construct($options);
    }
}