<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class OperationOutput
 * @package AlibabaCloud\Oss\V2
 */
final class OperationOutput
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array<string, string>
     */
    private $headers;

    /**
     * @var StreamInterface
     */
    private ?StreamInterface $body;

    /**
     * @var OperationInput
     */
    private ?OperationInput $opInput;

    /**
     * @var array<string, mixed>
     */
    private $opMetadata;

    /**
     * @var ResponseInterface
     */
    private ?ResponseInterface $httpResponse;

    public function __construct(
        ?string $status = null,
        ?int $statusCode = null,
        ?array $headers = null,
        ?StreamInterface $body = null,
        ?OperationInput $opInput = null,
        ?array $opMetadata = null,
        ?ResponseInterface $httpResponse = null
    ) {
        $this->status = $status ?? '';
        $this->statusCode = $statusCode ?? 0;
        $this->headers = $headers ?? [];
        $this->body = $body;
        $this->opInput = $opInput;
        $this->opMetadata = $opMetadata ?? [];
        $this->httpResponse = $httpResponse;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function GetStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): ?StreamInterface
    {
        return $this->body;
    }

    public function getOpInput(): ?OperationInput
    {
        return $this->opInput;
    }

    public function getOpMetadata(): array
    {
        return $this->opMetadata;
    }

    public function getHttpResponse(): ?ResponseInterface
    {
        return $this->httpResponse;
    }
}
