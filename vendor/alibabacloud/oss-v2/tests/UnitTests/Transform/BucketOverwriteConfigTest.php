<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketOverwriteConfig;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketOverwriteConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutBucketOverwriteConfig()
    {
        // miss required field 
        try {
            $request = new Models\PutBucketOverwriteConfigRequest();
            $input = BucketOverwriteConfig::fromPutBucketOverwriteConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutBucketOverwriteConfigRequest('bucket-123');
            $input = BucketOverwriteConfig::fromPutBucketOverwriteConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, overwriteConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutBucketOverwriteConfigRequest('bucket-123', new Models\OverwriteConfiguration(
            rules: array(
                new Models\OverwriteRule(action: 'forbid', id: 'rule-001')
            )
        ));
        $input = BucketOverwriteConfig::fromPutBucketOverwriteConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><OverwriteConfiguration><Rule><Action>forbid</Action><ID>rule-001</ID></Rule></OverwriteConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));

        // demo2
        $request = new Models\PutBucketOverwriteConfigRequest('bucket-123', new Models\OverwriteConfiguration(
            rules: array(
                new Models\OverwriteRule(action: 'forbid', id: 'rule-001'),
                new Models\OverwriteRule(action: 'forbid', prefix: 'pre', suffix: '.txt', principals: new Models\OverwritePrincipals(array('1234567890')), id: 'rule-002')
            )
        ));
        $input = BucketOverwriteConfig::fromPutBucketOverwriteConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><OverwriteConfiguration><Rule><Action>forbid</Action><ID>rule-001</ID></Rule><Rule><Action>forbid</Action><Prefix>pre</Prefix><Suffix>.txt</Suffix><Principals><Principal>1234567890</Principal></Principals><ID>rule-002</ID></Rule></OverwriteConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutBucketOverwriteConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketOverwriteConfig::toPutBucketOverwriteConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketOverwriteConfig::toPutBucketOverwriteConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetBucketOverwriteConfig()
    {
        // miss required field
        try {
            $request = new Models\GetBucketOverwriteConfigRequest();
            $input = BucketOverwriteConfig::fromGetBucketOverwriteConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\GetBucketOverwriteConfigRequest('bucket-123');
        $input = BucketOverwriteConfig::fromGetBucketOverwriteConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetBucketOverwriteConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketOverwriteConfig::toGetBucketOverwriteConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<OverwriteConfiguration>
  <Rule>
    <ID>1</ID>
    <Action>forbid</Action>
    <Prefix></Prefix>
    <Suffix></Suffix>
    <Principals />
  </Rule>
  <Rule>
    <ID>2</ID>
    <Action>forbid</Action>
    <Prefix>pre</Prefix>
    <Suffix>.txt</Suffix>
    <Principals>
      <Principal>1234567890</Principal>
    </Principals>
  </Rule>
</OverwriteConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketOverwriteConfig::toGetBucketOverwriteConfig($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals(2, count($result->overwriteConfiguration->rules));
        $this->assertEquals("1", $result->overwriteConfiguration->rules[0]->id);
        $this->assertEquals("forbid", $result->overwriteConfiguration->rules[0]->action);
        $this->assertEquals("", $result->overwriteConfiguration->rules[0]->prefix);
        $this->assertEquals("", $result->overwriteConfiguration->rules[0]->suffix);
        $this->assertEquals(new Models\OverwritePrincipals(), $result->overwriteConfiguration->rules[0]->principals);
        $this->assertEquals("2", $result->overwriteConfiguration->rules[1]->id);
        $this->assertEquals("forbid", $result->overwriteConfiguration->rules[1]->action);
        $this->assertEquals("pre", $result->overwriteConfiguration->rules[1]->prefix);
        $this->assertEquals(".txt", $result->overwriteConfiguration->rules[1]->suffix);
        $this->assertEquals(1, count($result->overwriteConfiguration->rules[1]->principals->principals));
        $this->assertEquals("1234567890", $result->overwriteConfiguration->rules[1]->principals->principals[0]);
    }

    public function testFromDeleteBucketOverwriteConfig()
    {
        // miss required field
        try {
            $request = new Models\DeleteBucketOverwriteConfigRequest();
            $input = BucketOverwriteConfig::fromDeleteBucketOverwriteConfig($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\DeleteBucketOverwriteConfigRequest('bucket-123');
        $input = BucketOverwriteConfig::fromDeleteBucketOverwriteConfig($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteBucketOverwriteConfig()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketOverwriteConfig::toDeleteBucketOverwriteConfig($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketOverwriteConfig::toDeleteBucketOverwriteConfig($output);
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
