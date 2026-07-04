<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when a method fails.
 */
class OperationException extends \RuntimeException
{
    public function __construct(
        string $name,
        ?\Throwable $previous = null
    ) {
        $message = "Operation error $name";
        if ($previous !== null) {
            $message = $message . ': ' . $previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }
}
