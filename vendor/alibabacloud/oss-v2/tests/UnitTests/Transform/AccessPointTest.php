<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\AccessPoint;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class AccessPointTest extends \PHPUnit\Framework\TestCase
{
    public function testFromCreateAccessPoint()
    {
        // miss required field 
        try {
            $request = new Models\CreateAccessPointRequest();
            $input = AccessPoint::fromCreateAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\CreateAccessPointRequest('bucket-123');
            $input = AccessPoint::fromCreateAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, createAccessPointConfiguration", (string)$e);
        }

        $request = new Models\CreateAccessPointRequest('bucket-123', new Models\CreateAccessPointConfiguration(
            accessPointName: 'ap-01',
            networkOrigin: 'internet'
        ));
        $input = AccessPoint::fromCreateAccessPoint($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CreateAccessPointConfiguration><AccessPointName>ap-01</AccessPointName><NetworkOrigin>internet</NetworkOrigin></CreateAccessPointConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        $request = new Models\CreateAccessPointRequest('bucket-123', new Models\CreateAccessPointConfiguration(
            vpcConfiguration: new Models\AccessPointVpcConfiguration('vpc-t4nlw426y44rd3iq4xxxx'),
            accessPointName: 'ap-01',
            networkOrigin: 'vpc'
        ));
        $input = AccessPoint::fromCreateAccessPoint($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><CreateAccessPointConfiguration><VpcConfiguration><VpcId>vpc-t4nlw426y44rd3iq4xxxx</VpcId></VpcConfiguration><AccessPointName>ap-01</AccessPointName><NetworkOrigin>vpc</NetworkOrigin></CreateAccessPointConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToCreateAccessPoint()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toCreateAccessPoint($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<CreateAccessPointResult>
  <AccessPointArn>acs:oss:cn-hangzhou:128364106451xxxx:accesspoint/ap-01</AccessPointArn>
  <Alias>ap-01-45ee7945007a2f0bcb595f63e2215cxxxx-ossalias</Alias>
</CreateAccessPointResult>';

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = AccessPoint::toCreateAccessPoint($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('acs:oss:cn-hangzhou:128364106451xxxx:accesspoint/ap-01', $result->createAccessPoint->accessPointArn);
        $this->assertEquals('ap-01-45ee7945007a2f0bcb595f63e2215cxxxx-ossalias', $result->createAccessPoint->alias);
    }

    public function testFromGetAccessPoint()
    {
        // miss required field
        try {
            $request = new Models\GetAccessPointRequest();
            $input = AccessPoint::fromGetAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetAccessPointRequest('bucket-123');
            $input = AccessPoint::fromGetAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessPointName", (string)$e);
        }

        $request = new Models\GetAccessPointRequest('bucket-123', 'ap-01');
        $input = AccessPoint::fromGetAccessPoint($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('ap-01', $input->getHeaders()['x-oss-access-point-name']);
    }

    public function testToGetAccessPoint()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toGetAccessPoint($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<GetAccessPointResult>
  <AccessPointName>ap-01</AccessPointName>
  <Bucket>oss-example</Bucket>
  <AccountId>111933544165xxxx</AccountId>
  <NetworkOrigin>vpc</NetworkOrigin>
  <VpcConfiguration>
     <VpcId>vpc-t4nlw426y44rd3iq4xxxx</VpcId>
  </VpcConfiguration>
  <AccessPointArn>arn:acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01</AccessPointArn>
  <CreationDate>1626769503</CreationDate>
  <Alias>ap-01-ossalias</Alias>
  <Status>enable</Status>
  <Endpoints>
    <PublicEndpoint>ap-01.oss-cn-hangzhou.oss-accesspoint.aliyuncs.com</PublicEndpoint>
    <InternalEndpoint>ap-01.oss-cn-hangzhou-internal.oss-accesspoint.aliyuncs.com</InternalEndpoint>
  </Endpoints>
  <PublicAccessBlockConfiguration>
    <BlockPublicAccess>true</BlockPublicAccess>
  </PublicAccessBlockConfiguration>
</GetAccessPointResult>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = AccessPoint::toGetAccessPoint($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('ap-01', $result->getAccessPoint->accessPointName);
        $this->assertEquals('oss-example', $result->getAccessPoint->bucket);
        $this->assertEquals('111933544165xxxx', $result->getAccessPoint->accountId);
        $this->assertEquals('vpc', $result->getAccessPoint->networkOrigin);
        $this->assertEquals('vpc-t4nlw426y44rd3iq4xxxx', $result->getAccessPoint->vpcConfiguration->vpcId);
        $this->assertEquals('arn:acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01', $result->getAccessPoint->accessPointArn);
        $this->assertEquals('1626769503', $result->getAccessPoint->creationDate);
        $this->assertEquals('ap-01-ossalias', $result->getAccessPoint->alias);
        $this->assertEquals('enable', $result->getAccessPoint->status);
        $this->assertEquals('ap-01.oss-cn-hangzhou.oss-accesspoint.aliyuncs.com', $result->getAccessPoint->endpoints->publicEndpoint);
        $this->assertEquals('ap-01.oss-cn-hangzhou-internal.oss-accesspoint.aliyuncs.com', $result->getAccessPoint->endpoints->internalEndpoint);
        $this->assertTrue($result->getAccessPoint->publicAccessBlockConfiguration->blockPublicAccess);
    }

    public function testFromDeleteAccessPoint()
    {
        // miss required field
        try {
            $request = new Models\DeleteAccessPointRequest();
            $input = AccessPoint::fromDeleteAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteAccessPointRequest('bucket-123');
            $input = AccessPoint::fromDeleteAccessPoint($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessPointName", (string)$e);
        }

        $request = new Models\DeleteAccessPointRequest('bucket-123', 'ap-01');
        $input = AccessPoint::fromDeleteAccessPoint($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('ap-01', $input->getHeaders()['x-oss-access-point-name']);
    }

    public function testToDeleteAccessPoint()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toDeleteAccessPoint($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123'],
        );
        $result = AccessPoint::toDeleteAccessPoint($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromListAccessPoints()
    {
        $request = new Models\ListAccessPointsRequest();
        $input = AccessPoint::fromListAccessPoints($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);

        $request = new Models\ListAccessPointsRequest(10, 'token', 'bucket-123');
        $input = AccessPoint::fromListAccessPoints($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('10', $input->getParameters()['max-keys']);
        $this->assertEquals('token', $input->getParameters()['continuation-token']);
    }

    public function testToListAccessPoints()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toGetAccessPoint($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListAccessPointsResult>
  <IsTruncated>true</IsTruncated>
  <NextContinuationToken>abc</NextContinuationToken>
  <AccountId>111933544165****</AccountId>
  <MaxKeys>3</MaxKeys>
  <AccessPoints>
    <AccessPoint>
      <Bucket>oss-example</Bucket>
      <AccessPointName>ap-01</AccessPointName>
      <Alias>ap-01-ossalias</Alias>
      <NetworkOrigin>vpc</NetworkOrigin>
      <VpcConfiguration>
        <VpcId>vpc-t4nlw426y44rd3iq4****</VpcId>
      </VpcConfiguration>
      <Status>enable</Status>
    </AccessPoint>
    <AccessPoint>
      <Bucket>oss-example</Bucket>
      <AccessPointName>ap-02</AccessPointName>
      <Alias>ap-02-ossalias</Alias>
      <NetworkOrigin>vpc</NetworkOrigin>
      <VpcConfiguration>
        <VpcId>vpc-t4nlw426y44rd3iq2****</VpcId>
      </VpcConfiguration>
      <Status>enable</Status>
    </AccessPoint>
	<AccessPoint>
      <Bucket>oss-example</Bucket>
      <AccessPointName>ap-03</AccessPointName>
      <Alias>ap-03-ossalias</Alias>
      <NetworkOrigin>internet</NetworkOrigin>
      <VpcConfiguration>
        <VpcId></VpcId>
      </VpcConfiguration>
      <Status>enable</Status>
    </AccessPoint>
  </AccessPoints>
</ListAccessPointsResult>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = AccessPoint::toListAccessPoints($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('111933544165****', $result->listAccessPoints->accountId);
        $this->assertEquals('abc', $result->listAccessPoints->nextContinuationToken);
        $this->assertTrue($result->listAccessPoints->isTruncated);
        $this->assertEquals(3, $result->listAccessPoints->maxKeys);
        $this->assertEquals(3, count($result->listAccessPoints->accessPoints->accessPoints));
        $this->assertEquals('oss-example', $result->listAccessPoints->accessPoints->accessPoints[0]->bucket);
        $this->assertEquals('ap-01', $result->listAccessPoints->accessPoints->accessPoints[0]->accessPointName);
        $this->assertEquals('ap-01-ossalias', $result->listAccessPoints->accessPoints->accessPoints[0]->alias);
        $this->assertEquals('vpc', $result->listAccessPoints->accessPoints->accessPoints[0]->networkOrigin);
        $this->assertEquals('vpc-t4nlw426y44rd3iq4****', $result->listAccessPoints->accessPoints->accessPoints[0]->vpcConfiguration->vpcId);
        $this->assertEquals('enable', $result->listAccessPoints->accessPoints->accessPoints[0]->status);
        $this->assertEquals('oss-example', $result->listAccessPoints->accessPoints->accessPoints[1]->bucket);
        $this->assertEquals('ap-02', $result->listAccessPoints->accessPoints->accessPoints[1]->accessPointName);
        $this->assertEquals('ap-02-ossalias', $result->listAccessPoints->accessPoints->accessPoints[1]->alias);
        $this->assertEquals('vpc', $result->listAccessPoints->accessPoints->accessPoints[1]->networkOrigin);
        $this->assertEquals('vpc-t4nlw426y44rd3iq2****', $result->listAccessPoints->accessPoints->accessPoints[1]->vpcConfiguration->vpcId);
        $this->assertEquals('enable', $result->listAccessPoints->accessPoints->accessPoints[1]->status);
        $this->assertEquals('oss-example', $result->listAccessPoints->accessPoints->accessPoints[2]->bucket);
        $this->assertEquals('ap-03', $result->listAccessPoints->accessPoints->accessPoints[2]->accessPointName);
        $this->assertEquals('ap-03-ossalias', $result->listAccessPoints->accessPoints->accessPoints[2]->alias);
        $this->assertEquals('internet', $result->listAccessPoints->accessPoints->accessPoints[2]->networkOrigin);
        $this->assertEquals('enable', $result->listAccessPoints->accessPoints->accessPoints[2]->status);
    }

    public function testFromPutAccessPointPolicy()
    {
        // miss required field
        try {
            $request = new Models\PutAccessPointPolicyRequest();
            $input = AccessPoint::fromPutAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutAccessPointPolicyRequest('bucket-123');
            $input = AccessPoint::fromPutAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessPointName", (string)$e);
        }

        try {
            $request = new Models\PutAccessPointPolicyRequest('bucket-123', 'ap-01');
            $input = AccessPoint::fromPutAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, body", (string)$e);
        }

        $request = new Models\PutAccessPointPolicyRequest('bucket-123', 'ap-01', '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["27737962156157xxxx"],"Resource":["acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01","acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01/object/*"]}]}');
        $input = AccessPoint::fromPutAccessPointPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["27737962156157xxxx"],"Resource":["acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01","acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01/object/*"]}]}', $input->getBody()->getContents());
    }

    public function testToPutAccessPointPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toPutAccessPointPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
        );
        $result = AccessPoint::toPutAccessPointPolicy($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetAccessPointPolicy()
    {
        // miss required field
        try {
            $request = new Models\GetAccessPointPolicyRequest();
            $input = AccessPoint::fromGetAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetAccessPointPolicyRequest('bucket-123');
            $input = AccessPoint::fromGetAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessPointName", (string)$e);
        }

        $request = new Models\GetAccessPointPolicyRequest('bucket-123', 'ap-01');
        $input = AccessPoint::fromGetAccessPointPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('ap-01', $input->getHeaders()['x-oss-access-point-name']);
    }

    public function testToGetAccessPointPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toGetAccessPointPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '{"Version":"1","Statement":[{"Action":["oss:PutObject","oss:GetObject"],"Effect":"Deny","Principal":["27737962156157xxxx"],"Resource":["acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01","acs:oss:cn-hangzhou:111933544165xxxx:accesspoint/ap-01/object/*"]}]}';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/json'],
            body: Utils::streamFor($body)
        );
        $result = AccessPoint::toGetAccessPointPolicy($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/json', $result->headers['content-type']);
        $this->assertEquals($body, $result->body);
    }

    public function testFromDeleteAccessPointPolicy()
    {
        // miss required field
        try {
            $request = new Models\DeleteAccessPointPolicyRequest();
            $input = AccessPoint::fromDeleteAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteAccessPointPolicyRequest('bucket-123');
            $input = AccessPoint::fromDeleteAccessPointPolicy($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, accessPointName", (string)$e);
        }

        $request = new Models\DeleteAccessPointPolicyRequest('bucket-123', 'ap-01');
        $input = AccessPoint::fromDeleteAccessPointPolicy($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
        $this->assertEquals('ap-01', $input->getHeaders()['x-oss-access-point-name']);
    }

    public function testToDeleteAccessPointPolicy()
    {
        // empty output
        $output = new OperationOutput();
        $result = AccessPoint::toDeleteAccessPointPolicy($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123'],
        );
        $result = AccessPoint::toDeleteAccessPointPolicy($output);
        $this->assertEquals('No Content', $result->status);
        $this->assertEquals(204, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    private function cleanXml($xml): array|string
    {
        return str_replace("\n", "", str_replace("\r", "", $xml));
    }
}
