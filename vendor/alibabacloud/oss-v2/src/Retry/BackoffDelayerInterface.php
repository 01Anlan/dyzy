<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;


interface BackoffDelayerInterface
{
    /**
     * Returns the delay that should be used before retrying the attempt.
     * @param int $attempt current retry attempt
     * @param \Throwable $reason the error meets
     * @return float delay duration in second.
     */    
    public function backoffDelay(int $attempt, ?\Throwable $reason): float;
}
