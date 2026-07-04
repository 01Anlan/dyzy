<?php

namespace UnitTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'NoRewindStream.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'RewindStatStream.php';


use AlibabaCloud\Oss\V2\Config;
use AlibabaCloud\Oss\V2\Credentials\CredentialsProvider;
use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Signer;
use AlibabaCloud\Oss\V2\Retry;
use AlibabaCloud\Oss\V2\Credentials;
use AlibabaCloud\Oss\V2\Utils;
use AlibabaCloud\Oss\V2\ClientImpl;
use AlibabaCloud\Oss\V2\Defaults;
use AlibabaCloud\Oss\V2\Exception;
use AlibabaCloud\Oss\V2\SinkStreamWrapper;
use DateInterval;
use GuzzleHttp;
use UnitTests\Fixtures\NoRewindStream;
use UnitTests\Fixtures\RewindStatStream;


class ClientImplTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultConfig()
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);

        $ro = new \ReflectionObject($client);

        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');

        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }

        $sdkOptions = $pSdkOptions->getValue($client);
        $innerOptions = $pInnerOptions->getValue($client);
        $requestOptions = $pRequestOptions->getValue($client);

        //default
        $this->assertEquals('oss', $sdkOptions['product']);
        $this->assertEquals('cn-hangzhou', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        $this->assertEquals(null, $sdkOptions['retry_max_attempts']);
        $this->assertInstanceOf(Retry\StandardRetryer::class, $sdkOptions['retryer']);

        $this->assertInstanceOf(Signer\SignerV4::class, $sdkOptions['signer']);
        $this->assertInstanceOf(Credentials\AnonymousCredentialsProvider::class, $sdkOptions['credentials_provider']);

        $this->assertEquals('virtual', $sdkOptions['address_style']);
        $this->assertEquals('header', $sdkOptions['auth_method']);
        $this->assertEquals(null, $sdkOptions['response_handlers']);
        $this->assertEquals(0, $sdkOptions['feature_flags']);
        $this->assertEquals(null, $cfg->getCloudBoxId());
        $this->assertEquals(null, $cfg->getEnableAutoDetectCloudBoxId());

        #$this->assertEquals('oss', $sdkOptions['response_stream']);

        $this->assertEquals(null, $innerOptions['handler']);
        $this->assertStringContainsString('alibabacloud-php-sdk-v2/0.', $innerOptions['user_agent']);

        $this->assertEquals(False, $requestOptions['allow_redirects']);
        $this->assertEquals(10.0, $requestOptions['connect_timeout']);
        $this->assertEquals(20.0, $requestOptions['read_timeout']);
        $this->assertEquals(True, $requestOptions['verify']);

        // test cloud box id
        $cfg = Config::loadDefault();
        $cfg->setCloudBoxId('cb-1234');
        $this->assertEquals('cb-1234', $cfg->getCloudBoxId());

        $cfg->setEnableAutoDetectCloudBoxId(true);
        $this->assertTrue($cfg->getEnableAutoDetectCloudBoxId());

        $cfg->setEnableAutoDetectCloudBoxId(false);
        $this->assertFalse($cfg->getEnableAutoDetectCloudBoxId());
    }

    public function testConfigCredentialsProvider()
    {
        // default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(null, $sdkOptions['credentials_provider']);

        // set AnonymousCredentialsProvider
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Credentials\AnonymousCredentialsProvider::class, $sdkOptions['credentials_provider']);

        // set static
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Credentials\StaticCredentialsProvider::class, $sdkOptions['credentials_provider']);
        $cred = $sdkOptions['credentials_provider']->getCredentials();

        $this->assertEquals('ak', $cred->getAccessKeyId());
        $this->assertEquals('sk', $cred->getAccessKeySecret());
    }

    public function testConfigSignatureVersion()
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        // default 
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Signer\SignerV4::class, $sdkOptions['signer']);

        // set to v1
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Signer\SignerV1::class, $sdkOptions['signer']);


        // set invalid 
        $cfg->setSignatureVersion('any-str');
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Signer\SignerV4::class, $sdkOptions['signer']);

        // set v4 
        $cfg->setSignatureVersion('v4');
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);

        $this->assertInstanceOf(Signer\SignerV4::class, $sdkOptions['signer']);
    }

    public function testConfigEndpoint()
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('oss-cn-beijing.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // use_internal_endpoint
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-shanghai');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUseInternalEndpoint(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('oss-cn-shanghai-internal.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // use_accelerate_endpoint
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUseAccelerateEndpoint(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('oss-accelerate.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // use_dualstack_endpoint
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUseDualStackEndpoint(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cn-hangzhou.oss.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // user-defined endpoint
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setEndpoint('http://oss-cn-shenzhen.aliyuncs.com');

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('http', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('oss-cn-shenzhen.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // disable ssl
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUseDualStackEndpoint(true);
        $cfg->setDisableSSL(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('http', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cn-hangzhou.oss.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());


    }

    public function testConfigAddressStyle()
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('virtual', $sdkOptions['address_style']);

        //set path-style 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUsePathStyle(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('path', $sdkOptions['address_style']);

        //set cname 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUseCname(true);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('cname', $sdkOptions['address_style']);

        //use ip endpoint 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setEndpoint('192.168.1.1');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('path', $sdkOptions['address_style']);
    }

    public function testConfigRetryer()
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(null, $sdkOptions['retry_max_attempts']);
        $this->assertInstanceOf(Retry\StandardRetryer::class, $sdkOptions['retryer']);

        //set retry_max_attempts
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setRetryMaxAttempts(2);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(2, $sdkOptions['retry_max_attempts']);
        $this->assertInstanceOf(Retry\StandardRetryer::class, $sdkOptions['retryer']);

        //set retryer
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setRetryer(new Retry\NopRetryer());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(null, $sdkOptions['retry_max_attempts']);
        $this->assertInstanceOf(Retry\NopRetryer::class, $sdkOptions['retryer']);

        //set retry_max_attempts and retryer
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setRetryMaxAttempts(2);
        $cfg->setRetryer(new Retry\StandardRetryer(4));

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(2, $sdkOptions['retry_max_attempts']);
        $this->assertInstanceOf(Retry\StandardRetryer::class, $sdkOptions['retryer']);
        $this->assertEquals(4, $sdkOptions['retryer']->getMaxAttempts());
    }

    public function testConfigUserAgent()
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pInnerOptions = $ro->getProperty('innerOptions');
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        $innerOptions = $pInnerOptions->getValue($client);
        $this->assertEquals(Utils::defaultUserAgent(), $innerOptions['user_agent']);

        //set user-agent 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUserAgent('my-agent');

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pInnerOptions = $ro->getProperty('innerOptions');
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        $innerOptions = $pInnerOptions->getValue($client);
        $this->assertEquals(Utils::defaultUserAgent() . '/my-agent', $innerOptions['user_agent']);

        //set repalce by options 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUserAgent('my-agent');

        $client = new ClientImpl($cfg, ['user_agent' => 'user-define-agent']);
        $ro = new \ReflectionObject($client);
        $pInnerOptions = $ro->getProperty('innerOptions');
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        $innerOptions = $pInnerOptions->getValue($client);
        $this->assertEquals('user-define-agent', $innerOptions['user_agent']);
    }

    public function testConfigAdditionalHeaders()
    {
        //default
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(null, $sdkOptions['additional_headers']);

        //set from config
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setAdditionalHeaders(['header1', 'header2']);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(['header1', 'header2'], $sdkOptions['additional_headers']);
    }

    public function testConfigTimeout()
    {
        //default
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals(False, $requestOptions['allow_redirects']);
        $this->assertEquals(10.0, $requestOptions['connect_timeout']);
        $this->assertEquals(20.0, $requestOptions['read_timeout']);
        $this->assertEquals(True, $requestOptions['verify']);

        // set from config
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setInsecureSkipVerify(true);
        $cfg->setEnabledRedirect(true);
        $cfg->setConnectTimeout(30.0);
        $cfg->setReadwriteTimeout(40.5);

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals(true, $requestOptions['allow_redirects']);
        $this->assertEquals(30.0, $requestOptions['connect_timeout']);
        $this->assertEquals(40.5, $requestOptions['read_timeout']);
        $this->assertEquals(false, $requestOptions['verify']);

        // set from options
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl(
            $cfg,
            [
                'request_options' => [
                    'allow_redirects' => true,
                    'connect_timeout' => 11.0,
                    'read_timeout' => 22.0,
                    'verify' => false,
                ],
            ]
        );
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        #$this->assertEquals(true, $requestOptions['allow_redirects']);
        $this->assertEquals(11.0, $requestOptions['connect_timeout']);
        $this->assertEquals(22.0, $requestOptions['read_timeout']);
        $this->assertEquals(false, $requestOptions['verify']);

        // set from config
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setInsecureSkipVerify(true);
        $cfg->setEnabledRedirect(true);
        $cfg->setConnectTimeout(30.0);
        $cfg->setReadwriteTimeout(40.5);

        $client = new ClientImpl(
            $cfg,
            [
                'request_options' => [
                    'allow_redirects' => false,
                    'connect_timeout' => 12.0,
                    'read_timeout' => 23.0,
                    'verify' => true,
                ],
            ]
        );
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals(false, $requestOptions['allow_redirects']);
        $this->assertEquals(12.0, $requestOptions['connect_timeout']);
        $this->assertEquals(23.0, $requestOptions['read_timeout']);
        $this->assertEquals(true, $requestOptions['verify']);
    }

    public function testConfigProxy()
    {
        //default
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals(false, isset($requestOptions['proxy']));

        // set from config
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setProxyHost('http://127.0.0.1:8080');

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals('http://127.0.0.1:8080', $requestOptions['proxy']);

        // set from options
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl(
            $cfg,
            [
                'request_options' => [
                    'proxy' => 'http://127.0.0.1:3182',
                ],
            ]
        );
        $ro = new \ReflectionObject($client);
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $requestOptions = $pRequestOptions->getValue($client);
        $this->assertEquals('http://127.0.0.1:3182', $requestOptions['proxy']);
    }

    public function testConfigAuthMethod()
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('header', $sdkOptions['auth_method']);

        //set query 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg, ['auth_method' => 'query']);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('query', $sdkOptions['auth_method']);
    }

    public function testConfigProduct(): void
    {
        //default 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss', $sdkOptions['product']);

        //set oss-cloudbox 
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $client = new ClientImpl($cfg, ['product' => 'oss-cloudbox']);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss-cloudbox', $sdkOptions['product']);
    }

    public function testInvokeOperationOptions(): void
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        //default
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT",
        );
        $client->executeAsync($input)->wait();
        $options = $mock->getLastOptions();
        #GuzzleHttp's request options
        $this->assertEquals(false, $options['allow_redirects']);
        $this->assertEquals(Defaults::CONNECT_TIMEOUT, $options['connect_timeout']);
        $this->assertEquals(Defaults::READWRITE_TIMEOUT, $options['read_timeout']);
        $this->assertEquals(true, $options['verify']);
        #sdk's request options
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $options['sdk_context']['retry_max_attempts']);
        $this->assertInstanceOf(retry\StandardRetryer::class, $options['sdk_context']['retryer']);

        // test retry_max_attempts & retryer
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $opt = [
            'retry_max_attempts' => 4,
            'retryer' => new Retry\NopRetryer(),
        ];
        $client->executeAsync($input, $opt)->wait();
        $options = $mock->getLastOptions();
        // GuzzleHttp's request options
        $this->assertEquals(false, $options['allow_redirects']);
        $this->assertEquals(Defaults::CONNECT_TIMEOUT, $options['connect_timeout']);
        $this->assertEquals(Defaults::READWRITE_TIMEOUT, $options['read_timeout']);
        $this->assertEquals(true, $options['verify']);
        // sdk's request options
        $this->assertEquals(4, $options['sdk_context']['retry_max_attempts']);
        $this->assertInstanceOf(retry\NopRetryer::class, $options['sdk_context']['retryer']);

        // test retryer
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $opt = [
            'retryer' => new Retry\StandardRetryer(10),
        ];
        $client->executeAsync($input, $opt)->wait();
        $options = $mock->getLastOptions();
        $this->assertEquals(10, $options['sdk_context']['retry_max_attempts']);
        $this->assertInstanceOf(retry\StandardRetryer::class, $options['sdk_context']['retryer']);

        // test request options
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $opt = [
            'request_options' =>
                [
                    'allow_redirects' => true,
                    'connect_timeout' => 31.0,
                    'read_timeout' => 15.2,
                    'verify' => false,
                ]
        ];
        $client->executeAsync($input, $opt)->wait();
        $options = $mock->getLastOptions();
        // GuzzleHttp's request options
        $this->assertEquals(31.0, $options['connect_timeout']);
        $this->assertEquals(15.2, $options['read_timeout']);
        // can't be modified from api options.
        $this->assertEquals(false, $options['allow_redirects']);
        $this->assertEquals(true, $options['verify']);
    }

    public function testRequestContext(): void
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        // user-agent
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT",
        );
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();

        $this->assertEquals(Utils::defaultUserAgent(), $request->getHeader('User-Agent')[0]);

        // uri format
        # virtual, no bucket and key, only bucket,  bucket and key
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT",
        );
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://oss-cn-beijing.aliyuncs.com/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://my-bucket.oss-cn-beijing.aliyuncs.com/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://my-bucket.oss-cn-beijing.aliyuncs.com/123/321/%2B%3F%20/123.txt', $request->getUri()->__tostring());

        # cname, no bucket and key, only bucket,  bucket and key
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setEndpoint('www.cname.com');
        $cfg->setUseCname(true);
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://www.cname.com/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://www.cname.com/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://www.cname.com/123/321/%2B%3F%20/123.txt', $request->getUri()->__tostring());

        # path-style, no bucket and key, only bucket,  bucket and key
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUsePathStyle(true);
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://oss-cn-beijing.aliyuncs.com/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://oss-cn-beijing.aliyuncs.com/my-bucket/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://oss-cn-beijing.aliyuncs.com/my-bucket/123/321/%2B%3F%20/123.txt', $request->getUri()->__tostring());

        # ip format, no bucket and key, only bucket,  bucket and key
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setEndpoint('192.168.1.1:3218');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setUsePathStyle(true);
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://192.168.1.1:3218/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://192.168.1.1:3218/my-bucket/', $request->getUri()->__tostring());

        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://192.168.1.1:3218/my-bucket/123/321/%2B%3F%20/123.txt', $request->getUri()->__tostring());

        // uri format after signing
        # signing header, bucket and key
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertEquals('https://my-bucket.oss-cn-beijing.aliyuncs.com/123/321/%2B%3F%20/123.txt', $request->getUri()->__tostring());

        # signing query, bucket and key
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $mock = new GuzzleHttp\Handler\MockHandler([new GuzzleHttp\Psr7\Response()]);
        $client = new ClientImpl($cfg, ['handler' => $mock, 'auth_method' => 'query']);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('123/321/+? /123.txt');
        $client->executeAsync($input)->wait();
        $request = $mock->getLastRequest();
        $this->assertStringContainsString('https://my-bucket.oss-cn-beijing.aliyuncs.com/123/321/%2B%3F%20/123.txt?x-oss-credential=ak', $request->getUri()->__tostring());
    }

    public function testHttpErrorsHandler(): void
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $cfg->setRetryer(new Retry\NopRetryer());

        $body = '<?xml version="1.0" encoding="UTF-8"?>
            <Error>
                <Code>InvalidAccessKeyId</Code>
                <Message>The OSS Access Key Id you provided does not exist in our records.</Message>
                <RequestId>id-1234</RequestId>
                <HostId>oss-cn-hangzhou.aliyuncs.com</HostId>
                <OSSAccessKeyId>ak</OSSAccessKeyId>
                <EC>0002-00000902</EC>
                <RecommendDoc>https://api.aliyun.com/troubleshoot?q=0002-00000902</RecommendDoc>
            </Error>
        ';

        $response = new GuzzleHttp\Psr7\Response(403, [], $body);
        $mock = new GuzzleHttp\Handler\MockHandler([$response]);
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals('The OSS Access Key Id you provided does not exist in our records.', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
                $this->assertEquals('0002-00000902', $se->getEC());
                $this->assertEquals('oss-cn-hangzhou.aliyuncs.com', $se->getErrorField('HostId'));
                $this->assertEquals('ak', $se->getErrorField('OSSAccessKeyId'));
                $this->assertEquals('https://api.aliyun.com/troubleshoot?q=0002-00000902', $se->getErrorField('RecommendDoc'));
                $this->assertEquals($body, $se->getSnapshot());
                $this->assertEquals('PUT https://bucket.oss-cn-beijing.aliyuncs.com/key0123/321/%2B%3F/123.txt', $se->getRequestTarget());
            }
        }

        // invalid xml
        $invalidBody = 'Error>
                <Code>InvalidAccessKeyId</Code>
                <Message>The OSS Access Key Id you provided does not exist in our records.</Message>
                <RequestId>id-1234</RequestId>
                <HostId>oss-cn-hangzhou.aliyuncs.com</HostId>
                <OSSAccessKeyId>ak</OSSAccessKeyId>
                <EC>0002-00000902</EC>
                <RecommendDoc>https://api.aliyun.com/troubleshoot?q=0002-00000902</RecommendDoc>
            </Error>
        ';

        $response = new GuzzleHttp\Psr7\Response(403, [], $invalidBody);
        $mock->append($response);
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('BadErrorResponse', $se->getErrorCode());
                $this->assertStringContainsString('Not found tag <Error>, part response body Error>', $se->getErrorMessage());
                $this->assertEquals('', $se->getRequestId());
                $this->assertEquals('', $se->getEC());
                $this->assertEquals($invalidBody, $se->getSnapshot());
                $this->assertEquals('PUT https://bucket.oss-cn-beijing.aliyuncs.com/key0123/321/%2B%3F/123.txt', $se->getRequestTarget());
                $this->assertEquals([], $se->getErrorFields());
                $this->assertEquals('', $se->getErrorField('123'));
                $this->assertEquals(null, $se->getTimestamp());
                $this->assertEquals([], $se->getHeaders());
                $this->assertEquals('', $se->getHeader('123'));
            }
        }

        // special char
        $specialCharBody = '<?xml version="1.0" encoding="UTF-8"?>
            <Error>
                <Code>InvalidAccessKeyId</Code>
                <Message>The OSS Access Key Id you provided does not exist in our records.</Message>
                <RequestId>id-1234</RequestId>
                <HostId>oss-cn-hangzhou.aliyuncs.com</HostId>
                <OSSAccessKeyId>ak</OSSAccessKeyId>
                <EC>0002-00000902</EC>
                <SpecialChar>&#1;&#2;</SpecialChar>
                <RecommendDoc>https://api.aliyun.com/troubleshoot?q=0002-00000902</RecommendDoc>
            </Error>
        ';

        $response = new GuzzleHttp\Psr7\Response(403, [], $specialCharBody);
        $mock->append($response);
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertStringContainsString('The OSS Access Key Id you provided does not exist in our records.', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
                $this->assertEquals('0002-00000902', $se->getEC());
                $this->assertEquals($specialCharBody, $se->getSnapshot());
                $this->assertEquals('PUT https://bucket.oss-cn-beijing.aliyuncs.com/key0123/321/%2B%3F/123.txt', $se->getRequestTarget());
                $this->assertEquals([], $se->getErrorFields());
                $this->assertEquals('', $se->getErrorField('123'));
                $this->assertEquals(null, $se->getTimestamp());
                $this->assertEquals([], $se->getHeaders());
                $this->assertEquals('', $se->getHeader('123'));
            }
        }

        // special char + not oss error format
        $specialCharBody = '<?xml version="1.0" encoding="UTF-8"?>
            <Error>
                <Id>InvalidAccessKeyId</Id>
                <Text>InvalidAccessKeyId</Text>
                <SpecialChar>&#1;&#2;</SpecialChar>
            </Error>
        ';

        $response = new GuzzleHttp\Psr7\Response(403, [], $specialCharBody);
        $mock->append($response);
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('BadErrorResponse', $se->getErrorCode());
                $this->assertStringContainsString('Failed to parse xml from response body', $se->getErrorMessage());
                $this->assertEquals('', $se->getRequestId());
                $this->assertEquals('', $se->getEC());
                $this->assertEquals($specialCharBody, $se->getSnapshot());
                $this->assertEquals('PUT https://bucket.oss-cn-beijing.aliyuncs.com/key0123/321/%2B%3F/123.txt', $se->getRequestTarget());
                $this->assertEquals([], $se->getErrorFields());
                $this->assertEquals('', $se->getErrorField('123'));
                $this->assertEquals(null, $se->getTimestamp());
                $this->assertEquals([], $se->getHeaders());
                $this->assertEquals('', $se->getHeader('123'));
            }
        }
    }

    public function testRetryMiddleware(): void
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $body = '<?xml version="1.0" encoding="UTF-8"?>
            <Error>
                <Code>InternalError</Code>
                <Message>Please contact the server administrator, oss@service.aliyun.com</Message>
                <RequestId>id-1234</RequestId>
                <HostId>oss-cn-hangzhou.aliyuncs.com</HostId>
                <EC>0002-00000902</EC>
            </Error>
        ';

        $mock = new GuzzleHttp\Handler\MockHandler();
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('key0123/321/+?/123.txt');
        // default retry count is 3
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(500, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(0, $mock->count());

        // set from api
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(500, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        try {
            $opt = ['retry_max_attempts' => 2,];
            $client->executeAsync($input, $opt)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 2, $mock->count());

        // no retry
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(500, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        try {
            $opt = ['retryer' => new Retry\NopRetryer()];
            $client->executeAsync($input, $opt)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 1, $mock->count());

        // no seekable stream
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(500, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody(new GuzzleHttp\Psr7\NoSeekStream(Utils::streamFor('hello world')));
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 1, $mock->count());

        // No retryable error
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(403, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody(new GuzzleHttp\Psr7\NoSeekStream(Utils::streamFor('hello world')));
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 1, $mock->count());

        // No rewind stream error
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(500, [], $body));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody(new NoRewindStream(Utils::streamFor('hello world')));
        try {
            $client->executeAsync($input)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\StreamRewindException::class, $e->getPrevious());
            $this->assertStringContainsString('Cannot rewind a NoRewindStream', $e->getPrevious()->getMessage());
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious()->getPrevious());
            $se = $e->getPrevious()->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 1, $mock->count());

        // InconsistentExecption Error
        $mock->reset();
        for ($x = 0; $x < Defaults::MAX_ATTEMPTS; $x++) {
            $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-1234']));
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS, $mock->count());
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('my-bucket');
        $input->setKey('key0123/321/+?/123.txt');
        try {
            $opt = [
                'response_handlers' => [
                    static function ($request, $response, $options) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    },
                ],
            ];
            $client->executeAsync($input, $opt)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\InconsistentExecption::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\InconsistentExecption) {
                $this->assertEquals('crc is inconsistent, client crc: 1, server crc: 2, request id: id-1234', $se->getMessage());
            }
        }
        $this->assertEquals(0, $mock->count());

        // rewind check, set retry count 3, 500 error, crc error, normal
        $mock->reset();
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1']));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-2', 'x-oss-crc-fail' => 'true']));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-3']));
        $this->assertEquals(3, $mock->count());
        $body = new RewindStatStream(Utils::streamFor('hello world'));
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody($body);
        $opt = [
            'response_handlers' => [
                static function ($request, \Psr\Http\Message\ResponseInterface $response, $options) {
                    if ($response->hasHeader('x-oss-crc-fail')) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    }
                },
            ],
            'retry_max_attempts' => 3,
        ];
        $output = $client->executeAsync($input, $opt)->wait();
        $this->assertInstanceOf(OperationOutput::class, $output);
        $this->assertEquals(0, $mock->count());
        $this->assertEquals(2, $body->getRewindCount());
    }

    public function testRetryMiddlewareAndSinkOption(): void
    {
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-beijing');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());

        $errBody = '<?xml version="1.0" encoding="UTF-8"?>
            <Error>
                <Code>InternalError</Code>
                <Message>Please contact the server administrator, oss@service.aliyun.com</Message>
                <RequestId>id-1234</RequestId>
                <HostId>oss-cn-hangzhou.aliyuncs.com</HostId>
                <EC>0002-00000902</EC>
            </Error>
        ';

        $mock = new GuzzleHttp\Handler\MockHandler();
        $client = new ClientImpl($cfg, ['handler' => $mock]);
        $respBody = 'respons body';

        // rewind check, set retry count 3, 500 error, crc error, normal
        $mock->reset();
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-2', 'x-oss-crc-fail' => 'true'], $respBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-3'], $respBody));
        $this->assertEquals(3, $mock->count());
        $body = new RewindStatStream(Utils::streamFor('hello world'));
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody($body);
        $opt = [
            'response_handlers' => [
                static function ($request, \Psr\Http\Message\ResponseInterface $response, $options) {
                    if ($response->hasHeader('x-oss-crc-fail')) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    }
                },
            ],
            'retry_max_attempts' => 3,
        ];
        $output = $client->executeAsync($input, $opt)->wait();
        $this->assertInstanceOf(OperationOutput::class, $output);
        $this->assertEquals(0, $mock->count());
        $this->assertEquals(2, $body->getRewindCount());
        $content = $output->getBody()->getContents();
        $this->assertEquals($respBody, $content);

        //use no seek sink
        $mock->reset();
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-2', 'x-oss-crc-fail' => 'true'], $respBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-3'], $respBody));
        $this->assertEquals(3, $mock->count());
        $body = new RewindStatStream(Utils::streamFor('hello world'));
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody($body);
        $opt = [
            'response_handlers' => [
                static function ($request, \Psr\Http\Message\ResponseInterface $response, $options) {
                    if ($response->hasHeader('x-oss-crc-fail')) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    }
                },
            ],
            'retry_max_attempts' => 3,
            'request_options' => [
                'sink' => new \GuzzleHttp\Psr7\BufferStream()
            ],
        ];

        try {
            $client->executeAsync($input, $opt)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(Defaults::MAX_ATTEMPTS - 1, $mock->count());

        //use seek sink
        $mock->reset();
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-2', 'x-oss-crc-fail' => 'true'], $respBody));
        $mock->append(new GuzzleHttp\Psr7\Response(200, ['x-oss-request-id' => 'id-3'], $respBody));
        $this->assertEquals(3, $mock->count());
        $body = new RewindStatStream(Utils::streamFor('hello world'));
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody($body);
        $sink = \GuzzleHttp\Psr7\Utils::tryFopen('php://temp', 'w+');
        $sink = \GuzzleHttp\Psr7\Utils::streamFor($sink);
        $opt = [
            'response_handlers' => [
                static function ($request, \Psr\Http\Message\ResponseInterface $response, $options) {
                    if ($response->hasHeader('x-oss-crc-fail')) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    }
                },
            ],
            'retry_max_attempts' => 3,
            'request_options' => [
                'sink' => $sink
            ],
        ];
        $output = $client->executeAsync($input, $opt)->wait();
        $this->assertInstanceOf(OperationOutput::class, $output);
        $this->assertEquals(0, $mock->count());
        $this->assertEquals(2, $body->getRewindCount());
        $content = $output->getBody()->getContents();
        $this->assertEquals('', $content);
        $sink->seek(0);
        $sinkContent = $sink->getContents();
        $this->assertEquals($respBody, $sinkContent);
        $this->assertEquals(\gettype($sink), \gettype($output->getBody()));
        $this->assertNotInstanceOf(SinkStreamWrapper::class, $output->getBody());

        //use seek sink and fail
        $mock->reset();
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $mock->append(new GuzzleHttp\Psr7\Response(500, ['x-oss-request-id' => 'id-1'], $errBody));
        $this->assertEquals(3, $mock->count());
        $body = new RewindStatStream(Utils::streamFor('hello world'));
        $input = new OperationInput(
            "TestApi",
            "PUT"
        );
        $input->setBucket('bucket');
        $input->setKey('key0123/321/+?/123.txt');
        $input->setBody($body);
        $sink = \GuzzleHttp\Psr7\Utils::tryFopen('php://temp', 'w+');
        $sink = \GuzzleHttp\Psr7\Utils::streamFor($sink);
        $opt = [
            'response_handlers' => [
                static function ($request, \Psr\Http\Message\ResponseInterface $response, $options) {
                    if ($response->hasHeader('x-oss-crc-fail')) {
                        throw new Exception\InconsistentExecption('1', '2', $response);
                    }
                },
            ],
            'retry_max_attempts' => 3,
            'request_options' => [
                'sink' => $sink
            ],
        ];
        try {
            $client->executeAsync($input, $opt)->wait();
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Exception\OperationException::class, $e);
            $this->assertInstanceOf(Exception\ServiceException::class, $e->getPrevious());
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $this->assertEquals('InternalError', $se->getErrorCode());
                $this->assertStringContainsString('Please contact the server administrator, oss@service.aliyun.com', $se->getErrorMessage());
                $this->assertEquals('id-1234', $se->getRequestId());
            }
        }
        $this->assertEquals(0, $mock->count());
        $sink->seek(0);
        $sinkContent = $sink->getContents();
        $this->assertEquals('', $sinkContent);
    }

    public function testPresignInner()
    {
        // Presign Options
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);
        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=', $result['url']);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $expires = 'PT50M';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval($expires));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v4');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $nowTime = clone $now;
        $expiration = $nowTime->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $expectedDate = $expiration->modify('-1 hour')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3600', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $expires = 'PT50M';
        $nowTime = clone $now;
        $expiration = $nowTime->add(new DateInterval($expires));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $expectedDate = $expiration->modify('-50 minutes')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3000', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);

        // token
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk', 'token'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=', $result['url']);
        $this->assertStringContainsString('security-token=token', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk', 'token'));
        $cfg->setSignatureVersion('v4');
        $client = new ClientImpl($cfg);
        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals([], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('x-oss-security-token=token', $result['url']);
        $expectedDate = $expiration->modify('-1 hour')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3600', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setHeader('Content-Type', 'application/octet-stream');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals('application/octet-stream', $result['signedHeaders']['content-type']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v4');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setHeader('Content-Type', 'application/octet-stream');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals(['content-type' => 'application/octet-stream'], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $expectedDate = $expiration->modify('-1 hour')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3600', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);

        // with query
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setParameter('x-oss-process', 'abc');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=', $result['url']);
        $this->assertStringContainsString('x-oss-process=abc', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v4');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setParameter('x-oss-process', 'abc');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $expectedDate = $expiration->modify('-1 hour')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3600', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);
        $this->assertStringContainsString('x-oss-process=abc', $result['url']);

        // with version id
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);

        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setParameter('versionId', 'versionId');
        $arg['auth_method'] = 'query';
        $expiration = new \DateTime('Sun, 12 Nov 2023 16:43:40 GMT', new \DateTimeZone('UTC'));

        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=dcLTea%2BYh9ApirQ8o8dOPqtvJXQ%3D', $result['url']);
        $this->assertStringContainsString('versionId=versionId', $result['url']);

        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk', 'token'));
        $cfg->setSignatureVersion('v1');
        $client = new ClientImpl($cfg);
        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key+123');
        $input->setParameter('versionId', 'versionId');
        $arg['auth_method'] = 'query';
        $expiration = new \DateTime('Sun, 12 Nov 2023 16:56:44 GMT');
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key%2B123?', $result['url']);
        $this->assertStringContainsString('OSSAccessKeyId=ak', $result['url']);
        $this->assertStringContainsString(sprintf('Expires=%s', $expiration->getTimestamp()), $result['url']);
        $this->assertStringContainsString('Signature=jzKYRrM5y6Br0dRFPaTGOsbrDhY%3D', $result['url']);
        $this->assertStringContainsString('security-token=token', $result['url']);
        $this->assertStringContainsString('versionId=versionId', $result['url']);

        // with add
        $cfg = Config::loadDefault();
        $cfg->setRegion('cn-hangzhou');
        $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
        $cfg->setSignatureVersion('v4');
        $cfg->setAdditionalHeaders(['ZAbc', 'abc']);
        $client = new ClientImpl($cfg);
        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket('bucket');
        $input->setKey('key');
        $input->setHeader('ZAbc', 'value');
        $input->setHeader('abc', 'value');
        $arg['auth_method'] = 'query';
        $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
        $input->setOpMetadata('expiration_time', $expiration);
        $result = $client->presignInner($input, $arg);
        $this->assertEquals('GET', $result['method']);
        $this->assertEquals($expiration, $result['expiration']);
        $this->assertEquals(['zabc' => 'value', 'abc' => 'value'], $result['signedHeaders']);
        $this->assertStringContainsString('bucket.oss-cn-hangzhou.aliyuncs.com/key?', $result['url']);
        $expectedDate = $expiration->modify('-1 hour')->format('Ymd');
        $this->assertStringContainsString("x-oss-date={$expectedDate}", $result['url']);
        $this->assertStringContainsString('x-oss-expires=3600', $result['url']);
        $this->assertStringContainsString('x-oss-signature=', $result['url']);
        $credential = urlencode("ak/{$expectedDate}/cn-hangzhou/oss/aliyun_v4_request");
        $this->assertStringContainsString("x-oss-credential={$credential}", $result['url']);
        $this->assertStringContainsString('x-oss-signature-version=OSS4-HMAC-SHA256', $result['url']);
        $this->assertStringContainsString('x-oss-additional-headers=abc%3Bzabc', $result['url']);
    }

    public function testPresignInnerError()
    {
        try {
            $cfg = Config::loadDefault();
            $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('endpoint is invalid.', $e->getMessage());
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new ErrorProvider());
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket!@!@$');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Bucket name is invalid, got bucket!@!@$', $e->getMessage());
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey(random_bytes(1035));
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Object name is invalid, got', $e->getMessage());
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new ErrorProvider());
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Fetch Credentials raised an exception', $e->getMessage());
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT192H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
        } catch (\Throwable $e) {
            $this->assertTrue(false, 'should not here');
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('ak', 'sk'));
            $cfg->setSignatureVersion('v4');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT192H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Expires should not be greater than 604800 (seven days)', $e->getMessage());
        }

        try {
            $cfg = Config::loadDefault();
            $cfg->setRegion('cn-hangzhou');
            $cfg->setCredentialsProvider(new Credentials\StaticCredentialsProvider('', ''));
            $cfg->setSignatureVersion('v1');
            $client = new ClientImpl($cfg);
            $input = new OperationInput(
                'GetObject',
                'GET',
            );
            $input->setBucket('bucket');
            $input->setKey('key');
            $arg['auth_method'] = 'query';
            $expiration = (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT1H'));
            $input->setOpMetadata('expiration_time', $expiration);
            $result = $client->presignInner($input, $arg);
            $this->assertTrue(false, 'should not here');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('Credentials is null or empty.', $e->getMessage());
        }
    }

    public function testConfigWithCloudBoxId()
    {
        // set cloud box id
        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('test-endpoint');
        $cfg->setCloudBoxId('test-cloudbox-id');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(Defaults::CLOUD_BOX_PRODUCT, $sdkOptions['product']);
        $this->assertEquals('test-cloudbox-id', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('test-endpoint', $sdkOptions['endpoint']->getAuthority());
        $this->assertEquals('test-cloudbox-id', $cfg->getCloudBoxId());
        $this->assertEquals(null, $cfg->getEnableAutoDetectCloudBoxId());

        // cb-***.{region}.oss-cloudbox-control.aliyuncs.com
        // cb-***.{region}.oss-cloudbox.aliyuncs.com

        // auto detect cloud box id default
        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.test-region.oss-cloudbox-control.aliyuncs.com');
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss', $sdkOptions['product']);
        $this->assertEquals('test-region', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.test-region.oss-cloudbox-control.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // auto detect cloudbox id set false
        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.test-region.oss-cloudbox-control.aliyuncs.com');
        $cfg->setEnableAutoDetectCloudBoxId(false);
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss', $sdkOptions['product']);
        $this->assertEquals('test-region', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.test-region.oss-cloudbox-control.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // auto detect cloudbox id set true
        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.test-region.oss-cloudbox-control.aliyuncs.com');
        $cfg->setEnableAutoDetectCloudBoxId(true);
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(Defaults::CLOUD_BOX_PRODUCT, $sdkOptions['product']);
        $this->assertEquals('cb-123', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.test-region.oss-cloudbox-control.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.test-region.oss-cloudbox.aliyuncs.com');
        $cfg->setEnableAutoDetectCloudBoxId(true);
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals(Defaults::CLOUD_BOX_PRODUCT, $sdkOptions['product']);
        $this->assertEquals('cb-123', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.test-region.oss-cloudbox.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        // auto detect cloudbox id set true + non cloud box endpoint
        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.test-region.oss.aliyuncs.com');
        $cfg->setEnableAutoDetectCloudBoxId(true);
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss', $sdkOptions['product']);
        $this->assertEquals('test-region', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.test-region.oss.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());

        $cfg = Config::loadDefault();
        $cfg->setRegion('test-region');
        $cfg->setEndpoint('cb-123.oss-cloudbox.aliyuncs.com');
        $cfg->setEnableAutoDetectCloudBoxId(true);
        $cfg->setCredentialsProvider(new Credentials\AnonymousCredentialsProvider());
        $client = new ClientImpl($cfg);
        $ro = new \ReflectionObject($client);
        $pSdkOptions = $ro->getProperty('sdkOptions');
        $pInnerOptions = $ro->getProperty('innerOptions');
        $pRequestOptions = $ro->getProperty('requestOptions');
        if (PHP_VERSION_ID < 80100) {
            $pSdkOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pInnerOptions->setAccessible(true);
        }
        if (PHP_VERSION_ID < 80100) {
            $pRequestOptions->setAccessible(true);
        }
        $sdkOptions = $pSdkOptions->getValue($client);
        $this->assertEquals('oss', $sdkOptions['product']);
        $this->assertEquals('test-region', $sdkOptions['region']);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Uri::class, $sdkOptions['endpoint']);
        $this->assertEquals('https', $sdkOptions['endpoint']->getScheme());
        $this->assertEquals('cb-123.oss-cloudbox.aliyuncs.com', $sdkOptions['endpoint']->getAuthority());
    }
}

class ErrorProvider implements CredentialsProvider
{
    public function getCredentials(): \AlibabaCloud\Oss\V2\Credentials\Credentials
    {
        throw new \Exception('Simulated exception');

    }
}
