<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Credentials;

/**
 *Provides a helper wrapping a function value to satisfy the CredentialsProvider interface.
 */
class CredentialsProviderFunc implements CredentialsProvider
{
    private $handler;

    /**
     * @param callable(): Credentials $handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function getCredentials(): Credentials
    {
        return ($this->handler)();
    }
}
