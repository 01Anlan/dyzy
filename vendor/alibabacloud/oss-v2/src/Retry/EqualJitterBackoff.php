<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

/**
 * FullJitterBackoff implements capped exponential backoff with jitter.
 * ceil = min(2 ^ attempts * baseDealy, maxBackoff)
 * ceil/2 + [0.0, 1.0) *(ceil/2 + 1)
 */
final class EqualJitterBackoff implements BackoffDelayerInterface
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

        $half = $delay / 2;

        $rand = mt_rand(0, 1000000) / 1000000;

        return $half + $rand * ($half + 1);
    }
}
