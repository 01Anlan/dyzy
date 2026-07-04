<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Credentials;

/**
 * Holds the credentials needed to authenticate requests.
 */
final class Credentials
{
    /**
     * @var string The access key id of the credentials.
     */
    private string $accessKeyId;

    /**
     * @var string The access key secret of the credentials.
     */
    private string $accessKeySecret;

    /**
     * @var string|null The security token of the credentials.    
     */
    private ?string $securityToken;

    /**
     * @var \DateTimeInterface|null The token's expiration time in utc.
     */
    private ?\DateTimeInterface $expiration;

    public function __construct(
        string $accessKeyId,
        string $accessKeySecret,
        ?string $securityToken = null,
        ?\DateTimeInterface $expiration = null
    ) {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->securityToken = $securityToken;
        $this->expiration  = $expiration;
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->accessKeyId;
    }

    /**
     * @return string
     */
    public function getAccessKeySecret()
    {
        return $this->accessKeySecret;
    }

    /**
     * @return string|null
     */
    public function getSecurityToken(): ?string
    {
        return $this->securityToken;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiration(): ?\DateTimeInterface
    {
        return $this->expiration;
    }

    /**
     * Check whether the credentials keys are set.
     * True if the credentials keys are set.
     */
    public function hasKeys(): bool
    {
        return !empty($this->accessKeyId) && !empty($this->accessKeySecret);
    }

    /**
     * Check whether the credentials have expired.
     * True if the credentials have expired.
     */
    public function isExpired(): bool
    {
        return null !== $this->expiration && new \DateTimeImmutable() >= $this->expiration;
    }    
}
