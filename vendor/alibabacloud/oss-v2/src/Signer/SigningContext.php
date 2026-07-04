<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Signer;

use AlibabaCloud\Oss\V2\Credentials\Credentials;
use DateTime;
use Psr\Http\Message\RequestInterface;

class SigningContext
{

    /**
     * @var string|null
     */
    public ?string $product;

    /**
     * @var string|null
     */
    public ?string $region;

    /**
     * @var string|null
     */
    public ?string $bucket;

    /**
     * @var string|null
     */
    public ?string $key;

    /**
     * @var RequestInterface|null
     */
    public ?RequestInterface $request;

    /**
     * @var array
     */
    public array $subResource;

    /**
     * @var array
     */
    public array $additionalHeaders;

    /**
     * @var Credentials|null
     */
    public ?Credentials $credentials;

    /**
     * @var DateTime|null
     */
    public ?DateTime $time;

    /**
     * @var int
     */
    public int $clockOffset;

    /**
     * @var string|null
     */
    public ?string $signedHeaders;

    /**
     * @var string|null
     */
    public ?string $stringToSign;

    /**
     * @var bool
     */
    public bool $authMethodQuery;

    /**
     * For Test
     * @var DateTime
     */
    public DateTime $signTime;

    public function __construct(
        $product = null,
        $region = null,
        $bucket = null,
        $key = null,
        $request = null,
        $subResource = [],
        $additionalHeaders = [],
        $credentials = null,
        $time = null,
        $clockOffset = 0,
        $signedHeaders = null,
        $stringToSign = null,
        $authMethodQuery = false)
    {
        $this->product = $product;
        $this->region = $region;
        $this->bucket = $bucket;
        $this->key = $key;
        $this->request = $request;
        $this->subResource = $subResource;
        $this->additionalHeaders = $additionalHeaders;
        $this->credentials = $credentials;
        $this->time = $time;
        $this->signedHeaders = $signedHeaders;
        $this->stringToSign = $stringToSign;
        $this->authMethodQuery = $authMethodQuery;
        $this->clockOffset = $clockOffset;
    }
}

