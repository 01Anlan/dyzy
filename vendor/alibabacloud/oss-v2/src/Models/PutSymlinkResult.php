<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the PutSymlink operation.
 * Class PutSymlinkResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutSymlinkResult extends ResultModel
{
    /**
     * The version id of the target object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * PutSymlinkResult constructor.
     * @param string|null $versionId The version id of the target object.
     */
    public function __construct(
        ?string $versionId = null
    )
    {
        $this->versionId = $versionId;
    }
}
