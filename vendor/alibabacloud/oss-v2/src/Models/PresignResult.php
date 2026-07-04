<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * The result for the presign operation.
 */
final class PresignResult
{
    /**
     * The HTTP method, which corresponds to the operation.
     * For example, the HTTP method of the GetObject operation is GET.
     * @var string|null 
     */
    public ?string $method;

    /**
     * The pre-signed URL.
     * @var string|null 
     */
    public ?string $url;

    /**
     * The time when the pre-signed URL expires.
     * @var \DateTime|null 
     */
    public ?\DateTime $expiration;

    /**
     * The request headers specified in the request.
     * For example, if Content-Type is specified for PutObject, Content-Type is returned.
     * @var array<string, string>|null 
     */
    public ?array $signedHeaders;

    /**
     * PresignResult constructor.
     * @param mixed $method The HTTP method, which corresponds to the operation.
     * @param mixed $url The pre-signed URL.
     * @param mixed $expiration The time when the pre-signed URL expires.
     * @param mixed $signedHeaders The request headers specified in the request.
     */
    public function __construct(
        ?string $method = null,
        ?string $url = null,
        ?\DateTime $expiration = null,
        ?array $signedHeaders = null
    ) {
        $this->method = $method;
        $this->url = $url;
        $this->expiration = $expiration;
        $this->signedHeaders = $signedHeaders;
    }
}
