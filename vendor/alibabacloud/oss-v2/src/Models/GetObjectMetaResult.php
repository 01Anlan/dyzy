<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetObjectMeta operation.
 * Class GetObjectMetaResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetObjectMetaResult extends ResultModel
{
    /**
     *  Size of the body in bytes.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     * The entity tag (ETag). An ETag is created when an object is created to identify the content of the object.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The time when the returned objects were last modified.
     * @var \DateTime|null
     */
    public ?\DateTime $lastModified;

    /**
     * The 64-bit CRC value of the object. This value is calculated based on the ECMA-182 standard.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The time when the object was last accessed.
     * @var \DateTime|null
     */
    public ?\DateTime $lastAccessTime;

    /**
     * The time when the storage class of the object is converted to Cold Archive or Deep Cold Archive based on lifecycle rules.
     * @var \DateTime|null
     */
    public ?\DateTime $transitionTime;

    public function __construct(
        ?int $contentLength = null,
        ?string $etag = null,
        ?\DateTime $lastModified = null,
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?\DateTime $lastAccessTime = null,
        ?\DateTime $transitionTime = null
    )
    {
        $this->contentLength = $contentLength;
        $this->etag = $etag;
        $this->lastModified = $lastModified;
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->lastAccessTime = $lastAccessTime;
        $this->transitionTime = $transitionTime;
    }
}
