<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the PutObject operation.
 * Class PutObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutObjectResult extends ResultModel
{

    /**
     * Content-Md5 for the uploaded object.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * Entity tag for the uploaded object.
     * @var string|null
     */
    public ?string $etag;

    /**
     * The 64-bit CRC value of the object.
     * @var string|null
     */
    public ?string $hashCrc64;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * The callback result.
     * @var array|null
     */
    public ?array $callbackResult;

    /**
     * PutObjectResult constructor.
     * @param string|null $contentMd5 Content-Md5 for the uploaded object.
     * @param string|null $etag Entity tag for the uploaded object.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     * @param string|null $versionId Version of the object.
     * @param array|null $callbackResult The callback result.
     */
    public function __construct(
        ?string $contentMd5 = null,
        ?string $etag = null,
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?array $callbackResult = null
    )
    {
        $this->contentMd5 = $contentMd5;
        $this->etag = $etag;
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->callbackResult = $callbackResult;
    }
}
