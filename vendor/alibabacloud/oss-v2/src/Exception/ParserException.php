<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when parsing xml/json.
 */

class ParserException extends \RuntimeException
{
    public function __construct($message = '', $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}