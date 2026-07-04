<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\PublicAccessBlock;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class PublicAccessBlockTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutPublicAccessBlock()
    {
        // miss required field 
        try {
            $request = new Models\PutPublicAccessBlockRequest();
            $input = PublicAccessBlock::fromPutPublicAccessBlock($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, publicAccessBlockConfiguration", (string)$e);
        }

        // demo1
        $request = new Models\PutPublicAccessBlockRequest(new Models\PublicAccessBlockConfiguration(
            true
        ));
        $input = PublicAccessBlock::fromPutPublicAccessBlock($request);
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><PublicAccessBlockConfiguration><BlockPublicAccess>true</BlockPublicAccess></PublicAccessBlockConfiguration>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutPublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = PublicAccessBlock::toPutPublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = PublicAccessBlock::toPutPublicAccessBlock($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetPublicAccessBlock()
    {
        $request = new Models\GetPublicAccessBlockRequest();
        $input = PublicAccessBlock::fromGetPublicAccessBlock($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetPublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = PublicAccessBlock::toGetPublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
            <PublicAccessBlockConfiguration>
			  <BlockPublicAccess>true</BlockPublicAccess>
			</PublicAccessBlockConfiguration>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123'],
            Utils::streamFor($body)
        );
        $result = PublicAccessBlock::toGetPublicAccessBlock($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertTrue($result->publicAccessBlockConfiguration->blockPublicAccess);
    }

    public function testFromDeletePublicAccessBlock()
    {
        $request = new Models\DeletePublicAccessBlockRequest();
        $input = PublicAccessBlock::fromDeletePublicAccessBlock($request);
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeletePublicAccessBlock()
    {
        // empty output
        $output = new OperationOutput();
        $result = PublicAccessBlock::toDeletePublicAccessBlock($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = PublicAccessBlock::toDeletePublicAccessBlock($output);
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
