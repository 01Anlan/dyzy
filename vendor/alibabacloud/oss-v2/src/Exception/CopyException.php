<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when copy object fail.
 */
class CopyException extends \RuntimeException
{
    private string $uploadId;

    private string $path;


    public function __construct(
        string $uploadId,
        string $path,
        ?\Throwable $previous = null
    ) {
        $this->uploadId = $uploadId;
        $this->path = $path;

        $message = "copy failed, $uploadId, $path";
        if ($previous !== null) {
            $message = $message . ', ' . $previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }

    public function getUploadId()
    {
        return $this->uploadId;
    }

    public function getPath()
    {
        return $this->path;
    }
}
