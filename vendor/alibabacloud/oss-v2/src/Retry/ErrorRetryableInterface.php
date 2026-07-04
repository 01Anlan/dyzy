<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;


interface ErrorRetryableInterface
{
    public function isErrorRetryable(\Throwable $reason): bool;
}
