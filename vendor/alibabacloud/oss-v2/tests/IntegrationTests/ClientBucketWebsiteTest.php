<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;

class ClientBucketWebsiteTest extends TestIntegration
{
    public function testBucketWebsite()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName;

        // PutBucketWebsite
        $putResult = $client->putBucketWebsite(new Oss\Models\PutBucketWebsiteRequest(
            $bucketName,
            websiteConfiguration: new Oss\Models\WebsiteConfiguration(
                indexDocument: new Oss\Models\IndexDocument(
                suffix: 'index.html', supportSubDir: true, type: 0
            ),
                errorDocument: new Oss\Models\ErrorDocument(
                    key: 'error.html', httpStatus: 404
                )
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // GetBucketWebsite
        $getResult = $client->getBucketWebsite(new Oss\Models\GetBucketWebsiteRequest(
            $bucketName
        ));
        $this->assertEquals(200, $getResult->statusCode);
        $this->assertEquals('OK', $getResult->status);
        $this->assertEquals(True, count($getResult->headers) > 0);
        $this->assertEquals(24, strlen($getResult->requestId));

        // DeleteBucketWebsite
        $delResult = $client->deleteBucketWebsite(new Oss\Models\DeleteBucketWebsiteRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));

        // PutBucketWebsite
        $putResult = $client->putBucketWebsite(new Oss\Models\PutBucketWebsiteRequest(
            $bucketName,
            websiteConfiguration: new Oss\Models\WebsiteConfiguration(
                indexDocument: new Oss\Models\IndexDocument(
                suffix: 'index.html', supportSubDir: true, type: 0
            ),
                errorDocument: new Oss\Models\ErrorDocument(
                key: 'error.html', httpStatus: 404
            ),
                routingRules: new Oss\Models\RoutingRules([
                new  Oss\Models\RoutingRule(
                    redirect: new  Oss\Models\RoutingRuleRedirect(
                    mirrorPassOriginalSlashes: false,
                    redirectType: 'Mirror',
                    mirrorURL: 'http://example.com/',
                    mirrorPassQueryString: true,
                    mirrorCheckMd5: true,
                    mirrorSNI: true,
                    replaceKeyPrefixWith: "def/",
                    mirrorFollowRedirect: true,
                    hostName: "example.com",
                    mirrorHeaders: new Oss\Models\MirrorHeaders(
                    passs: ["myheader-key1", "myheader-key2"],
                    sets: array(
                    new Oss\Models\MirrorHeaderSet(key: 'myheader-key5', value: 'myheader-value5'),
                ),
                    passAll: true,
                ),
                    passQueryString: true,
                    enableReplacePrefix: true,
                    httpRedirectCode: 301,
                    mirrorURLSlave: 'http://example.com/',
                    mirrorSaveOssMeta: true,
                    mirrorProxyPass: false,
                    mirrorAllowGetImageInfo: true,
                    mirrorAllowVideoSnapshot: false,
                    mirrorIsExpressTunnel: true,
                    mirrorDstRegion: 'cn-hangzhou',
                    mirrorUserLastModified: false,
                    mirrorSwitchAllErrors: true,
                    mirrorUsingRole: true,
                    mirrorRole: 'aliyun-test-role',
                    mirrorAllowHeadObject: true,
                    transparentMirrorResponseCodes: '400',
                    mirrorTaggings: new Oss\Models\MirrorTaggings(
                    taggings: [new Oss\Models\MirrorTagging(
                        value: 'v',
                        key: 'k'
                    )]
                ),
                    mirrorReturnHeaders: new Oss\Models\MirrorReturnHeaders(
                    returnHeaders: [
                        new Oss\Models\ReturnHeader(
                            key: 'k',
                            value: 'v'
                        )
                    ]
                ),
                    mirrorAuth: new Oss\Models\MirrorAuth(
                    'S3V4', 'ap-southeast-1', 'TESTAK', 'TESTSK'
                ),
                    mirrorMultiAlternates: new Oss\Models\MirrorMultiAlternates(
                        [
                            new Oss\Models\MirrorMultiAlternate(
                                mirrorMultiAlternateNumber: 32, mirrorMultiAlternateURL: 'https://test-multi-alter.example.com', mirrorMultiAlternateVpcId: 'vpc-test-id', mirrorMultiAlternateDstRegion: 'ap-southeast-1'
                            )
                        ]
                    )
                ),
                    ruleNumber: 1,
                    condition: new Oss\Models\RoutingRuleCondition(
                    keyPrefixEquals: 'abc/',
                    keySuffixEquals: ".txt",
                    httpErrorCodeReturnedEquals: 404,
                ),
                    luaConfig: new Oss\Models\RoutingRuleLuaConfig(
                        script: "test.lua",
                    )
                ),
                new Oss\Models\RoutingRule(
                    redirect: new Oss\Models\RoutingRuleRedirect(
                    redirectType: 'AliCDN',
                    mirrorURL: 'http://example.com/',
                    mirrorPassQueryString: true,
                    mirrorCheckMd5: true,
                    mirrorSNI: true,
                    protocol: 'http',
                    mirrorFollowRedirect: true,
                    mirrorHeaders: new Oss\Models\MirrorHeaders(
                    passs: ["myheader-key1", "myheader-key2"],
                    sets: array(
                    new Oss\Models\MirrorHeaderSet(key: 'myheader-key5', value: 'myheader-value5'),
                ),
                    passAll: true,
                ),
                    passQueryString: true,
                    replaceKeyWith: "abc",
                ),
                    ruleNumber: 2,
                    condition: new Oss\Models\RoutingRuleCondition(
                    keyPrefixEquals: 'abc/',
                    keySuffixEquals: ".txt",
                    httpErrorCodeReturnedEquals: 404,
                ),
                    luaConfig: new Oss\Models\RoutingRuleLuaConfig(
                        script: "test.lua",
                    )
                ),
            ]),
            )
        ));
        $this->assertEquals(200, $putResult->statusCode);
        $this->assertEquals('OK', $putResult->status);
        $this->assertEquals(True, count($putResult->headers) > 0);
        $this->assertEquals(24, strlen($putResult->requestId));

        // DeleteBucketWebsite
        $delResult = $client->deleteBucketWebsite(new Oss\Models\DeleteBucketWebsiteRequest(
            $bucketName
        ));
        $this->assertEquals(204, $delResult->statusCode);
        $this->assertEquals('No Content', $delResult->status);
        $this->assertEquals(True, count($delResult->headers) > 0);
        $this->assertEquals(24, strlen($delResult->requestId));
    }

    public function testBucketWebsiteFail()
    {
        $client = $this->getDefaultClient();
        $bucketName = self::$bucketName . "-not-exist";

        // PutBucketWebsite
        try {
            $putResult = $client->putBucketWebsite(new Oss\Models\PutBucketWebsiteRequest(
                $bucketName,
                websiteConfiguration: new Oss\Models\WebsiteConfiguration(
                    indexDocument: new Oss\Models\IndexDocument(
                    suffix: 'index.html', supportSubDir: true, type: 0
                ),
                    errorDocument: new Oss\Models\ErrorDocument(
                        key: 'error.html', httpStatus: 404
                    )
                )
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error PutBucketWebsite', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // GetBucketWebsite
        try {
            $getResult = $client->getBucketWebsite(new Oss\Models\GetBucketWebsiteRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error GetBucketWebsite', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }

        // DeleteBucketWebsite
        try {
            $delResult = $client->deleteBucketWebsite(new Oss\Models\DeleteBucketWebsiteRequest(
                $bucketName
            ));
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error DeleteBucketWebsite', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('NoSuchBucket', $se->getErrorCode());
                $this->assertEquals(404, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }
}