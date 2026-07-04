<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

use AlibabaCloud\Oss\V2\Defaults;

/**
 * standard retryer
 */
class StandardRetryer implements RetryerInterface
{
    private int $maxAttempts;

    private float $maxBackoff;

    private float $baseDelay;

    private array $errorRetryables;

    private BackoffDelayerInterface $backoffDelayer;


    public function __construct(
        ?int $maxAttempts = null,
        ?float $maxBackoff = null,
        ?float $baseDelay = null,
        ?array $errorRetryables = null,
        ?BackoffDelayerInterface $backoffDelayer = null
    ) {
        $this->maxAttempts = $maxAttempts ?? Defaults::MAX_ATTEMPTS;
        $this->maxBackoff = $maxBackoff ?? Defaults::MAX_BACKOFF_S;
        $this->baseDelay = $baseDelay ?? Defaults::BASE_DELAY_S;
        $this->errorRetryables = $errorRetryables ?? [
            new HTTPStatusCodeRetryable(),
            new ServiceErrorCodeRetryable(),
            new ClientErrorRetryable(),
        ];
        $this->backoffDelayer = $backoffDelayer ??
            new FullJitterBackoff($this->baseDelay, $this->maxBackoff);
    }


    public function isErrorRetryable(\Throwable $reason): bool
    {
        foreach ($this->errorRetryables as $h) {
            if ($h->isErrorRetryable($reason)) {
                return true;
            }
        }
        return false;
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function retryDelay(int $attempt, ?\Throwable $reason): float
    {
        return $this->backoffDelayer->backoffDelay($attempt, $reason);
    }
}
