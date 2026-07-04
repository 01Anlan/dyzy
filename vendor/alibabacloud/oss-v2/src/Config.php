<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use AlibabaCloud\Oss\V2\Credentials\CredentialsProvider;

final class Config
{
    /**
     * @var ?string The region in which the bucket is located.
     */
    private $region;

    /**
     * @var ?string The domain names that other services can use to access OSS.
     */
    private $endpoint;

    /**
     * @var ?string The signature version when signing requests. Valid values v4, v1
     */
    private $signatureVersion;

    /**
     * @var ?CredentialsProvider The credentials provider to use when signing requests.
     */
    private $credentialsProvider;

    /**
     * @var ?bool Forces the endpoint to be resolved as HTTP.
     */
    private $disableSSL;

    /**
     * @var ?bool Enable http redirect or not. Default is disable
     */
    private $enabledRedirect;

    /**
     * @var ?bool Skip server certificate verification.
     */
    private $insecureSkipVerify;

    /**
     * @var ?float The time in seconds till a timeout exception is thrown when attempting to make a connection.
     *             The default is 10 seconds.
     */
    private $connectTimeout;

    /**
     * @var ?float The time in seconds till a timeout exception is thrown when attempting to read from a connection.
     *             The default is 20 seconds.
     */
    private $readwriteTimeout;

    /**
     * @var ?string The proxy setting.
     */
    private $proxyHost;


    /**
     * @var ?bool Dual-stack endpoints are provided in some regions.
     */
    private $useDualStackEndpoint;

    /**
     * @var ?bool OSS provides the transfer acceleration feature to accelerate date transfers
     *            of data uploads and downloads across countries and regions.
     *            Set this to True to use a accelerate endpoint for the requests.
     */
    private $useAccelerateEndpoint;

    /**
     * @var ?bool You can use an internal endpoint to communicate between Alibaba Cloud services located
     *            within the same region over the internal network.
     *            You are not charged for the traffic generated over the internal network.
     *            Set this to True to use a internal endpoint for the requests.
     */
    private $useInternalEndpoint;


    /**
     * @var ?bool If the endpoint is s CName, set this flag to true
     */
    private $useCname;

    /**
     * @var ?bool Allows you to enable the client to use path-style addressing,
     *            i.e., https://oss-cn-hangzhou.aliyuncs.com/bucket/key.
     */
    private $usePathStyle;

    /**
     * @var ?int Specifies the maximum number attempts an API client will call
     *           an operation that fails with a retryable error.
     */
    private $retryMaxAttempts;

    /**
     * @var ?Retry\RetryerInterface Guides how HTTP requests should be retried in case of recoverable failures.
     */
    private $retryer;


    /**
     * @var ?string The optional user specific identifier appended to the User-Agent header.
     */
    private $userAgent;

    /**
     * The cloud box id
     * @var string|null
     */
    private ?string $cloudBoxId;

    /**
     * The cloud box id is automatically extracted from endpoint.
     * @var bool|null
     */
    private ?bool $enableAutoDetectCloudBoxId;


    /**
     * @var ?array Additional signable headers.
     */
    private $additionalHeaders;

