<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp;
use AlibabaCloud\Oss\V2\Exception;

final class ClientImpl
{
    /**
     * @var array<string,mixed>
     */
    private $sdkOptions = [
        'product' => 'oss',
        'region' => null,
        'endpoint' => null,
        'retry_max_attempts' => null,
        'retryer' => null,
        'signer' => null,
        'credentials_provider' => null,
        'address_style' => 'virtual',
        'auth_method' => 'header',
        'response_handlers' => null,
        'feature_flags' => 0,
        'additional_headers' => null,
        'response_stream' => null,
    ];

    private $innerOptions = [
        'handler' => null,
        'user_agent' => null,
    ];

    // guzzle
    private GuzzleHttp\Client $httpClient;
    private $requestOptions = [
        //GuzzleHttp\RequestOptions::ALLOW_REDIRECTS
        'allow_redirects' => false,
        //GuzzleHttp\RequestOptions::CONNECT_TIMEOUT
        'connect_timeout' => Defaults::CONNECT_TIMEOUT,
        //GuzzleHttp\RequestOptions::READ_TIMEOUT
        'read_timeout' => Defaults::READWRITE_TIMEOUT,
        //GuzzleHttp\RequestOptions::VERIFY
        'verify' => true,
    ];

    public function __construct(Config $config, array $options = [])
    {
        $this->resolveConfig($config);
        $this->resolveOptions($options);
        $this->applyOptions();
    }

    public function executeAsync(OperationInput &$input, array &$options = []): GuzzleHttp\Promise\PromiseInterface
    {
        $this->verifyOperation($input);
        [$request, $context] = $this->buildRequestContext($input, $options);
        /**
         * @var GuzzleHttp\Psr7\Request $request
         */
        return $this->httpClient->sendAsync($request, $context)->then(
            function ($response) use ($input) {
                // unwrap stream from SinkStreamWrapper
                $body = $response->getBody();
                if ($body instanceof SinkStreamWrapper) {
                    $body = $body->isFromFilepath() ? null : $body->unwrap();
                    $responseRo = new \ReflectionObject($response);
                    if ($responseRo->hasProperty('stream')) {
                        $pp = $responseRo->getProperty('stream');
                        if (PHP_VERSION_ID < 80100) {
                            $pp->setAccessible(true);
                        }
                        $pp->setValue($response, $body);
                    }
                }
                return new OperationOutput(
                    $response->getReasonPhrase(),
                    $response->getStatusCode(),
                    Utils::toSimpleArray($response->getHeaders()),
                    $body,
                    $input,
                    null,
                    $response,
                );
            },
            function ($reason) use ($input) {
                return GuzzleHttp\Promise\Create::rejectionFor(new Exception\OperationException(
                    $input->getOpName(),
                    $reason
                ));
            }
        );
    }

    public function presignInner(OperationInput &$input, array &$options = []): array
    {
        $this->verifyOperation($input);
        /**
         * @var GuzzleHttp\Psr7\Request $request
         */
        [$request, $context] = $this->buildRequestContext($input, $options);
        $sdk_context = $context['sdk_context'];
        /**
         * @var Credentials\CredentialsProvider $provider
         */
        $provider = $sdk_context['credentials_provider'];
        $result = [];
        if (!($provider instanceof Credentials\AnonymousCredentialsProvider)) {
            try {
                $cred = $provider->getCredentials();
            } catch (\Exception $e) {
                throw new Exception\CredentialsException('Fetch Credentials raised an exception', $e);
            }
            if ($cred == null || !$cred->hasKeys()) {
                throw new \InvalidArgumentException("Credentials is null or empty.");
            }
            /**
             * @var Signer\SignerInterface $signer
             */
            $signer = $sdk_context['signer'];
            /**
             * @var Signer\SigningContext $signingContext
             */
            $signingContext = $context['signing_context'];
            $signingContext->request = $request;
            $signingContext->credentials = $cred;
            $signer->sign($signingContext);
            $request = $signingContext->request;
            if ($signer instanceof Signer\SignerV4) {
                if (isset($signingContext->time) && $signingContext->time instanceof \DateTime) {
                    $nowPlusSevenDays = new \DateTime();
                    $nowPlusSevenDays->modify('+7 days');
                    if ($signingContext->time > $nowPlusSevenDays) {
                        throw new \InvalidArgumentException("Expires should not be greater than 604800 (seven days)");
                    }
                }
            }
            //save to result
            $result['expiration'] = $signingContext->time;
            $matches = ['content-type', 'content-md5'];
            if ($signer instanceof Signer\SignerV4 && !empty($signingContext->additionalHeaders)) {
                foreach ($signingContext->additionalHeaders as $k) {
                    $matches[] = strtolower($k);
                }
            }
            //signed headers
            $signedHeaders = [];
            foreach ($request->getHeaders() as $name => $values) {
                $lowName = strtolower((string)$name);
                if (
                    \in_array($lowName, $matches) ||
                    (strncmp($lowName, 'x-oss-', 6) == 0)
                ) {
                    $signedHeaders[(string)$name] = $values[0];
                }
            }
            $result['signedHeaders'] = $signedHeaders;
        }

        $result['url'] = $request->getUri()->__toString();
        $result['method'] = $request->getMethod();

        return $result;
    }

