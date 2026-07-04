<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the RestoreObject operation.
 * Class RestoreObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class RestoreObjectResult extends ResultModel
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The restoration priority.
     * This header is displayed only for the Cold Archive or Deep Cold Archive object in the restored state.
     * @var string|null
     */
    public ?string $restorePriority;

    /**
     * RestoreObjectResult constructor.
     * @param string|null $versionId Version of the object.
     * @param string|null $restorePriority The restoration priority.
     */
    public function __construct(
        ?string $versionId = null,
        ?string $restorePriority = null
    )
    {
        $this->versionId = $versionId;
        $this->restorePriority = $restorePriority;
    }
}