    /**
     * Config constructor.
     * @param string|null $region
     * @param string|null $endpoint
     * @param string|null $signatureVersion
     * @param CredentialsProvider|null $credentialsProvider
     * @param bool|null $disableSSL
     * @param bool|null $insecureSkipVerify
     * @param float|null $connectTimeout
     * @param float|null $readwriteTimeout
     * @param string|null $proxyHost
     * @param bool|null $useDualStackEndpoint
     * @param bool|null $useAccelerateEndpoint
     * @param bool|null $useInternalEndpoint
     * @param bool|null $useCname
     * @param bool|null $usePathStyle
     * @param int|null $retryMaxAttempts
     * @param Retry\RetryerInterface|null $retryer
     * @param string|null $userAgent
     * @param array|null $additionalHeaders
     * @param string|null $cloudBoxId
     * @param bool|null $enableAutoDetectCloudBoxId
     */
    public function __construct(
        ?string $region = null,
        ?string $endpoint = null,
        ?string $signatureVersion = null,
        ?CredentialsProvider $credentialsProvider = null,
        ?bool $disableSSL = null,
        ?bool $insecureSkipVerify = null,
        ?float $connectTimeout = null,
        ?float $readwriteTimeout = null,
        ?string $proxyHost = null,
        ?bool $useDualStackEndpoint = null,
        ?bool $useAccelerateEndpoint = null,
        ?bool $useInternalEndpoint = null,
        ?bool $useCname = null,
        ?bool $usePathStyle = null,
        ?int $retryMaxAttempts = null,
        ?Retry\RetryerInterface $retryer = null,
        ?string $userAgent = null,
        ?array $additionalHeaders = null,
        ?string $cloudBoxId = null,
        ?bool $enableAutoDetectCloudBoxId = null
    )
    {
        $this->region = $region;
        $this->endpoint = $endpoint;
        $this->signatureVersion = $signatureVersion;
        $this->credentialsProvider = $credentialsProvider;
        $this->disableSSL = $disableSSL;
        $this->insecureSkipVerify = $insecureSkipVerify;
        $this->connectTimeout = $connectTimeout;
        $this->readwriteTimeout = $readwriteTimeout;
        $this->proxyHost = $proxyHost;
        $this->useDualStackEndpoint = $useDualStackEndpoint;
        $this->useAccelerateEndpoint = $useAccelerateEndpoint;
        $this->useInternalEndpoint = $useInternalEndpoint;
        $this->useCname = $useCname;
        $this->usePathStyle = $usePathStyle;
        $this->retryMaxAttempts = $retryMaxAttempts;
        $this->retryer = $retryer;
        $this->userAgent = $userAgent;
        $this->additionalHeaders = $additionalHeaders;
        $this->cloudBoxId = $cloudBoxId;
        $this->enableAutoDetectCloudBoxId = $enableAutoDetectCloudBoxId;
    }

    public static function loadDefault(): Config
    {
        return new Config();
    }

