<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Types;

use AlibabaCloud\Oss\V2\Types\Model;

class RequestModel extends Model
{
    /**
     * @var array<string, string>
     */
    protected $headers;

    /**
     * @var array<string, string>
     */
    protected $parameters;

    /**
     * @var mixed
     */
    protected $payload;

    public function __construct(?array $option)
    {
        if (\is_array($option)) {
            $this->headers = $option['headers'] ?? null;
            $this->parameters = $option['parameters'] ?? null;
            $this->payload = $option['payload'] ?? null;
        }
    }
}
