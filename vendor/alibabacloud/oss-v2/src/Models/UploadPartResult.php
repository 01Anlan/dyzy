<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the UploadPart operation.
 * Class UploadPartResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class UploadPartResult extends ResultModel
{
    /**
     * The MD5 hash of the part that you want to upload.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * Entity tag for the uploaded part.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The 64-bit CRC value of the part.
     * This value is calculated based on the ECMA-182 standard.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * UploadPartResult constructor.
     * @param string|null $contentMd5 The MD5 hash of the part that you want to upload.
     * @param string|null $etag Entity tag for the uploaded part.
     * @param string|null $hashCrc64 The 64-bit CRC value of the part.
     */
    public function __construct(
        ?string $contentMd5 = null,
        ?string $etag = null,
        ?string $hashCrc64 = null
    )
    {
        $this->contentMd5 = $contentMd5;
        $this->etag = $etag;
        $this->hashCrc64 = $hashCrc64;
    }
}
