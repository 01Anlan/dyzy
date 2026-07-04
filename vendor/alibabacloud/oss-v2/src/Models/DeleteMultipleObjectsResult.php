<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the DeleteMultipleObjects operation.
 * Class DeleteMultipleObjectsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteMultipleObjectsResult extends ResultModel
{
    /**
     * The container that stores information about the deleted objects.
     * @var array<DeletedInfo>|null 
     */
    public ?array $deletedObjects;

    /**
     * The encoding type of the name of the deleted object in the response.
     * If encoding-type is specified in the request, the object name is encoded in the returned result.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * DeleteMultipleObjectsResult constructor.
     * @param array<DeletedInfo>|null $deletedObjects The container that stores information about the deleted objects.
     * @param string|null $encodingType The encoding type of the object names in the response. Valid value: url
     */
    public function __construct(
        ?array $deletedObjects = null,
        ?string $encodingType = null
    ) {
        $this->deletedObjects = $deletedObjects;
        $this->encodingType = $encodingType;
    }
}
