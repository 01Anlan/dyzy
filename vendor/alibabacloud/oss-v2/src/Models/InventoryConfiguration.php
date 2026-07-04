<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InventoryConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InventoryConfiguration')]
final class InventoryConfiguration extends Model
{
    /**
     * Specifies whether to enable the bucket inventory feature. Valid values:*   true*   false
     * @var bool|null
     */
    #[XmlElement(rename: 'IsEnabled', type: 'bool')]
    public ?bool $isEnabled;

    /**
     * The container that stores the exported inventory lists.
     * @var InventoryDestination|null
     */
    #[XmlElement(rename: 'Destination', type: InventoryDestination::class)]
    public ?InventoryDestination $destination;

    /**
     * The container that stores information about the frequency at which inventory lists are exported.
     * @var InventorySchedule|null
     */
    #[XmlElement(rename: 'Schedule', type: InventorySchedule::class)]
    public ?InventorySchedule $schedule;

    /**
     * The container that stores the prefix used to filter objects. Only objects whose names contain the specified prefix are included in the inventory.
     * @var InventoryFilter|null
     */
    #[XmlElement(rename: 'Filter', type: InventoryFilter::class)]
    public ?InventoryFilter $filter;

    /**
     * Specifies whether to include the version information about the objects in inventory lists. Valid values:*   All: The information about all versions of the objects is exported.*   Current: Only the information about the current versions of the objects is exported.
     * @var string|null
     */
    #[XmlElement(rename: 'IncludedObjectVersions', type: 'string')]
    public ?string $includedObjectVersions;

    /**
     * The container that stores the configuration fields in inventory lists.
     * @var OptionalFields|null
     */
    #[XmlElement(rename: 'OptionalFields', type: OptionalFields::class)]
    public ?OptionalFields $optionalFields;

    /**
     * The name of the inventory. The name must be unique in the bucket.
     * @var string|null
     */
    #[XmlElement(rename: 'Id', type: 'string')]
    public ?string $id;

    /**
     * InventoryConfiguration constructor.
     * @param bool|null $isEnabled Specifies whether to enable the bucket inventory feature.
     * @param InventoryDestination|null $destination The container that stores the exported inventory lists.
     * @param InventorySchedule|null $schedule The container that stores information about the frequency at which inventory lists are exported.
     * @param InventoryFilter|null $filter The container that stores the prefix used to filter objects.
     * @param string|null $includedObjectVersions Specifies whether to include the version information about the objects in inventory lists.
     * @param OptionalFields|null $optionalFields The container that stores the configuration fields in inventory lists.
     * @param string|null $id The name of the inventory.
     */
    public function __construct(
        ?bool $isEnabled = null,
        ?InventoryDestination $destination = null,
        ?InventorySchedule $schedule = null,
        ?InventoryFilter $filter = null,
        ?string $includedObjectVersions = null,
        ?OptionalFields $optionalFields = null,
        ?string $id = null
    )
    {
        $this->isEnabled = $isEnabled;
        $this->destination = $destination;
        $this->schedule = $schedule;
        $this->filter = $filter;
        $this->includedObjectVersions = $includedObjectVersions;
        $this->optionalFields = $optionalFields;
        $this->id = $id;
    }
}