<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketInventory operation.
 * Class PutBucketInventoryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketInventoryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the inventory.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'inventoryId', type: 'string')]
    public ?string $inventoryId;

    /**
     * Request body schema.
     * @var InventoryConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'InventoryConfiguration', type: 'xml')]
    public ?InventoryConfiguration $inventoryConfiguration;

    /**
     * PutBucketInventoryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $inventoryId The name of the inventory.
     * @param InventoryConfiguration|null $inventoryConfiguration Request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $inventoryId = null,
        ?InventoryConfiguration $inventoryConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->inventoryId = $inventoryId;
        $this->inventoryConfiguration = $inventoryConfiguration;
        parent::__construct($options);
    }
}