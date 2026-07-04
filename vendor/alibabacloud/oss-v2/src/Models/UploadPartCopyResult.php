<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the UploadPartCopy operation.
 * Class UploadPartCopyResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class UploadPartCopyResult extends ResultModel
{
    /**
     * The last modified time of copy source.
     * @var \DateTime|null
     */
    public ?\DateTime $lastModified;

    /**
     * The ETag of the copied part.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The version ID of the source object.
     * @var string|null
     */
    public ?string $sourceVersionId;


    /**
     * UploadPartCopyResult constructor.
     * @param \DateTime|null $lastModified The last modified time of copy source.
     * @param string|null $etag The ETag of the copied part.
     * @param string|null $sourceVersionId The version ID of the source object.
     */
    public function __construct(
        ?\DateTime $lastModified = null,
        ?string $etag = null,
        ?string $sourceVersionId = null
    )
    {
        $this->lastModified = $lastModified;
        $this->etag = $etag;
        $this->sourceVersionId = $sourceVersionId;
    }
}
