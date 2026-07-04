<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketCname;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketCnameTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutCname()
    {
        // miss required field 
        try {
            $request = new Models\PutCnameRequest();
            $input = BucketCname::fromPutCname($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutCnameRequest('bucket-123');
            $input = BucketCname::fromPutCname($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucketCnameConfiguration", (string)$e);
        }

        $request = new Models\PutCnameRequest('bucket-123', new Models\BucketCnameConfiguration(
            new Models\Cname(
                domain: 'example.com'
            )
        ));
        $input = BucketCname::fromPutCname($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketCnameConfiguration><Cname><Domain>example.com</Domain></Cname></BucketCnameConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\PutCnameRequest('bucket-123', new Models\BucketCnameConfiguration(
            new Models\Cname(
                domain: 'example.com',
                certificateConfiguration: new Models\CertificateConfiguration(
                    force: true,
                    certId: '493****-cn-hangzhou',
                    certificate: '-----BEGIN CERTIFICATE----- MIIDhDCCAmwCCQCFs8ixARsyrDANBgkqhkiG9w0BAQsFADCBgzELMAkGA1UEBhMC **** -----END CERTIFICATE-----',
                    privateKey: '-----BEGIN CERTIFICATE----- MIIDhDCCAmwCCQCFs8ixARsyrDANBgkqhkiG9w0BAQsFADCBgzELMAkGA1UEBhMC **** -----END CERTIFICATE-----',
                    previousCertId: '493****-cn-hangzhou'
                )
            )
        ));
        $input = BucketCname::fromPutCname($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketCnameConfiguration><Cname><Domain>example.com</Domain><CertificateConfiguration><Force>true</Force><CertId>493****-cn-hangzhou</CertId><Certificate>-----BEGIN CERTIFICATE----- MIIDhDCCAmwCCQCFs8ixARsyrDANBgkqhkiG9w0BAQsFADCBgzELMAkGA1UEBhMC **** -----END CERTIFICATE-----</Certificate><PrivateKey>-----BEGIN CERTIFICATE----- MIIDhDCCAmwCCQCFs8ixARsyrDANBgkqhkiG9w0BAQsFADCBgzELMAkGA1UEBhMC **** -----END CERTIFICATE-----</PrivateKey><PreviousCertId>493****-cn-hangzhou</PreviousCertId></CertificateConfiguration></Cname></BucketCnameConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\PutCnameRequest('bucket-123', new Models\BucketCnameConfiguration(
            new Models\Cname(
                domain: 'example.com',
                certificateConfiguration: new Models\CertificateConfiguration(
                    deleteCertificate: true,
                )
            )
        ));
        $input = BucketCname::fromPutCname($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketCnameConfiguration><Cname><Domain>example.com</Domain><CertificateConfiguration><DeleteCertificate>true</DeleteCertificate></CertificateConfiguration></Cname></BucketCnameConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutCname()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCname::toPutCname($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketCname::toPutCname($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromCreateCnameToken()
    {
        // miss required field
        try {
            $request = new Models\CreateCnameTokenRequest();
            $input = BucketCname::fromCreateCnameToken($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CreateCnameTokenRequest('bucket-123');
            $input = BucketCname::fromCreateCnameToken($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucketCnameConfiguration", (string)$e);
        }

        $request = new Models\CreateCnameTokenRequest('bucket-123', new Models\BucketCnameConfiguration(
            new Models\Cname(
                domain: 'example.com'
            )
        ));
        $input = BucketCname::fromCreateCnameToken($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketCnameConfiguration><Cname><Domain>example.com</Domain></Cname></BucketCnameConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToCreateCnameToken()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCname::toCreateCnameToken($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<CnameToken>
  <Bucket>bucket</Bucket>
  <Cname>example.com</Cname>;
  <Token>be1d49d863dea9ffeff3df7d6455****</Token>
  <ExpireTime>Wed, 23 Feb 2022 21:16:37 GMT</ExpireTime>
</CnameToken>')
        );
        $result = BucketCname::toCreateCnameToken($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('bucket', $result->cnameToken->bucket);
        $this->assertEquals('example.com', $result->cnameToken->cname);
        $this->assertEquals('be1d49d863dea9ffeff3df7d6455****', $result->cnameToken->token);
        $this->assertEquals('Wed, 23 Feb 2022 21:16:37 GMT', $result->cnameToken->expireTime);
    }

    public function testFromGetCnameToken()
    {
        // miss required field
        try {
            $request = new Models\GetCnameTokenRequest();
            $input = BucketCname::fromGetCnameToken($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetCnameTokenRequest('bucket-123');
            $input = BucketCname::fromGetCnameToken($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, cname", (string)$e);
        }

        $request = new Models\GetCnameTokenRequest('bucket-123', 'example.com');
        $input = BucketCname::fromGetCnameToken($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('example.com', $input->getParameters()['cname']);
    }

    public function testToGetCnameToken()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCname::toGetCnameToken($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor('<?xml version="1.0" encoding="UTF-8"?>
<CnameToken>
  <Bucket>bucket</Bucket>
  <Cname>example.com</Cname>;
  <Token>be1d49d863dea9ffeff3df7d6455****</Token>
  <ExpireTime>Wed, 23 Feb 2022 21:16:37 GMT</ExpireTime>
</CnameToken>')
        );
        $result = BucketCname::toGetCnameToken($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('bucket', $result->cnameToken->bucket);
        $this->assertEquals('example.com', $result->cnameToken->cname);
        $this->assertEquals('be1d49d863dea9ffeff3df7d6455****', $result->cnameToken->token);
        $this->assertEquals('Wed, 23 Feb 2022 21:16:37 GMT', $result->cnameToken->expireTime);
    }

    public function testFromListCname()
    {
        // miss required field
        try {
            $request = new Models\ListCnameRequest();
            $input = BucketCname::fromListCname($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\ListCnameRequest('bucket-123');
        $input = BucketCname::fromListCname($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToListCname()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCname::toListCname($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListCnameResult>
  <Bucket>bucket</Bucket>
  <Owner>owner</Owner>
  <Cname>
    <Domain>example.com</Domain>
    <LastModified>2021-09-15T02:35:07.000Z</LastModified>
    <Status>Enabled</Status>
    <Certificate>
      <Type>CAS</Type>
      <CertId>493****-cn-hangzhou</CertId>
      <Status>Enabled</Status>
      <CreationDate>Wed, 15 Sep 2021 02:35:06 GMT</CreationDate>
      <Fingerprint>DE:01:CF:EC:7C:A7:98:CB:D8:6E:FB:1D:97:EB:A9:64:1D:4E:**:**</Fingerprint>
      <ValidStartDate>Wed, 12 Apr 2023 10:14:51 GMT</ValidStartDate>
      <ValidEndDate>Mon, 4 May 2048 10:14:51 GMT</ValidEndDate>
    </Certificate>
  </Cname>
  <Cname>
    <Domain>example.org</Domain>
    <LastModified>2021-09-15T02:34:58.000Z</LastModified>
    <Status>Enabled</Status>
  </Cname>
  <Cname>
    <Domain>example.edu</Domain>
    <LastModified>2021-09-15T02:50:34.000Z</LastModified>
    <Status>Enabled</Status>
  </Cname>
</ListCnameResult>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketCname::toListCname($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('bucket', $result->bucket);
        $this->assertEquals('owner', $result->owner);
        $this->assertEquals(3, count($result->cnames));
        $this->assertEquals('example.com', $result->cnames[0]->domain);
        $this->assertEquals('2021-09-15T02:35:07.000Z', $result->cnames[0]->lastModified);
        $this->assertEquals('Enabled', $result->cnames[0]->status);
        $this->assertEquals('CAS', $result->cnames[0]->certificate->type);
        $this->assertEquals('493****-cn-hangzhou', $result->cnames[0]->certificate->certId);
        $this->assertEquals('Enabled', $result->cnames[0]->certificate->status);
        $this->assertEquals('Wed, 15 Sep 2021 02:35:06 GMT', $result->cnames[0]->certificate->creationDate);
        $this->assertEquals('DE:01:CF:EC:7C:A7:98:CB:D8:6E:FB:1D:97:EB:A9:64:1D:4E:**:**', $result->cnames[0]->certificate->fingerprint);
        $this->assertEquals('Wed, 12 Apr 2023 10:14:51 GMT', $result->cnames[0]->certificate->validStartDate);
        $this->assertEquals('Mon, 4 May 2048 10:14:51 GMT', $result->cnames[0]->certificate->validEndDate);
        $this->assertEquals('example.org', $result->cnames[1]->domain);
        $this->assertEquals('2021-09-15T02:34:58.000Z', $result->cnames[1]->lastModified);
        $this->assertEquals('Enabled', $result->cnames[1]->status);
        $this->assertEquals('example.edu', $result->cnames[2]->domain);
        $this->assertEquals('2021-09-15T02:50:34.000Z', $result->cnames[2]->lastModified);
        $this->assertEquals('Enabled', $result->cnames[2]->status);
    }

    public function testFromDeleteCname()
    {
        // miss required field
        try {
            $request = new Models\DeleteCnameRequest();
            $input = BucketCname::fromDeleteCname($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteCnameRequest('bucket-123');
            $input = BucketCname::fromDeleteCname($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucketCnameConfiguration", (string)$e);
        }

        $request = new Models\DeleteCnameRequest('bucket-123', new Models\BucketCnameConfiguration(
            new Models\Cname(
                domain: 'example.com'
            )
        ));
        $input = BucketCname::fromDeleteCname($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><BucketCnameConfiguration><Cname><Domain>example.com</Domain></Cname></BucketCnameConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToDeleteCname()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketCname::toDeleteCname($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
        );
        $result = BucketCname::toDeleteCname($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
