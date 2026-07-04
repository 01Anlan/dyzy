<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

/**
 * FixedDelayBackoff implements fixed backoff.
 */
final class FixedDelayBackoff implements BackoffDelayerInterface
{

    /**
     * the delay duration in second
     * @var float
     */
    private float $backoff;

    /**
     * Summary of __construct
     * @param float $backoff the delay duration in second
     */
    public function __construct(float $backoff)
    {
        $this->backoff = $backoff;
    }

    /**
     * Returns the delay that should be used before retrying the attempt.
     * @param int $attempt current retry attempt
     * @param \Throwable $reason the error meets
     * @return float delay duration in second.
     */
    public function backoffDelay(int $attempt, ?\Throwable $reason): float
    {
        return $this->backoff;
    }
}
