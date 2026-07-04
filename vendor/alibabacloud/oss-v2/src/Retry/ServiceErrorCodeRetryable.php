<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

use AlibabaCloud\Oss\V2\Exception;

final class ServiceErrorCodeRetryable implements ErrorRetryableInterface
{
    private static $errorCodes = [
        "RequestTimeTooSkewed",
        "BadRequest"
    ];

    public function isErrorRetryable(\Throwable $reason): bool
    {
        if ($reason instanceof Exception\ServiceException) {
            $code = $reason->getErrorCode();
            if (in_array($code, self::$errorCodes)) {
                return true;
            }
        }
        return false;
    }
}
