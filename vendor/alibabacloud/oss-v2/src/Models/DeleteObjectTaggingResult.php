<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the DeleteObjectTagging operation.
 * Class DeleteObjectTaggingResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteObjectTaggingResult extends ResultModel
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * DeleteObjectTaggingResult constructor.
     * @param string|null $versionId Version of the object.
     */
    public function __construct(
        ?string $versionId = null
    )
    {
        $this->versionId = $versionId;
    }
}
