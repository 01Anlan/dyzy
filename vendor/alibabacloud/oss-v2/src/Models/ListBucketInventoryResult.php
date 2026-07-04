<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the ListBucketInventory operation.
 * Class ListBucketInventoryResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListBucketInventoryResult extends ResultModel
{

    /**
     * The container that stores inventory configuration list.
     * @var ListInventoryConfigurationsResult|null
     */
    #[TagBody(rename: 'ListInventoryConfigurationsResult', type: ListInventoryConfigurationsResult::class, format: 'xml')]
    public ?ListInventoryConfigurationsResult $listInventoryConfigurationsResult;

    /**
     * ListBucketInventoryRequest constructor.
     * @param ListInventoryConfigurationsResult|null $listInventoryConfigurationsResult The container that stores inventory configuration list.
     */
    public function __construct(
        ?ListInventoryConfigurationsResult $listInventoryConfigurationsResult = null
    )
    {
        $this->listInventoryConfigurationsResult = $listInventoryConfigurationsResult;
    }
}
