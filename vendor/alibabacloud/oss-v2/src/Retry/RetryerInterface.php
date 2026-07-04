<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;


interface RetryerInterface
{
    public function isErrorRetryable(\Throwable $reason): bool;

    public function getMaxAttempts(): int;

    public function retryDelay(int $attempt, ?\Throwable $reason): float;
}
