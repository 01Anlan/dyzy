<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketHttpsConfig;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketHttpsConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketHttpsConfig()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketHttpsConfigRequest();
            $input = BucketHttpsConfig::fromPutBucketHttpsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketHttpsConfigRequest('bucket-123');
            $input = BucketHttpsConfig::fromPutBucketHttpsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, httpsConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketHttpsConfigRequest('bucket-123', new Models\HttpsConfiguration(
            tls: new Models\TLS(
                tlsVersions: ['TLSv1.2', 'TLSv1.3'],
                enable: true
            )
        ));
        $input = BucketHttpsConfig::fromPutBucketHttpsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><HttpsConfiguration><TLS><TLSVersion>TLSv1.2</TLSVersion><TLSVersion>TLSv1.3</TLSVersion><Enable>true</Enable></TLS></HttpsConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketHttpsConfigRequest('bucket-123', new Models\HttpsConfiguration(
            tls: new Models\TLS(
            tlsVersions: ['TLSv1.2', 'TLSv1.3'],
            enable: true
        ),
            cipherSuite: new Models\CipherSuite(
                enable: true,
                strongCipherSuite: false,
                customCipherSuites: ['ECDHE-ECDSA-AES128-SHA256', 'ECDHE-RSA-AES128-GCM-SHA256', 'ECDHE-ECDSA-AES256-CCM8'],
                tls13CustomCipherSuites: ['ECDHE-ECDSA-AES256-CCM8', 'ECDHE-ECDSA-AES256-CCM8', 'ECDHE-ECDSA-AES256-CCM8'],
            )
        ));
        $input = BucketHttpsConfig::fromPutBucketHttpsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><HttpsConfiguration><TLS><TLSVersion>TLSv1.2</TLSVersion><TLSVersion>TLSv1.3</TLSVersion><Enable>true</Enable></TLS><CipherSuite><Enable>true</Enable><StrongCipherSuite>false</StrongCipherSuite><CustomCipherSuite>ECDHE-ECDSA-AES128-SHA256</CustomCipherSuite><CustomCipherSuite>ECDHE-RSA-AES128-GCM-SHA256</CustomCipherSuite><CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite></CipherSuite></HttpsConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketHttpsConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketHttpsConfig::toPutBucketHttpsConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketHttpsConfig::toPutBucketHttpsConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketHttpsConfig()
    {
        // miss required field
        try {
            $request = new Models\GetBucketHttpsConfigRequest();
            $input = BucketHttpsConfig::fromGetBucketHttpsConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketHttpsConfigRequest('bucket-123');
        $input = BucketHttpsConfig::fromGetBucketHttpsConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketHttpsConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketHttpsConfig::toGetBucketHttpsConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<HttpsConfiguration>  
  <TLS>
    <Enable>true</Enable>   
    <TLSVersion>TLSv1.2</TLSVersion>
    <TLSVersion>TLSv1.3</TLSVersion>    
  </TLS>
</HttpsConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketHttpsConfig::toGetBucketHttpsConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertTrue($result->httpsConfiguration->tls->enable);
        $this->assertEquals(2, count($result->httpsConfiguration->tls->tlsVersions));
        $this->assertEquals("TLSv1.2", $result->httpsConfiguration->tls->tlsVersions[0]);
        $this->assertEquals("TLSv1.3", $result->httpsConfiguration->tls->tlsVersions[1]);

        $body = '<?xml version="1.0" encoding="UTF-8"?><HttpsConfiguration><TLS><TLSVersion>TLSv1.2</TLSVersion><TLSVersion>TLSv1.3</TLSVersion><Enable>true</Enable></TLS><CipherSuite><Enable>true</Enable><StrongCipherSuite>false</StrongCipherSuite><CustomCipherSuite>ECDHE-ECDSA-AES128-SHA256</CustomCipherSuite><CustomCipherSuite>ECDHE-RSA-AES128-GCM-SHA256</CustomCipherSuite><CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite><TLS13CustomCipherSuite>ECDHE-ECDSA-AES256-CCM8</TLS13CustomCipherSuite></CipherSuite></HttpsConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketHttpsConfig::toGetBucketHttpsConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertTrue($result->httpsConfiguration->tls->enable);
        $this->assertEquals(2, count($result->httpsConfiguration->tls->tlsVersions));
        $this->assertEquals("TLSv1.2", $result->httpsConfiguration->tls->tlsVersions[0]);
        $this->assertEquals("TLSv1.3", $result->httpsConfiguration->tls->tlsVersions[1]);

        $this->assertTrue($result->httpsConfiguration->cipherSuite->enable);
        $this->assertFalse($result->httpsConfiguration->cipherSuite->strongCipherSuite);
        $this->assertEquals(3, count($result->httpsConfiguration->cipherSuite->customCipherSuites));
        $this->assertEquals('ECDHE-ECDSA-AES128-SHA256', $result->httpsConfiguration->cipherSuite->customCipherSuites[0]);
        $this->assertEquals('ECDHE-RSA-AES128-GCM-SHA256', $result->httpsConfiguration->cipherSuite->customCipherSuites[1]);
        $this->assertEquals('ECDHE-ECDSA-AES256-CCM8', $result->httpsConfiguration->cipherSuite->customCipherSuites[2]);
        $this->assertEquals(3, count($result->httpsConfiguration->cipherSuite->tls13CustomCipherSuites));
        $this->assertEquals('ECDHE-ECDSA-AES256-CCM8', $result->httpsConfiguration->cipherSuite->tls13CustomCipherSuites[0]);
        $this->assertEquals('ECDHE-ECDSA-AES256-CCM8', $result->httpsConfiguration->cipherSuite->tls13CustomCipherSuites[1]);
        $this->assertEquals('ECDHE-ECDSA-AES256-CCM8', $result->httpsConfiguration->cipherSuite->tls13CustomCipherSuites[2]);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
