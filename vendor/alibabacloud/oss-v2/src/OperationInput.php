<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use Psr\Http\Message\StreamInterface;

/**
 * Class OperationInput
 * @package AlibabaCloud\Oss\V2
 */
final class OperationInput
{
    /**
     * @var string
     */
    private $opName;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array<string, string>
     */
    private $headers;

    /**
     * @var array<string, string>
     */
    private $parameters;

    /**
     * @var StreamInterface|null
     */
    private $body;

    /**
     * @var string|null
     */
    private $bucket;

    /**
     * @var string|null 
     */
    private $key;

    /**
     * @var array<string, mixed>
     */
    private $opMetadata;

    public function __construct(
        string $opName,
        string $method,
        ?array $headers = null,
        ?array $parameters = null,
        ?StreamInterface $body = null,
        ?string $bucket = null,
        ?string $key = null,
        ?array $opMetadata = null
    ) {
        $this->opName = $opName;
        $this->method = $method;
        $this->headers = [];
        if (\is_array($headers)) {
            foreach ($headers as $k => $v) {
                $this->headers[strtolower($k)] = (string) $v;
            }
        }
        $this->parameters = $parameters ?? [];
        $this->body = $body;
        $this->bucket = $bucket;
        $this->key = $key;
        $this->opMetadata = $opMetadata ?? [];
    }

    public function getOpName(): string
    {
        return $this->opName;
    }

    public function setOpName(string $opName): void
    {
        $this->opName = $opName;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function hasHeader(string $name): bool
    {
        return \array_key_exists(strtolower($name), $this->headers);
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[strtolower($name)] = $value;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasParameter(string $name): bool
    {
        return \array_key_exists($name, $this->parameters);
    }

    public function setParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getBody(): ?StreamInterface
    {
        return $this->body;
    }

    public function setBody(StreamInterface $body): void
    {
        $this->body = $body;
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function setBucket(string $bucket): void
    {
        $this->bucket = $bucket;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function hasOpMetadata(string $name): bool
    {
        return \array_key_exists($name, $this->opMetadata);
    }

    public function setOpMetadata(string $name, $value): void
    {
        $this->opMetadata[$name] = $value;
    }

    public function getOpMetadata(): array
    {
        return $this->opMetadata;
    }
}
