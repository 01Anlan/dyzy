<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when uploading stream fail.
 */
class DownloadException extends \RuntimeException
{
    private string $filepath;


    public function __construct(
        string $filepath,
        \Throwable $previous = null
    ) {
        $this->filepath = $filepath;

        $message = "download failed, $filepath";
        if ($previous !== null) {
            $message = $message . ', ' . $previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }

    public function getFilepath()
    {
        return $this->filepath;
    }
}
