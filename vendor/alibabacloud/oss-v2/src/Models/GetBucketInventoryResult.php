<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketInventory operation.
 * Class GetBucketInventoryResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketInventoryResult extends ResultModel
{
    /**
     * The inventory task configured for a bucket.
     * @var InventoryConfiguration|null
     */
    #[TagBody(rename: 'InventoryConfiguration', type: InventoryConfiguration::class, format: 'xml')]
    public ?InventoryConfiguration $inventoryConfiguration;

    /**
     * GetBucketInventoryRequest constructor.
     * @param InventoryConfiguration|null $inventoryConfiguration The inventory task configured for a bucket.
     */
    public function __construct(
        ?InventoryConfiguration $inventoryConfiguration = null
    )
    {
        $this->inventoryConfiguration = $inventoryConfiguration;
    }
}
