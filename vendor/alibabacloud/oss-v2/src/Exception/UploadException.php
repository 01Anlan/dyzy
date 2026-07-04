<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when uploading stream fail.
 */
class UploadException extends \RuntimeException
{
    private string $uploadId;

    private string $filepath;


    public function __construct(
        string $uploadId,
        string $filepath,
        ?\Throwable $previous = null
    ) {
        $this->uploadId = $uploadId;
        $this->filepath = $filepath;

        $message = "upload failed, $uploadId, $filepath";
        if ($previous !== null) {
            $message = $message . ', ' . $previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }

    public function getUploadId()
    {
        return $this->uploadId;
    }

    public function getFilepath()
    {
        return $this->filepath;
    }
}
