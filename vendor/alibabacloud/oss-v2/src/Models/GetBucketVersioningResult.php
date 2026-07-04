<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketVersioning operation.
 * Class GetBucketVersioningResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketVersioningResult extends ResultModel
{
    /**
     * The container that stores the versioning state of the bucket.
     * @var VersioningConfiguration|null
     */
    public ?VersioningConfiguration $versioningConfiguration;

    /**
     * GetBucketVersioningResult constructor.
     * @param VersioningConfiguration|null $versioningConfiguration The container that stores the versioning state of the bucket.
     */
    public function __construct(
        ?VersioningConfiguration $versioningConfiguration = null
    )
    {
        $this->versioningConfiguration = $versioningConfiguration;
    }
}
