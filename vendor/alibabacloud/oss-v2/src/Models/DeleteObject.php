<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class DeleteObject
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Object')]
final class DeleteObject extends Model
{
    /**
     * The name of the object that you want to delete.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The version ID of the object that you want to delete.
     * @var string|null
     */
    #[XmlElement(rename: 'VersionId', type: 'string')]
    public ?string $versionId;

    /**
     * DeleteMultipleObjectsRequest constructor.
     * @param string|null $key The name of the object that you want to delete.
     * @param string|null $versionId The version ID of the object that you want to delete.
     */
    public function __construct(
        ?string $key = null,
        ?string $versionId = null
    ) {
        $this->key = $key;
        $this->versionId = $versionId;
    }
}