    private function resolveConfig(Config &$config)
    {
        // client's options
        $options = $this->sdkOptions;
        $options['region'] = $config->getRegion();
        $options['credentials_provider'] = $config->getCredentialsProvider();
        $options['additional_headers'] = $config->getAdditionalHeaders();
        $this->resolveEndpoint($config, $options);
        $this->resolveRetryer($config, $options);
        $this->resolveSigner($config, $options);
        $this->resolveAddressStyle($config, $options);
        $this->resolveFeatureFlags($config, $options);
        $this->resolveCloudBox($config, $options);

        $this->sdkOptions = $options;

        // user-agent
        $this->innerOptions['user_agent'] = $this->buildUserAgent($config);

        // guzzle's client
        $options = $this->requestOptions;
        $this->resolveHttpClient($config, $options);
        $this->requestOptions = $options;
    }

    private function resolveEndpoint(Config &$config, array &$options)
    {
        $disableSSL = Utils::safetyBool($config->getDisableSSL());
        $endpoint = Utils::safetyString($config->getEndpoint());
        $region = Utils::safetyString($config->getRegion());
        if (\strlen($endpoint) > 0) {
            $endpoint = Utils::addScheme($endpoint, $disableSSL);
        } else if (Validation::isValidRegion($region)) {
            if (Utils::safetyBool($config->getUseDualStackEndpoint())) {
                $etype = 'dualstack';
            } else if (Utils::safetyBool($config->getUseInternalEndpoint())) {
                $etype = 'internal';
            } else if (Utils::safetyBool($config->getUseAccelerateEndpoint())) {
                $etype = 'accelerate';
            } else {
                $etype = 'default';
            }
            $endpoint = Utils::regionToEndpoint($region, $disableSSL, $etype);
        }

        if ($endpoint === '') {
            return;
        }

        $options['endpoint'] = new GuzzleHttp\Psr7\Uri($endpoint);
    }

    private function resolveRetryer(Config &$config, array &$options)
    {
        if (Utils::safetyInt($config->getRetryMaxAttempts()) > 0) {
            $options['retry_max_attempts'] = $config->getRetryMaxAttempts();
        }

        $options['retryer'] = $config->getRetryer() ?? new Retry\StandardRetryer();
    }

    private function resolveSigner(Config &$config, array &$options)
    {
        $value = Utils::safetyString($config->getSignatureVersion());
        $options['signer'] = $value == 'v1' ? new Signer\SignerV1() : new Signer\SignerV4();
    }

    private function resolveAddressStyle(Config &$config, array &$options)
    {
        if (Utils::safetyBool($config->getUseCname())) {
            $options['address_style'] = 'cname';
        } else if (Utils::safetyBool($config->getUsePathStyle())) {
            $options['address_style'] = 'path';
        } else {
            $options['address_style'] = 'virtual';
        }

        if ($options['endpoint'] instanceof GuzzleHttp\Psr7\Uri) {
            if (Utils::isIPFormat($options['endpoint']->getHost())) {
                $options['address_style'] = 'path';
            }
        }
    }

