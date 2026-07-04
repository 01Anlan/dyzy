<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetSymlink operation.
 * Class GetSymlinkResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetSymlinkResult extends ResultModel
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * Indicates the target object that the symbol link directs to.
     * @var string|null
     */
    public ?string $target;

    /**
     * Entity tag for the uploaded object.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The metadata of the object that you want to symlink.
     * @var array<string,string>|null
     */
    public ?array $metadata;

    public function __construct(
        ?string $versionId = null,
        ?string $target = null,
        ?string $etag = null,
        ?array $metadata = null
    )
    {
        $this->versionId = $versionId;
        $this->target = $target;
        $this->etag = $etag;
        $this->metadata = $metadata;
    }
}
