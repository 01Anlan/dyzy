<?php

namespace UnitTests\Signer;

use AlibabaCloud\Oss\V2\Credentials\StaticCredentialsProvider;
use AlibabaCloud\Oss\V2\Signer\SignerV1;
use AlibabaCloud\Oss\V2\Signer\SigningContext;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Psr7\Request;

class SignerV1Test extends \PHPUnit\Framework\TestCase
{

    public function testAuthHeader()
    {
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        // case 1
        $request = new Request("PUT", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com");
        $request = $request->withHeader("Content-MD5", "eB5eJF1ptWaXm4bijSPyxw==")
            ->withHeader("Content-Type", "text/html")
            ->withHeader("x-oss-meta-author", "alice")
            ->withHeader("x-oss-meta-magic", "abracadabra")
            ->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", key: "nelson", request: $request, credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->key = 'nelson';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signToString = "PUT\neB5eJF1ptWaXm4bijSPyxw==\ntext/html\nWed, 28 Dec 2022 10:27:41 GMT\nx-oss-date:Wed, 28 Dec 2022 10:27:41 GMT\nx-oss-meta-author:alice\nx-oss-meta-magic:abracadabra\n/examplebucket/nelson";
        $this->assertEquals($signToString, $signCtx->stringToSign);
        $this->assertEquals($signTime, $signCtx->time);
        $request = $signCtx->request;
        $this->assertEquals("OSS ak:kSHKmLxlyEAKtZPkJhG9bZb5k7M=", $request->getHeaderLine("Authorization"));

        // case 2
        $request = new Request("PUT", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com/?acl");
        $request = $request->withHeader("Content-MD5", "eB5eJF1ptWaXm4bijSPyxw==")
            ->withHeader("Content-Type", "text/html")
            ->withHeader("x-oss-meta-author", "alice")
            ->withHeader("x-oss-meta-magic", "abracadabra")
            ->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", key: "nelson", request: $request, credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->key = 'nelson';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signToString = "PUT\neB5eJF1ptWaXm4bijSPyxw==\ntext/html\nWed, 28 Dec 2022 10:27:41 GMT\nx-oss-date:Wed, 28 Dec 2022 10:27:41 GMT\nx-oss-meta-author:alice\nx-oss-meta-magic:abracadabra\n/examplebucket/nelson?acl";
        $this->assertEquals($signToString, $signCtx->stringToSign);
        $this->assertEquals($signTime, $signCtx->time);
        $request = $signCtx->request;
        $this->assertEquals("OSS ak:/afkugFbmWDQ967j1vr6zygBLQk=", $request->getHeaderLine("Authorization"));

        // case 3
        $request = new Request("GET", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com/?resourceGroup&non-resousce=null");
        $request = $request->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", request: $request, subResource: ["resourceGroup"], credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->request = $request;
        $signCtx->subResource = ["resourceGroup"];
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signToString = "GET\n\n\nWed, 28 Dec 2022 10:27:41 GMT\nx-oss-date:Wed, 28 Dec 2022 10:27:41 GMT\n/examplebucket/?resourceGroup";
        $this->assertEquals($signToString, $signCtx->stringToSign);
        $this->assertEquals($signTime, $signCtx->time);
        $request = $signCtx->request;
        $this->assertEquals("OSS ak:vkQmfuUDyi1uDi3bKt67oemssIs=", $request->getHeaderLine("Authorization"));

        // case 4
        $request = new Request("GET", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com/?resourceGroup&acl");
        $request = $request->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", request: $request, subResource: ["resourceGroup"], credentials: $cred, time: $signTime);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->request = $request;
        $signCtx->subResource = ["resourceGroup"];
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signToString = "GET\n\n\nWed, 28 Dec 2022 10:27:41 GMT\nx-oss-date:Wed, 28 Dec 2022 10:27:41 GMT\n/examplebucket/?acl&resourceGroup";
        $this->assertEquals($signToString, $signCtx->stringToSign);
        $this->assertEquals($signTime, $signCtx->time);
        $request = $signCtx->request;
        $this->assertEquals("OSS ak:x3E5TgOvl/i7PN618s5mEvpJDYk=", $request->getHeaderLine("Authorization"));

        // case 5
        $request = new Request("GET", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com/?resourceGroup&acl");
        $request = $request->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", request: $request, subResource: ["resourceGroup"], credentials: $cred);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->request = $request;
        $signCtx->subResource = ["resourceGroup"];
        $signCtx->credentials = $cred;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $this->assertNotNull($signCtx->stringToSign);
        $this->assertNotNull($signCtx->time->format("D, d M Y H:i:s"));
        $request = $signCtx->request;
        $this->assertNotNull($request->getHeaderLine("Authorization"));

        $provider = new StaticCredentialsProvider("ak", "sk", "token");
        $cred = $provider->getCredentials();
        $request = new Request("GET", "https://examplebucket.oss-cn-hangzhou.aliyuncs.com/?resourceGroup&acl");
        $request = $request->withHeader("x-oss-date", "Wed, 28 Dec 2022 10:27:41 GMT");
        #$signCtx = new SigningContext(bucket: "examplebucket", request: $request, subResource: ["resourceGroup"], credentials: $cred);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'examplebucket';
        $signCtx->request = $request;
        $signCtx->subResource = ["resourceGroup"];
        $signCtx->credentials = $cred;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $this->assertNotNull($signCtx->stringToSign);
        $this->assertNotNull($signCtx->time->format("D, d M Y H:i:s"));
        $request = $signCtx->request;
        $this->assertNotNull($request->getHeaderLine("Authorization"));
    }

    public function testInvalidArgument()
    {
        try {
            $signer = new SignerV1();
            $signer->sign(null);
        } catch (\TypeError $e) {
            $this->assertStringContainsString("AlibabaCloud\Oss\V2\Signer\SigningContext, null given", $e->getMessage());
        }

        try {
            $provider = new StaticCredentialsProvider("", "sk");
            $cred = $provider->getCredentials();
            #$signCtx = new SigningContext(credentials: $cred);
            $signCtx = new SigningContext();
            $signCtx->credentials = $cred;
            $signer = new SignerV1();
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
            $signer = new SignerV1();
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
        $request = new Request("GET", "https://bucket.oss-cn-hangzhou.aliyuncs.com/key?versionId=versionId");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Sun, 12 Nov 2023 16:43:40 GMT");
        #$signCtx = new SigningContext(bucket: "bucket", key: "key", request: $request, credentials: $cred, time: $signTime, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'key';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signCtx->authMethodQuery = true;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signUrl = "https://bucket.oss-cn-hangzhou.aliyuncs.com/key?Expires=1699807420&OSSAccessKeyId=ak&Signature=dcLTea%2BYh9ApirQ8o8dOPqtvJXQ%3D&versionId=versionId";
        $request = $signCtx->request;
        $uri = $request->getUri();
        $this->assertEquals($signUrl, (string)$uri);
        $this->assertEquals($signTime, $signCtx->time);

        // case 2
        $provider = new StaticCredentialsProvider("ak", "sk", "token");
        $cred = $provider->getCredentials();
        $request = new Request("GET", "https://bucket.oss-cn-hangzhou.aliyuncs.com/key%2B123?versionId=versionId");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Sun, 12 Nov 2023 16:56:44 GMT");
        #$signCtx = new SigningContext(bucket: "bucket", key: "key+123", request: $request, credentials: $cred, time: $signTime, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'key+123';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signCtx->authMethodQuery = true;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signUrl = "https://bucket.oss-cn-hangzhou.aliyuncs.com/key%2B123?Expires=1699808204&OSSAccessKeyId=ak&Signature=jzKYRrM5y6Br0dRFPaTGOsbrDhY%3D&security-token=token&versionId=versionId";
        $request = $signCtx->request;
        $uri = $request->getUri();
        $this->assertEquals($signUrl, (string)$uri);
        $this->assertEquals($signTime, $signCtx->time);

        // case 3
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        $request = new Request("GET", "https://bucket.oss-cn-hangzhou.aliyuncs.com/key 123");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Sun, 12 Nov 2023 16:43:40 GMT");
        #$signCtx = new SigningContext(bucket: "bucket", key: "key", request: $request, credentials: $cred, time: $signTime, authMethodQuery: true);
        $signCtx = new SigningContext();
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'key';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->time = $signTime;
        $signCtx->authMethodQuery = true;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signUrl = "https://bucket.oss-cn-hangzhou.aliyuncs.com/key%20123?Expires=1699807420&OSSAccessKeyId=ak&Signature=tYKe4BGVXY%2FMIl5F0qSoNfAnpkk%3D";
        $request = $signCtx->request;
        $uri = $request->getUri();
        $this->assertEquals($signUrl, (string)$uri);
        $this->assertEquals($signTime, $signCtx->time);

        // case 4
        $provider = new StaticCredentialsProvider("ak", "sk");
        $cred = $provider->getCredentials();

        $request = new Request("GET", "https://bucket.oss-cn-hangzhou.aliyuncs.com/key 123");
        $signTime = DateTime::createFromFormat("D, d M Y H:i:s T", "Sun, 12 Nov 2023 16:43:40 GMT");
        $signCtx = new SigningContext();
        $signCtx->bucket = 'bucket';
        $signCtx->key = 'key';
        $signCtx->request = $request;
        $signCtx->credentials = $cred;
        $signCtx->authMethodQuery = true;
        $signer = new SignerV1();
        $signer->sign($signCtx);
        $signTime = (new DateTime('now', new DateTimeZone('UTC')))->modify('+' . SignerV1::DEFAULT_EXPIRES_DURATION . 'seconds');
        $request = $signCtx->request;
        $uri = $request->getUri();
        $this->assertStringContainsString("https://bucket.oss-cn-hangzhou.aliyuncs.com/key%20123?Expires=" . (string)$signTime->getTimestamp() . "&OSSAccessKeyId=ak&Signature=", (string)$uri);
    }
}