    private function resolveFeatureFlags(Config &$config, array &$options)
    {
    }

    private function resolveHttpClient(Config &$config, array &$options)
    {
        // map into GuzzleHttp request options
        if (Utils::safetyBool($config->getEnabledRedirect())) {
            //GuzzleHttp\RequestOptions::ALLOW_REDIRECTS
            $options['allow_redirects'] = true;
        }

        if (Utils::safetyBool($config->getInsecureSkipVerify())) {
            //GuzzleHttp\RequestOptions::VERIFY
            $options['verify'] = false;
        }

        $value = $config->getConnectTimeout();
        if (Utils::safetyFloat($value) > 0) {
            //GuzzleHttp\RequestOptions::CONNECT_TIMEOUT
            $options['connect_timeout'] = $value;
        }

        $value = $config->getReadwriteTimeout();
        if (Utils::safetyFloat($value) > 0) {
            //GuzzleHttp\RequestOptions::READ_TIMEOUT
            $options['read_timeout'] = $value;
        }

        $value = $config->getProxyHost();
        if (Utils::safetyString($value) !== '') {
            //GuzzleHttp\RequestOptions::PROXY
            $options['proxy'] = $value;
        }
    }

    private function resolveCloudBox(Config &$config, array &$options)
    {
        if ($config->getCloudBoxId() !== null) {
            $options['region'] = $config->getCloudBoxId();
            $options['product'] = Defaults::CLOUD_BOX_PRODUCT;
            return;
        }
        if ($config->getEnableAutoDetectCloudBoxId() == false) {
            return;
        }
        if ($config->getEndpoint() == null) {
            return;
        }
        //cb-***.{region}.oss-cloudbox-control.aliyuncs.com
        //cb-***.{region}.oss-cloudbox.aliyuncs.com
        $uri = new GuzzleHttp\Psr7\Uri($config->getEndpoint());
        $path = !empty($uri->getHost()) ? $uri->getHost() : $uri->getPath();
        if (!(substr($path, -strlen('.oss-cloudbox.aliyuncs.com')) === '.oss-cloudbox.aliyuncs.com' || substr($path, -strlen('.oss-cloudbox-control.aliyuncs.com')) === '.oss-cloudbox-control.aliyuncs.com')) {
            return;
        }
        $keys = explode('.', $path);
        if ($keys == false ||
            count($keys) !== 5 ||
            substr($keys[0], 0, strlen('cb-')) !== 'cb-') {
            return;
        }
        $options['region'] = $keys[0];
        $options['product'] = Defaults::CLOUD_BOX_PRODUCT;
    }

    private function resolveOptions(array &$options)
    {
        if (empty($options)) {
            return;
        }

        // sdk options
        $opt = \array_filter($options, function ($key) {
            return \array_key_exists($key, $this->sdkOptions);
        }, \ARRAY_FILTER_USE_KEY);
        $this->sdkOptions = array_replace($this->sdkOptions, $opt);

        // inner options
        $opt = \array_filter($options, function ($key) {
            return \array_key_exists($key, $this->innerOptions);
        }, \ARRAY_FILTER_USE_KEY);
        $this->innerOptions = array_replace($this->innerOptions, $opt);

        // Guzzle's request options
        $src = $options['request_options'] ?? [];
        if (!empty($src)) {
            $allows = [
                'allow_redirects',
                'proxy',
                'expect',
                'cert',
                'verify',
                'ssl_key',
                'connect_timeout',
                'read_timeout',
                'debug',
                'decode_content',
            ];
            $opt = \array_filter($src, function ($key) use ($allows) {
                return in_array($key, $allows);
            }, \ARRAY_FILTER_USE_KEY);
            $this->requestOptions = array_replace($this->requestOptions, $opt);
        }
    }

