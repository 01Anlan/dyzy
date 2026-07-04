<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\BucketStyle;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class BucketStyleTest extends \PHPUnit\Framework\TestCase
{
    public function testFromPutStyle()
    {
        // miss required field 
        try {
            $request = new Models\PutStyleRequest();
            $input = BucketStyle::fromPutStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\PutStyleRequest('bucket-123');
            $input = BucketStyle::fromPutStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, styleName", (string)$e);
        }

        try {
            $request = new Models\PutStyleRequest('bucket-123', 'test');
            $input = BucketStyle::fromPutStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, style", (string)$e);
        }

        // demo1
        $request = new Models\PutStyleRequest('bucket-123',
            styleName: 'test',
            style: new Models\StyleContent(
                'image/resize,p_50'
            )
        );
        $input = BucketStyle::fromPutStyle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $xml = <<<BBB
<?xml version="1.0" encoding="UTF-8"?><Style><Content>image/resize,p_50</Content></Style>
BBB;
        $this->assertEquals($xml, $this->cleanXml($input->getBody()->getContents()));
    }

    public function testToPutStyle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketStyle::toPutStyle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123']
        );
        $result = BucketStyle::toPutStyle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(1, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
    }

    public function testFromGetStyle()
    {
        // miss required field
        try {
            $request = new Models\GetStyleRequest();
            $input = BucketStyle::fromGetStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\GetStyleRequest('bucket-123');
            $input = BucketStyle::fromGetStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, styleName", (string)$e);
        }

        $request = new Models\GetStyleRequest('bucket-123', 'style-123');
        $input = BucketStyle::fromGetStyle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToGetStyle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketStyle::toGetStyle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
			<Style>
 <Name>imagestyle</Name>
 <Content>image/resize,p_50</Content>
 <Category>image</Category>
 <CreateTime>Wed, 20 May 2020 12:07:15 GMT</CreateTime>
 <LastModifyTime>Wed, 21 May 2020 12:07:15 GMT</LastModifyTime>
</Style>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketStyle::toGetStyle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('imagestyle', $result->style->name);
        $this->assertEquals('image/resize,p_50', $result->style->content);
        $this->assertEquals('image', $result->style->category);
        $this->assertEquals('Wed, 20 May 2020 12:07:15 GMT', $result->style->createTime);
        $this->assertEquals('Wed, 21 May 2020 12:07:15 GMT', $result->style->lastModifyTime);
    }

    public function testFromListStyle()
    {
        // miss required field
        try {
            $request = new Models\ListStyleRequest();
            $input = BucketStyle::fromListStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        $request = new Models\ListStyleRequest('bucket-123');
        $input = BucketStyle::fromListStyle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToListStyle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketStyle::toListStyle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<StyleList>
 <Style>
 <Name>imagestyle</Name>
 <Content>image/resize,p_50</Content>
 <Category>image</Category>
 <CreateTime>Wed, 20 May 2020 12:07:15 GMT</CreateTime>
 <LastModifyTime>Wed, 21 May 2020 12:07:15 GMT</LastModifyTime>
 </Style>
 <Style>
 <Name>imagestyle1</Name>
 <Content>image/resize,w_200</Content>
 <Category>image</Category>
 <CreateTime>Wed, 20 May 2020 12:08:04 GMT</CreateTime>
 <LastModifyTime>Wed, 21 May 2020 12:08:04 GMT</LastModifyTime>
 </Style>
 <Style>
 <Name>imagestyle2</Name>
 <Content>image/resize,w_300</Content>
 <Category>image</Category>
 <CreateTime>Fri, 12 Mar 2021 06:19:13 GMT</CreateTime>
 <LastModifyTime>Fri, 13 Mar 2021 06:27:21 GMT</LastModifyTime>
 </Style>
</StyleList>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            Utils::streamFor($body)
        );
        $result = BucketStyle::toListStyle($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('imagestyle', $result->styleList->styles[0]->name);
        $this->assertEquals('image/resize,p_50', $result->styleList->styles[0]->content);
        $this->assertEquals('image', $result->styleList->styles[0]->category);
        $this->assertEquals('Wed, 20 May 2020 12:07:15 GMT', $result->styleList->styles[0]->createTime);
        $this->assertEquals('Wed, 21 May 2020 12:07:15 GMT', $result->styleList->styles[0]->lastModifyTime);
        $this->assertEquals('imagestyle1', $result->styleList->styles[1]->name);
        $this->assertEquals('image/resize,w_200', $result->styleList->styles[1]->content);
        $this->assertEquals('image', $result->styleList->styles[1]->category);
        $this->assertEquals('Wed, 20 May 2020 12:08:04 GMT', $result->styleList->styles[1]->createTime);
        $this->assertEquals('Wed, 21 May 2020 12:08:04 GMT', $result->styleList->styles[1]->lastModifyTime);

        $this->assertEquals('imagestyle2', $result->styleList->styles[2]->name);
        $this->assertEquals('image/resize,w_300', $result->styleList->styles[2]->content);
        $this->assertEquals('image', $result->styleList->styles[2]->category);
        $this->assertEquals('Fri, 12 Mar 2021 06:19:13 GMT', $result->styleList->styles[2]->createTime);
        $this->assertEquals('Fri, 13 Mar 2021 06:27:21 GMT', $result->styleList->styles[2]->lastModifyTime);
    }

    public function testFromDeleteStyle()
    {
        // miss required field
        try {
            $request = new Models\DeleteStyleRequest();
            $input = BucketStyle::fromDeleteStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, bucket", (string)$e);
        }

        try {
            $request = new Models\DeleteStyleRequest('bucket-123');
            $input = BucketStyle::fromDeleteStyle($request);
            $this->assertTrue(false, "should not here");
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString("missing required field, styleName", (string)$e);
        }

        $request = new Models\DeleteStyleRequest('bucket-123', 'style-123');
        $input = BucketStyle::fromDeleteStyle($request);
        $this->assertEquals('bucket-123', $input->getBucket());
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', $input->getHeaders()['content-md5']);
    }

    public function testToDeleteStyle()
    {
        // empty output
        $output = new OperationOutput();
        $result = BucketStyle::toDeleteStyle($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $output = new OperationOutput(
            'No Content',
            204,
            ['x-oss-request-id' => '123']
        );
        $result = BucketStyle::toDeleteStyle($output);
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
