<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents an error is encountered during serialization.
 */
class SerializationExecption extends \RuntimeException
{
    public function __construct(string $messge)
    {
        parent::__construct("Serialization raised an exception: $messge");
    }
}