    private function applyOptions()
    {
        // GuzzleHttp\Client
        // request options
        $config = \array_merge([], $this->requestOptions);

        // stack
        $handler = $this->innerOptions['handler'] ?: GuzzleHttp\Utils::chooseHandler();
        $stack = new GuzzleHttp\HandlerStack($handler);

        // retryer
        $stack->push(RetryMiddleware::create(
            static function (
                int $retries,
                \Psr\Http\Message\RequestInterface $request,
                \Throwable $reason,
                array $options
            ) {

                if (!$request->getBody()->isSeekable()) {
                    return false;
                }

                // check sink
                if (isset($options['sink']) && !$options['sink']->isSeekable()) {
                    return false;
                }

                if ($retries + 1 >= $options['sdk_context']['retry_max_attempts']) {
                    return false;
                }

                // api's timeout


                // retryable error
                if (!$options['sdk_context']['retryer']->isErrorRetryable($reason)) {
                    return false;
                }

                // reset state
                try {
                    $request->getBody()->rewind();
                    if (isset($options['sink'])) {
                        $options['sink']->rewind();
                        $options['sink']->resetMemState();
                    }
                } catch (\Exception $e) {
                    throw new Exception\StreamRewindException($e->getMessage(), $reason);
                }

                if ($options['sdk_context']['reset_time']) {
                    $options['signing_context']->time = null;
                }

                //printf("retry cnt %d, %d\n", $retries, $options['sdk_context']['retry_max_attempts']);
                return true;
            },
            static function (int $retries, array $options): int {
                //int in milliseconds
                $delay = $options['sdk_context']['retryer']->retryDelay($retries, null);
                return (int)($delay * 1000);
            },
        ), 'retryer');

        // signer
        $stack->push(static function (callable $handler): callable {
            return static function (\Psr\Http\Message\RequestInterface $request, array $options) use ($handler) {
                $sdk_context = $options['sdk_context'];
                $provider = $sdk_context['credentials_provider'];
                if (!($provider instanceof Credentials\AnonymousCredentialsProvider)) {
                    try {
                        $cred = $provider->getCredentials();
                    } catch (\Exception $e) {
                        throw new Exception\CredentialsException('Fetch Credentials raised an exception', $e);
                    }

                    if ($cred == null || !$cred->hasKeys()) {
                        throw new \InvalidArgumentException("Credentials is null or empty.");
                    }
                    $signer = $sdk_context['signer'];
                    $signingContext = $options['signing_context'];
                    $signingContext->request = $request;
                    $signingContext->credentials = $cred;
                    $signer->sign($signingContext);
                    $request = $signingContext->request;
                }
                return $handler($request, $options);
            };
        }, 'signer');

        // http response checker
        $stack->push(static function (callable $handler): callable {
            return static function ($request, array $options) use ($handler) {
                if (empty($options['response_handlers'])) {
                    return $handler($request, $options);
                }
                return $handler($request, $options)->then(
                    static function (\Psr\Http\Message\ResponseInterface $response) use ($request, $options) {
                        foreach ($options['response_handlers'] as $h) {
                            if (\is_callable($h)) {
                                $h($request, $response, $options);
                            } else {
                                call_user_func($h, $request, $response, $options);
                            }
                        }
                        return $response;
                    }
                );
            };
        }, 'response_handlers');

        $stack->push(GuzzleHttp\Middleware::redirect(), 'allow_redirects');
        $stack->push(GuzzleHttp\Middleware::prepareBody(), 'prepare_body');

        $config['handler'] = $stack;

        $this->httpClient = new GuzzleHttp\Client($config);
    }


    private function buildUserAgent(Config &$config)
    {
        $value = Utils::defaultUserAgent();

        if ($config->getUserAgent() != null) {
            $value = $value . '/' . $config->getUserAgent();
        }

        return $value;
    }

    private function buildUri(OperationInput &$input, \Psr\Http\Message\UriInterface $uri): \Psr\Http\Message\UriInterface
    {
        $paths = [];
        if ($input->getBucket() != null) {
            switch ($this->sdkOptions['address_style']) {
                case 'path':
                    array_push($paths, $input->getBucket());
                    if ($input->getKey() == null) {
                        array_push($paths, '');
                    }
                    break;
                case 'cname':
                    break;
                default:
                    $uri = $uri->withHost($input->getBucket() . '.' . $uri->getHost());
                    break;
            }
        }

        if ($input->getKey() != null) {
            array_push($paths, Utils::urlEncode($input->getKey(), true));
        }

        return $uri->withPath('/' . implode('/', $paths));
    }

