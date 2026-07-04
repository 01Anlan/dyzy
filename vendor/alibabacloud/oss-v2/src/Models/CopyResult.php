<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the copier's copy[Async] operation.
 */
final class CopyResult extends ResultModel
{
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
     * The upload ID that uniquely identifies the multipart upload task.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * Callback result, it is valid only when the callback is set.
     * @var array|null
     */
    public ?array $callbackResult;


    /**
     * CopyResult constructor.
     * @param string|null $uploadId The upload ID that uniquely identifies the multipart upload task.
     * @param string|null $etag Entity tag for the uploaded part.
     * @param string|null $hashCrc64 The 64-bit CRC value of the part.
     * @param string|null $versionId Version of the object.
     * @param array|null  $callbackResult Callback result.
     */

    public function __construct(
        ?string $uploadId = null,
        ?string $etag = null,
        ?string $hashCrc64 = null,
        ?string $versionId = null,
        ?array $callbackResult = null
    ) {
        $this->uploadId = $uploadId;
        $this->etag = $etag;
        $this->hashCrc64 = $hashCrc64;
        $this->versionId = $versionId;
        $this->callbackResult = $callbackResult;
    }
}
