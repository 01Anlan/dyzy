<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Exception;

/**
 * Represents a exception that is thrown when checking crc.
 */
class InconsistentExecption extends \RuntimeException
{
    public function __construct(
        string $crc1,
        string $crc2,
        ?\Psr\Http\Message\ResponseInterface $response = null
    ) {
        $requestId = '';
        if ($response != null) {
            if ($response->hasHeader('x-oss-request-id')) {
                $requestId = $response->getHeader('x-oss-request-id')[0];
            }
        }
        parent::__construct("crc is inconsistent, client crc: $crc1, server crc: $crc2, request id: $requestId");
    }
}