    private function verifyOperation(OperationInput &$input)
    {
        if (!isset($this->sdkOptions['endpoint'])) {
            throw new \InvalidArgumentException('endpoint is invalid.');
        }

        if (
            $input->getBucket() != null &&
            !Validation::isValidBucketName($input->getBucket())
        ) {
            throw new \InvalidArgumentException('Bucket name is invalid, got ' . $input->getBucket());
        }

        if (
            $input->getKey() != null &&
            !Validation::isValidObjectName($input->getKey())
        ) {
            throw new \InvalidArgumentException('Object name is invalid, got ' . $input->getKey());
        }
    }

    /**
     * @param OperationInput $input
     * @param array $options
     * @return array<GuzzleHttp\Psr7\Request,array>
     */
    private function buildRequestContext(OperationInput &$input, array &$options)
    {
        $context = [];
        // GuzzleHttp's request options for api
        if (isset($options['request_options'])) {
            if (isset($options['request_options']['connect_timeout'])) {
                $context['connect_timeout'] = $options['request_options']['connect_timeout'];
            }
            if (isset($options['request_options']['read_timeout'])) {
                $context['read_timeout'] = $options['request_options']['read_timeout'];
            }
            if (isset($options['request_options']['timeout'])) {
                $context['timeout'] = $options['request_options']['timeout'];
            }
            if (isset($options['request_options']['on_headers'])) {
                $context['on_headers'] = $options['request_options']['on_headers'];
            }
            if (isset($options['request_options']['sink'])) {
                $sink = $options['request_options']['sink'];
                $sink = SinkStreamWrapper::sinkFor($sink);
                $context['sink'] = $sink;
                $context['on_headers'] = function (\Psr\Http\Message\ResponseInterface $response) use (&$sink) {
                    $sink->on_headers($response);
                };
            }
            if (isset($options['request_options']['stream'])) {
                $context['stream'] = $options['request_options']['stream'];
            }
            if (isset($options['request_options']['on_stats'])) {
                $context['on_stats'] = $options['request_options']['on_stats'];
            }
        }

        // retry options for api
        $retryer = $options['retryer'] ?? $this->sdkOptions['retryer'];
        if (Utils::safetyInt($options['retry_max_attempts'] ?? 0) > 0) {
            $retry_max_attempts = $options['retry_max_attempts'];
        } else if (isset($this->sdkOptions['retry_max_attempts'])) {
            $retry_max_attempts = $this->sdkOptions['retry_max_attempts'];
        } else {
            $retry_max_attempts = $retryer->getMaxAttempts();
        }

        // sdk's part
        $sdk_context = [
            'retry_max_attempts' => \max($retry_max_attempts, 1),
            'retryer' => $retryer,
            'signer' => $this->sdkOptions['signer'],
            'credentials_provider' => $options['credentials_provider'] ?? $this->sdkOptions['credentials_provider'],
        ];

        $context['sdk_context'] = $sdk_context;


        // Request
        // host & path & query
        /**
         * @var \Psr\Http\Message\UriInterface $endpoint
         */
        $endpoint = $this->sdkOptions['endpoint'];
        $baseuri = $endpoint->getScheme() . "://" . $endpoint->getAuthority();
        $uri = $this->buildUri($input, new GuzzleHttp\Psr7\Uri($baseuri));
        $query = $input->getParameters();
        if (!empty($query)) {
            $uri = $uri->withQuery(
                http_build_query($query, '', '&', PHP_QUERY_RFC3986)
            );
        }

        $request = new GuzzleHttp\Psr7\Request(
            $input->getMethod(),
            $uri,
            $input->getHeaders(),
            $input->getBody(),
        );

        // user agent
        $request = $request->withAddedHeader('User-Agent', $this->innerOptions['user_agent']);

        // Add a default content-type if possible.
        if ($input->hasOpMetadata('detect_content_type') &&
            !isset($options['sdk_presign']) &&
            !$request->hasHeader('Content-Type')) {
            $value = Utils::guessContentType($input->getKey());
            // compatibility with PrepareBodyMiddleware
            if ($value == null && $request->getBody() != null) {
                if ($uri = $request->getBody()->getMetadata('uri')) {
                    if (is_string($uri) && $type = Utils::guessContentType($uri)) {
                        $value = $type;
                    }
                }
            }
            if ($value == null) {
                $value = 'application/octet-stream';
            }
            $request = $request->withAddedHeader('Content-Type', $value);
        }

        // signing context
        $signingContext = new Signer\SigningContext(
            $this->sdkOptions['product'],
            $this->sdkOptions['region'],
            $input->getBucket(),
            $input->getKey(),
            $request
        );
        if (isset($this->sdkOptions['additional_headers'])) {
            $signingContext->additionalHeaders = $this->sdkOptions['additional_headers'];
        }
        $auth_method = $options['auth_method'] ?? $this->sdkOptions['auth_method'];
        $signingContext->authMethodQuery = $auth_method === 'query';
        $signingContext->subResource = ($input->getOpMetadata())['sub-resource'] ?? [];

        // signing time from user
        if (isset(($input->getOpMetadata())['expiration_time'])) {
            $signingContext->time = ($input->getOpMetadata())['expiration_time'];
        }

        $context['signing_context'] = $signingContext;

        $context['sdk_context']['reset_time'] = $signingContext->time == null;

        // response-handler
        $responseHandlers = [
            [ClientImpl::class, 'httpErrors'],
        ];

        if (isset($options['response_handlers'])) {
            foreach ($options['response_handlers'] as $h) {
                \array_push($responseHandlers, $h);
            }
        }

        $context['response_handlers'] = $responseHandlers;

        return [$request, $context];
    }

