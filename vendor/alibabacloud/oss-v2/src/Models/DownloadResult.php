<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the downloader's downloadFile[Async] operation.
 */
final class DownloadResult extends ResultModel
{
    /**
     * How much data is downloaded
     * @var int|null
     */
    public ?int $written;

    /**
     * DownloadResult constructor.
     * @param int|null $written How much data is downloaded.
     */
    public function __construct(
        ?int $written = null
    ) {
        $this->written = $written;
    }
}
