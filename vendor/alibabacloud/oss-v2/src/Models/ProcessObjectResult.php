<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ProcessObject operation.
 * Class ProcessObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ProcessObjectResult extends ResultModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The file size.
     * @var int|null
     */
    public ?int $fileSize;

    /**
     * The process status.
     * @var string|null
     */
    public ?string $processStatus;

    /**
     * ProcessObjectResult constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param int|null $fileSize The file size.
     * @param string|null $processStatus The process status.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?int $fileSize = null,
        ?string $processStatus = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->fileSize = $fileSize;
        $this->processStatus = $processStatus;
    }
}
