<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

class NopRetryer implements RetryerInterface
{
    public function isErrorRetryable(\Throwable $reason): bool
    {
        return false;
    }

    public function getMaxAttempts(): int
    {
        return 1;
    }

    public function retryDelay(int $attempt, ?\Throwable $reason): float
    {
        throw new \Exception("NotImplemented");
    }
}
