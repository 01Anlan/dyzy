<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

use AlibabaCloud\Oss\V2\Exception;

final class HTTPStatusCodeRetryable implements ErrorRetryableInterface
{
    private static $statusCode = [401, 408, 429];

    public function isErrorRetryable(\Throwable $reason): bool
    {
        if ($reason instanceof Exception\ServiceException) {
            $code = $reason->getStatusCode();
            if ($code >= 500) {
                return true;
            }

            if (in_array($code, self::$statusCode)) {
                return true;
            }
        }
        return false;
    }
}
