<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents an exception that is thrown when a fetch a credentials.
 */
class CredentialsException extends \RuntimeException
{
    public function __construct(
        $message,
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
