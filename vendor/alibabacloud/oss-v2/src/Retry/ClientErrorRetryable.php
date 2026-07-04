<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Retry;

use AlibabaCloud\Oss\V2\Exception;
use GuzzleHttp;

final class ClientErrorRetryable implements ErrorRetryableInterface
{
    public function isErrorRetryable(\Throwable $reason): bool
    {
        // sdk Exception
        if ($reason instanceof Exception\CredentialsException) {
            return true;
        }

        if ($reason instanceof Exception\InconsistentExecption) {
            return true;
        }

        // GuzzleHttp Exception
        // a bad response exception,
        if ($reason instanceof GuzzleHttp\Exception\BadResponseException) {
            return true;
        }

        // a connection exception
        if ($reason instanceof GuzzleHttp\Exception\ConnectException) {
            return true;
        }

        // request exceptions, ex.a connection reset by peer could have occurred,
        if ($reason instanceof GuzzleHttp\Exception\RequestException) {
            return true;
        }

        return false;
    }
}
