<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the DeleteBucketInventory operation.
 * Class DeleteBucketInventoryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteBucketInventoryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the inventory that you want to delete.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'inventoryId', type: 'string')]
    public ?string $inventoryId;

    /**
     * DeleteBucketInventoryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $inventoryId The name of the inventory that you want to delete.
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