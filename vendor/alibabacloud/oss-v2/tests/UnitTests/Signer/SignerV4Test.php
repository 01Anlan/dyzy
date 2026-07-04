<?php

namespace UnitTests\Signer;

use AlibabaCloud\Oss\V2\Credentials\StaticCredentialsProvider;
use AlibabaCloud\Oss\V2\Defaults;
use AlibabaCloud\Oss\V2\Signer\SignerV4;
use AlibabaCloud\Oss\V2\Signer\SigningContext;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Psr7\Request;

class SignerV4Test extends \PHPUnit\Framework\TestCase
{

    public function testAuthHeader()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702743657");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231216/cn-hangzhou/oss/aliyun_v4_request,Signature=e21d18daa82167720f9b1047ae7e7f1ce7cb77a31e8203a7d5f4624fa0284afe', $request->getHeaderLine("Authorization"));

        // case 2
        $query = array();
        $request = new Request("POST", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702743657");
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'object';
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['position'] = 0;
        $query['append'] = "";
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231216/cn-hangzhou/oss/aliyun_v4_request,Signature=f7a645f23a0434ea90d5e2bb2bca90d0fda9ef4bc54a9c5008b2134e7c9023f2', $request->getHeaderLine("Authorization"));

        // case 3
        $query = array();
        $request = new Request("POST", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("content-type", "text/plain");
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'object';
        $signCtx->credentials = $cred;
        $signer = new SignerV4();
        $query['position'] = 0;
        $query['append'] = "";
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertStringContainsString('OSS4-HMAC-SHA256', $request->getHeaderLine("Authorization"));
        $date = (new DateTime('now', new DateTimeZone('UTC')))->format("Ymd");
        $this->assertStringContainsString("ak/{$date}/cn-hangzhou/oss/aliyun_v4_request", $request->getHeaderLine("Authorization"));
        $this->assertStringContainsString('Signature=', $request->getHeaderLine("Authorization"));
    }

    public function testAuthHeaderWithCloudBox()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "http://bucket.cb-123.cn-hangzhou.oss-cloudbox.aliyuncs.com/1234+-/123/1.txt");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702743657");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->product = Defaults::CLOUD_BOX_PRODUCT;
        $signCtx->region = 'cb-123';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231216/cb-123/oss-cloudbox/aliyun_v4_request,Signature=94ce1f12c17d148ea681030275a94449d3357f5b5b21133996eec80af3e08a43', $request->getHeaderLine("Authorization"));
    }

    public function testAuthHeaderToken()
    {
        $provider = new StaticCredentialsProvider("ak", "sk", "token");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702784856");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231217/cn-hangzhou/oss/aliyun_v4_request,Signature=b94a3f999cf85bcdc00d332fbd3734ba03e48382c36fa4d5af5df817395bd9ea', $request->getHeaderLine("Authorization"));
    }

    public function testAuthHeaderWithAdditionalHeaders()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702747512");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, additionalHeaders: ["ZAbc", "abc"], credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->request = $request;
        $signCtx->additionalHeaders = ["ZAbc", "abc"];
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231216/cn-hangzhou/oss/aliyun_v4_request,AdditionalHeaders=abc;zabc,Signature=4a4183c187c07c8947db7620deb0a6b38d9fbdd34187b6dbaccb316fa251212f', $request->getHeaderLine("Authorization"));

        // case 2:with default signed header
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "text/plain")
            ->withHeader("x-oss-content-sha256", "UNSIGNED-PAYLOAD");
        $signTime = new DateTime("@1702747512");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, additionalHeaders: ["x-oss-no-exist", "ZAbc", "x-oss-head1", "abc"], credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->request = $request;
        $signCtx->additionalHeaders = ["x-oss-no-exist", "ZAbc", "x-oss-head1", "abc"];
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $this->assertEquals('OSS4-HMAC-SHA256 Credential=ak/20231216/cn-hangzhou/oss/aliyun_v4_request,AdditionalHeaders=abc;zabc,Signature=4a4183c187c07c8947db7620deb0a6b38d9fbdd34187b6dbaccb316fa251212f', $request->getHeaderLine("Authorization"));
    }

    public function testInvalidArgument()
    {
        try {
            $signer = new SignerV4();
            $signer->sign(null);
        } catch (\TypeError $e) {
            $this->assertStringContainsString("AlibabaCloud\Oss\V2\Signer\SigningContext, null given", $e->getMessage());
        }

        try {
            $signCtx = new SigningContext();
            $signer = new SignerV4();
            $signer->sign($signCtx);
        } catch (\Exception $e) {
            $this->assertSame("SigningContext Credentials is null or empty.", $e->getMessage());
        }

        try {
            $provider = new StaticCredentialsProvider("", "sk");
            $cred = $provider->getCredentials();
            #$signCtx = new SigningContext(credentials: $cred);
            $signCtx = new SigningContext();
            $signCtx->credentials = $cred;
            $signer = new SignerV4();
            $signer->sign($signCtx);
        } catch (\Exception $e) {
            $this->assertSame("SigningContext Credentials is null or empty.", $e->getMessage());
        }

        try {
            $provider = new StaticCredentialsProvider("ak", "sk");
            $cred = $provider->getCredentials();
            #$signCtx = new SigningContext(credentials: $cred);
            $signCtx = new SigningContext();
            $signCtx->credentials = $cred;
            $signer = new SignerV4();
            $signer->sign($signCtx);
        } catch (\Exception $e) {
            $this->assertSame("SigningContext Region is empty.", $e->getMessage());
        }

        try {
            $provider = new StaticCredentialsProvider("ak", "sk");
            $cred = $provider->getCredentials();
            #$signCtx = new SigningContext(credentials: $cred, region: 'cn-hangzhou');
            $signCtx = new SigningContext();
            $signCtx->credentials = $cred;
            $signCtx->region = 'cn-hangzhou';
            $signer = new SignerV4();
            $signer->sign($signCtx);
        } catch (\Exception $e) {
            $this->assertSame("SigningContext Request is null.", $e->getMessage());
        }
    }

    public function testAuthQuery()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702781677");
        $time = new DateTime("@1702782276");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cn-hangzhou/oss/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('a39966c61718be0d5b14e668088b3fa07601033f6518ac7b523100014269c0fe', $parsedQuery['x-oss-signature']);
        $this->assertFalse(array_key_exists('x-oss-additional-headers', $parsedQuery));

        // case 2
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->authMethodQuery = true;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $date = (new DateTime('now', new DateTimeZone('UTC')))->format("Ymd");
        $this->assertEquals('900', $parsedQuery['x-oss-expires']);
        $this->assertEquals("ak/{$date}/cn-hangzhou/oss/aliyun_v4_request", $parsedQuery['x-oss-credential']);
        $this->assertNotEmpty($parsedQuery['x-oss-signature']);
        $this->assertFalse(array_key_exists('x-oss-additional-headers', $parsedQuery));


    }

    public function testAuthQueryToken()
    {
        $provider = new StaticCredentialsProvider("ak", "sk", "token");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702785388");
        $time = new DateTime("@1702785987");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cn-hangzhou/oss/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('3817ac9d206cd6dfc90f1c09c00be45005602e55898f26f5ddb06d7892e1f8b5', $parsedQuery['x-oss-signature']);
        $this->assertFalse(array_key_exists('x-oss-additional-headers', $parsedQuery));
    }

    public function testAuthQueryWithAdditionalHeaders()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702783809");
        $time = new DateTime("@1702784408");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, additionalHeaders: ["ZAbc", "abc"], credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->additionalHeaders = ["ZAbc", "abc"];
        $signCtx->credentials = $cred;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query, '', '&', PHP_QUERY_RFC3986)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cn-hangzhou/oss/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('6bd984bfe531afb6db1f7550983a741b103a8c58e5e14f83ea474c2322dfa2b7', $parsedQuery['x-oss-signature']);
        $this->assertEquals('abc;zabc', $parsedQuery['x-oss-additional-headers']);

        // case 2:with default signed header
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702783809");
        $time = new DateTime("@1702784408");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, additionalHeaders: ["x-oss-no-exist", "abc", "x-oss-head1", "ZAbc"], credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->additionalHeaders = ["x-oss-no-exist", "abc", "x-oss-head1", "ZAbc"];
        $signCtx->credentials = $cred;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query, '', '&', PHP_QUERY_RFC3986)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('20231217T033009Z', $parsedQuery['x-oss-date']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cn-hangzhou/oss/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('6bd984bfe531afb6db1f7550983a741b103a8c58e5e14f83ea474c2322dfa2b7', $parsedQuery['x-oss-signature']);
        $this->assertEquals('abc;zabc', $parsedQuery['x-oss-additional-headers']);

        // case 3
        $request = new Request("PUT", "https://bucket.oss-cn-hangzhou.aliyuncs.com/key 123.txt");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702783809");
        $time = new DateTime("@1702784408");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "key 123.txt", request: $request, additionalHeaders: ["ZAbc", "abc"], credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = 'oss';
        $signCtx->region = 'cn-hangzhou';
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'key 123.txt';
        $signCtx->additionalHeaders = ["ZAbc", "abc"];
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query, '', '&', PHP_QUERY_RFC3986)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $uri = $request->getUri();
        $signUrl = "https://bucket.oss-cn-hangzhou.aliyuncs.com/key%20123.txt?%2Bparam1=value3&%2Bparam2=&param1=value1&param2=&x-oss-additional-headers=abc%3Bzabc&x-oss-credential=ak%2F20231217%2Fcn-hangzhou%2Foss%2Faliyun_v4_request&x-oss-date=20231217T033009Z&x-oss-expires=599&x-oss-signature=33208021567953241c3cc1d95ecf1864f8561890c30d29488ce76c7afb81a623&x-oss-signature-version=OSS4-HMAC-SHA256&%7Cparam1=value4&%7Cparam2=";
        $this->assertEquals($signUrl, (string)$uri);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cn-hangzhou/oss/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('33208021567953241c3cc1d95ecf1864f8561890c30d29488ce76c7afb81a623', $parsedQuery['x-oss-signature']);
        $this->assertEquals('abc;zabc', $parsedQuery['x-oss-additional-headers']);
    }

    public function testAuthQueryWithCloudBox()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "http://bucket.cb-123.cn-hangzhou.oss-cloudbox.aliyuncs.com/1234+-/123/1.txt");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702781677");
        $time = new DateTime("@1702782276");
        #$signCtx = new SigningContext(product: "oss", region: "cn-hangzhou", bucket: "bucket", key: "1234+-/123/1.txt", request: $request, credentials: $cred, time: $time, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->product = Defaults::CLOUD_BOX_PRODUCT;
        $signCtx->region = 'cb-123';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->time = $time;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signCtx->additionalHeaders = ["ZAbc", "abc"];
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cb-123/oss-cloudbox/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('07284191b9b4978ac3520cd39ee2dea2747eda454089359371ff463a6c7ba20f', $parsedQuery['x-oss-signature']);
        $this->assertTrue(array_key_exists('x-oss-additional-headers', $parsedQuery));
        $this->assertEquals('abc;zabc', $parsedQuery['x-oss-additional-headers']);

        // with default signed header
        $request = new Request("PUT", "http://bucket.cb-123.cn-hangzhou.oss-cloudbox.aliyuncs.com/1234+-/123/1.txt");
        $request = $request->withHeader("x-oss-head1", "value")
            ->withHeader("x-oss-head1", "value")
            ->withHeader("abc", "value")
            ->withHeader("ZAbc", "value")
            ->withHeader("XYZ", "value")
            ->withHeader("content-type", "application/octet-stream");
        $signTime = new DateTime("@1702783809");
        $time = new DateTime("@1702784408");
        $signCtx = new SigningContext();
        $signCtx->product = 'oss-cloudbox';
        $signCtx->region = 'cb-123';
        $signCtx->bucket = 'bucket';
        $signCtx->key = '1234+-/123/1.txt';
        $signCtx->credentials = $cred;
        $signCtx->authMethodQuery = true;
        $signCtx->signTime = $signTime;
        $signCtx->time = $time;
        $signCtx->additionalHeaders = ["x-oss-no-exist", "abc", "x-oss-head1", "ZAbc"];
        $signer = new SignerV4();
        $query['param1'] = 'value1';
        $query['+param1'] = 'value3';
        $query['|param1'] = 'value4';
        $query['+param2'] = '';
        $query['|param2'] = '';
        $query['param2'] = '';
        $signCtx->request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
        $signer->sign($signCtx);
        $request = $signCtx->request;
        $queryParams = $request->getUri()->getQuery();
        parse_str($queryParams, $parsedQuery);
        $this->assertEquals('OSS4-HMAC-SHA256', $parsedQuery['x-oss-signature-version']);
        $this->assertEquals('599', $parsedQuery['x-oss-expires']);
        $this->assertEquals('ak/20231217/cb-123/oss-cloudbox/aliyun_v4_request', $parsedQuery['x-oss-credential']);
        $this->assertEquals('16782cc8a7a554523db055eb804b508522e7e370073108ad88ee2f47496701dd', $parsedQuery['x-oss-signature']);
        $this->assertTrue(array_key_exists('x-oss-additional-headers', $parsedQuery));
        $this->assertEquals('abc;zabc', $parsedQuery['x-oss-additional-headers']);


    }
}
