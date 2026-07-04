<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
/**
 * The result for the GetObjectTagging operation.
 * Class GetObjectTaggingResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetObjectTaggingResult extends ResultModel
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The container that stores the returned tag of the bucket.
     * @var TagSet|null
     */
    public ?TagSet $tagSet;

    public function __construct(
        ?string $versionId = null,
        ?TagSet $tagSet = null
    ) {
        $this->versionId = $versionId;
        $this->tagSet = $tagSet;
    }
}
