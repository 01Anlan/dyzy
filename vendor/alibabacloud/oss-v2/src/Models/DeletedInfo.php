<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class DeletedInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Deleted')]
final class DeletedInfo extends Model
{
    /**
     * The name of the deleted object.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The version ID of the object that you deleted.
     * @var string|null
     */
    #[XmlElement(rename: 'VersionId', type: 'string')]
    public ?string $versionId;

    /**
     * Indicates whether the deleted version is a delete marker.
     * @var bool|null
     */
    #[XmlElement(rename: 'DeleteMarker', type: 'bool')]
    public ?bool $deleteMarker;

    /**
     * The version ID of the delete marker.
     * @var string|null
     */
    #[XmlElement(rename: 'DeleteMarkerVersionId', type: 'string')]
    public ?string $deleteMarkerVersionId;


    /**
     * DeleteMultipleObjectsRequest constructor.
     * @param string|null $key The name of the deleted object.
     * @param string|null $versionId The version ID of the object that you deleted.
     * @param bool|null $deleteMarker Indicates whether the deleted version is a delete marker.
     * @param string|null $deleteMarkerVersionId The version ID of the delete marker.
     */
    public function __construct(
        ?string $key = null,
        ?string $versionId = null,
        ?bool $deleteMarker = null,
        ?string $deleteMarkerVersionId = null
    ) {
        $this->key = $key;
        $this->versionId = $versionId;
        $this->deleteMarker = $deleteMarker;
        $this->deleteMarkerVersionId = $deleteMarkerVersionId;
    }
}
