<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

/**
 * FullJitterBackoff implements capped exponential backoff with jitter.
 * [0.0, 1.0) * min(2 ^ attempts * baseDealy, maxBackoff)
 */
final class FullJitterBackoff implements BackoffDelayerInterface
{

    /**
     *  the base delay duration in second
     * @var float
     */
    private float $baseDelay;

    /**
     * the max duration in second
     * @var float
     */
    private float $maxBackOff;

    private int $attemptCelling;

    public function __construct(float $baseDelay, float $maxBackOff)
    {
        $this->baseDelay = $baseDelay;
        $this->maxBackOff = $maxBackOff;
        $value = log(PHP_FLOAT_MAX / $baseDelay, 2);
        if (is_finite($value)) {
            $intValue = (int)$value;
        } else {
            $intValue = 64;
        }
        $this->attemptCelling = $intValue;
    }

    public function backoffDelay(int $attempt, ?\Throwable $reason): float
    {
        $attempt = min($attempt, $this->attemptCelling);

        $delay = min(2 ** $attempt * $this->baseDelay, $this->maxBackOff);

        $rand = mt_rand(0, 1000000) / 1000000;

        return $delay * $rand;
    }
}