    private static function httpErrors(
        \Psr\Http\Message\RequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        array $options
    )
    {
        $statusCode = $response->getStatusCode();
        if (intval($statusCode / 100) == 2) {
            return;
        }
        $content = isset($options['sink']) ? $options['sink']->getMemContent() :
            $response->getBody()->getContents();
        $code = 'BadErrorResponse';
        $message = '';
        $ec = '';
        $requestId = '';
        $errorFields = [];

        $xmlStr = $content;
        if ($xmlStr === '' && $response->hasHeader('x-oss-err')) {
            $xmlStr = base64_decode($response->getHeader('x-oss-err')[0]);
        }

        if (strpos($xmlStr, '<Error>') !== false) {
            try {
                $xml = Utils::parseXml($xmlStr);
                $code = isset($xml->Code) ? (string)$xml->Code : $code;
                $message = isset($xml->Message) ? (string)$xml->Message : '';
                $ec = isset($xml->EC) ? (string)$xml->EC : '';
                $requestId = isset($xml->RequestId) ? (string)$xml->RequestId : '';
                foreach ($xml as $key => $val) {
                    $errorFields[$key] = (string)$val;
                }
            } catch (\Exception $e) {
                //maybe contains speical char, from 0x1 - 0x1F
                //find <Code>...</Code> <Message>...</Message> <RequestId>...</RequestId> <EC>...</EC>
                $value = Utils::findXmlElementText($xmlStr, 'Code');
                if ($value !== '') {
                    $code = $value;
                    $message = Utils::findXmlElementText($xmlStr, 'Message');
                    $requestId = Utils::findXmlElementText($xmlStr, 'RequestId');
                    $ec = Utils::findXmlElementText($xmlStr, 'EC');
                } else {
                    $message = 'Failed to parse xml from response body, part response body ' . substr($xmlStr, 0, 256);
                }
            }
        } else {
            $message = 'Not found tag <Error>, part response body ' . substr($xmlStr, 0, 256);
        }

        if ($requestId == '' && $response->hasHeader('x-oss-request-id')) {
            $requestId = $response->getHeader('x-oss-request-id')[0];
        }

        if ($ec == '' && $response->hasHeader('x-oss-ec')) {
            $ec = $response->getHeader('x-oss-ec')[0];
        }

        throw new Exception\ServiceException(
            [
                'status_code' => $statusCode,
                'request_id' => $requestId,
                'code' => $code,
                'message' => $message,
                'ec' => $ec,
                'request_target' => $request->getMethod() . ' ' . $request->getUri(),
                'snapshot' => $content,
                'headers' => $response->getHeaders(),
                'error_fields' => $errorFields
            ]
        );
    }
}
