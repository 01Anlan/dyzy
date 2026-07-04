<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the DeleteObject operation.
 * Class DeleteObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteObjectResult extends ResultModel
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * Specifies whether the object retrieved was (true) or was not (false) a Delete Marker.
     * @var bool|null
     */
    public ?bool $deleteMarker;

    /**
     * DeleteObjectResult constructor.
     * @param string|null $versionId Version of the object.
     * @param bool|null $deleteMarker Specifies whether the object retrieved was (true) or was not (false) a Delete Marker.
     */
    public function __construct(
        ?string $versionId = null,
        ?bool $deleteMarker = null
    )
    {
        $this->versionId = $versionId;
        $this->deleteMarker = $deleteMarker;
    }
}