    /**
     * Get the region in which the bucket is located.
     *
     * @return  string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set the region in which the bucket is located.
     *
     * @param string $region The region in which the bucket is located.
     *
     * @return  self
     */
    public function setRegion(string $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get the domain names that other services can use to access OSS.
     *
     * @return  string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the domain names that other services can use to access OSS.
     *
     * @param string $endpoint The domain names that other services can use to access OSS.
     *
     * @return  self
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Get the signature version when signing requests. Valid values v4, v1
     *
     * @return  string
     */
    public function getSignatureVersion()
    {
        return $this->signatureVersion;
    }

    /**
     * Set the signature version when signing requests. Valid values v4, v1
     *
     * @param string $signatureVersion The signature version when signing requests. Valid values v4, v1
     *
     * @return  self
     */
    public function setSignatureVersion(string $signatureVersion)
    {
        $this->signatureVersion = $signatureVersion;

        return $this;
    }

    /**
     * Get the credentials provider to use when signing requests.
     *
     * @return  CredentialsProvider
     */
    public function getCredentialsProvider()
    {
        return $this->credentialsProvider;
    }

    /**
     * Set the credentials provider to use when signing requests.
     *
     * @param CredentialsProvider $credentialsProvider The credentials provider to use when signing requests.
     *
     * @return  self
     */
    public function setCredentialsProvider(CredentialsProvider $credentialsProvider)
    {
        $this->credentialsProvider = $credentialsProvider;

        return $this;
    }

    /**
     * Get forces the endpoint to be resolved as HTTP.
     *
     * @return  ?bool
     */
    public function getDisableSSL()
    {
        return $this->disableSSL;
    }

    /**
     * Set forces the endpoint to be resolved as HTTP.
     *
     * @param bool $disableSSL Forces the endpoint to be resolved as HTTP.
     *
     * @return  self
     */
    public function setDisableSSL(bool $disableSSL)
    {
        $this->disableSSL = $disableSSL;

        return $this;
    }

    /**
     * Get enable http redirect or not. Default is disable
     *
     * @return  ?bool
     */
    public function getEnabledRedirect()
    {
        return $this->enabledRedirect;
    }

    /**
     * Set enable http redirect or not. Default is disable
     *
     * @param  ?bool $enabledRedirect Enable http redirect or not. Default is disable
     *
     * @return  self
     */
    public function setEnabledRedirect(bool $enabledRedirect)
    {
        $this->enabledRedirect = $enabledRedirect;

        return $this;
    }

    /**
     * Get skip server certificate verification.
     *
     * @return  ?bool
     */
    public function getInsecureSkipVerify()
    {
        return $this->insecureSkipVerify;
    }

    /**
     * Set skip server certificate verification.
     *
     * @param  ?bool $insecureSkipVerify Skip server certificate verification.
     *
     * @return  self
     */
    public function setInsecureSkipVerify(bool $insecureSkipVerify)
    {
        $this->insecureSkipVerify = $insecureSkipVerify;

        return $this;
    }

    /**
     * Get the time in seconds till a timeout exception is thrown when attempting to make a connection.
     *
     * @return  ?float
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * Set the time in seconds till a timeout exception is thrown when attempting to make a connection.
     *
     * @param  ?float $connectTimeout The time in seconds till a timeout exception is thrown when attempting to make a connection.
     *
     * @return  self
     */
    public function setConnectTimeout(float $connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;

        return $this;
    }

    /**
     * Get the time in seconds till a timeout exception is thrown when attempting to read from a connection.
     *
     * @return  ?float
     */
    public function getReadwriteTimeout()
    {
        return $this->readwriteTimeout;
    }

    /**
     * Set the time in seconds till a timeout exception is thrown when attempting to read from a connection.
     *
     * @param  ?float $readwriteTimeout The time in seconds till a timeout exception is thrown when attempting to read from a connection.
     *
     * @return  self
     */
    public function setReadwriteTimeout(float $readwriteTimeout)
    {
        $this->readwriteTimeout = $readwriteTimeout;

        return $this;
    }

    /**
     * Get the proxy setting.
     *
     * @return  ?string
     */
    public function getProxyHost()
    {
        return $this->proxyHost;
    }

    /**
     * Set the proxy setting.
     *
     * @param  ?string $proxyHost The proxy setting.
     *
     * @return  self
     */
    public function setProxyHost(string $proxyHost)
    {
        $this->proxyHost = $proxyHost;

        return $this;
    }

    /**
     * Get dual-stack endpoints are provided in some regions.
     *
     * @return  ?bool
     */
    public function getUseDualStackEndpoint()
    {
        return $this->useDualStackEndpoint;
    }

    /**
     * Set dual-stack endpoints are provided in some regions.
     *
     * @param  ?bool $useDualStackEndpoint Dual-stack endpoints are provided in some regions.
     *
     * @return  self
     */
    public function setUseDualStackEndpoint(bool $useDualStackEndpoint)
    {
        $this->useDualStackEndpoint = $useDualStackEndpoint;

        return $this;
    }

    /**
     * Get oSS provides the transfer acceleration feature to accelerate date transfers
     *
     * @return  ?bool
     */
    public function getUseAccelerateEndpoint()
    {
        return $this->useAccelerateEndpoint;
    }

    /**
     * Set oSS provides the transfer acceleration feature to accelerate date transfers
     *
     * @param  ?bool $useAccelerateEndpoint OSS provides the transfer acceleration feature to accelerate date transfers
     *
     * @return  self
     */
    public function setUseAccelerateEndpoint(bool $useAccelerateEndpoint)
    {
        $this->useAccelerateEndpoint = $useAccelerateEndpoint;

        return $this;
    }

    /**
     * Get you can use an internal endpoint to communicate between Alibaba Cloud services located
     *
     * @return  ?bool
     */
    public function getUseInternalEndpoint()
    {
        return $this->useInternalEndpoint;
    }

    /**
     * Set you can use an internal endpoint to communicate between Alibaba Cloud services located
     *
     * @param  ?bool $useInternalEndpoint You can use an internal endpoint to communicate between Alibaba Cloud services located
     *
     * @return  self
     */
    public function setUseInternalEndpoint(bool $useInternalEndpoint)
    {
        $this->useInternalEndpoint = $useInternalEndpoint;

        return $this;
    }

    /**
     * Get if the endpoint is s CName, set this flag to true
     *
     * @return  ?bool
     */
    public function getUseCname()
    {
        return $this->useCname;
    }

    /**
     * Set if the endpoint is s CName, set this flag to true
     *
     * @param  ?bool $useCname If the endpoint is s CName, set this flag to true
     *
     * @return  self
     */
    public function setUseCname(bool $useCname)
    {
        $this->useCname = $useCname;

        return $this;
    }

    /**
     * Get allows you to enable the client to use path-style addressing,
     *
     * @return  ?bool
     */
    public function getUsePathStyle()
    {
        return $this->usePathStyle;
    }

    /**
     * Set allows you to enable the client to use path-style addressing,
     *
     * @param  ?bool $usePathStyle Allows you to enable the client to use path-style addressing,
     *
     * @return  self
     */
    public function setUsePathStyle(bool $usePathStyle)
    {
        $this->usePathStyle = $usePathStyle;

        return $this;
    }

    /**
     * Get specifies the maximum number attempts an API client will call
     *
     * @return  ?int
     */
    public function getRetryMaxAttempts()
    {
        return $this->retryMaxAttempts;
    }

    /**
     * Set specifies the maximum number attempts an API client will call
     *
     * @param  ?int $retryMaxAttempts Specifies the maximum number attempts an API client will call
     *
     * @return  self
     */
    public function setRetryMaxAttempts(int $retryMaxAttempts)
    {
        $this->retryMaxAttempts = $retryMaxAttempts;

        return $this;
    }

    /**
     * Get guides how HTTP requests should be retried in case of recoverable failures.
     *
     * @return  ?Retry\RetryerInterface
     */
    public function getRetryer()
    {
        return $this->retryer;
    }

    /**
     * Set guides how HTTP requests should be retried in case of recoverable failures.
     *
     * @param  ?Retry\RetryerInterface $retryer Guides how HTTP requests should be retried in case of recoverable failures.
     *
     * @return  self
     */
    public function setRetryer(Retry\RetryerInterface $retryer)
    {
        $this->retryer = $retryer;

        return $this;
    }

    /**
     * Get the optional user specific identifier appended to the User-Agent header.
     *
     * @return  ?string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set the optional user specific identifier appended to the User-Agent header.
     *
     * @param  ?string $userAgent The optional user specific identifier appended to the User-Agent header.
     *
     * @return  self
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get additional signable headers.
     *
     * @return  ?array
     */
    public function getAdditionalHeaders()
    {
        return $this->additionalHeaders;
    }

    /**
     * Set additional signable headers.
     *
     * @param  ?array $additionalHeaders Additional signable headers.
     *
     * @return  self
     */
    public function setAdditionalHeaders(array $additionalHeaders)
    {
        $this->additionalHeaders = $additionalHeaders;

        return $this;
    }

    /**
     * Set enable auto detect cloud box id.
     * @param bool $enableAutoDetectCloudBoxId
     * @return $this
     */
    public function setEnableAutoDetectCloudBoxId(bool $enableAutoDetectCloudBoxId)
    {
        $this->enableAutoDetectCloudBoxId = $enableAutoDetectCloudBoxId;

        return $this;
    }

    /**
     * Get enable auto detect cloud box id.
     * @return bool|null
     */
    public function getEnableAutoDetectCloudBoxId()
    {
        return $this->enableAutoDetectCloudBoxId;
    }

    /**
     * Set cloud box id.
     * @param string $cloudBoxId
     * @return  self
     */
    public function setCloudBoxId(string $cloudBoxId)
    {
        $this->cloudBoxId = $cloudBoxId;
        return $this;
    }

    /**
     * Get cloud box id.
     * @return string
     */
    public function getCloudBoxId()
    {
        return $this->cloudBoxId;
    }
}
