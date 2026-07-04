<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ListInventoryConfigurationsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ListInventoryConfigurationsResult')]
final class ListInventoryConfigurationsResult extends Model
{
    /**
     * The container that stores inventory configurations.
     * @var array<InventoryConfiguration>|null
     */
    #[XmlElement(rename: 'InventoryConfiguration', type: InventoryConfiguration::class)]
    public ?array $inventoryConfigurations;

    /**
     * Specifies whether to list all inventory tasks configured for the bucket.Valid values: true and false- The value of false indicates that all inventory tasks configured for the bucket are listed.- The value of true indicates that not all inventory tasks configured for the bucket are listed. To list the next page of inventory configurations, set the continuation-token parameter in the next request to the value of the NextContinuationToken header in the response to the current request.
     * @var bool|null
     */
    #[XmlElement(rename: 'IsTruncated', type: 'bool')]
    public ?bool $isTruncated;

    /**
     * If the value of IsTruncated in the response is true and value of this header is not null, set the continuation-token parameter in the next request to the value of this header.
     * @var string|null
     */
    #[XmlElement(rename: 'NextContinuationToken', type: 'string')]
    public ?string $nextContinuationToken;

    /**
     * ListInventoryConfigurationsResult constructor.
     * @param array<InventoryConfiguration>|null $inventoryConfigurations The container that stores inventory configurations.
     * @param bool|null $isTruncated Specifies whether to list all inventory tasks configured for the bucket.
     * @param string|null $nextContinuationToken If the value of IsTruncated in the response is true and value of this header is not null, set the continuation-token parameter in the next request to the value of this header.
     */
    public function __construct(
        ?array $inventoryConfigurations = null,
        ?bool $isTruncated = null,
        ?string $nextContinuationToken = null
    )
    {
        $this->inventoryConfigurations = $inventoryConfigurations;
        $this->isTruncated = $isTruncated;
        $this->nextContinuationToken = $nextContinuationToken;
    }
}