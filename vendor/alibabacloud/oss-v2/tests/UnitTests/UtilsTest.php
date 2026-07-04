<?php

namespace UnitTests;

use Psr\Http\Message\StreamInterface;
use AlibabaCloud\Oss\V2\Utils;
use AlibabaCloud\Oss\V2\Version;

class UtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testStreamFor()
    {
        $data = 'hello world';
        $stream = Utils::streamFor($data);
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(11, $stream->getSize());
        $this->assertEquals(true, $stream->isSeekable());
        $got = $stream->read(20);
        $this->assertEquals($data, $got);

        $got = $stream->read(20);
        $this->assertEquals('', $got);

        $stream->rewind();
        $got = $stream->read(20);
        $this->assertEquals($data, $got);
    }

    public function testGuessContentType(): void
    {
        $this->assertEquals(null, Utils::guessContentType(''));
        $this->assertEquals('application/octet-stream', Utils::guessContentType('', 'application/octet-stream'));
        $this->assertEquals('image/jpeg', Utils::guessContentType('1.jpeg'));
        $this->assertEquals('image/jpeg', Utils::guessContentType('1.jpeg', 'application/octet-stream'));
    }

    public function testUrlEncode(): void
    {
        $this->assertEquals('123%2F123%2F%2B%20%3F%2F123', Utils::urlEncode('123/123/+ ?/123'));
        $this->assertEquals('123/123/%2B%20%3F/123', Utils::urlEncode('123/123/+ ?/123', true));
    }

    public function testIsIpFormat(): void
    {
        $this->assertTrue(Utils::isIPFormat("10.101.160.147"));
        $this->assertTrue(Utils::isIPFormat("12.12.12.34"));
        $this->assertTrue(Utils::isIPFormat("12.12.12.12"));
        $this->assertTrue(Utils::isIPFormat("255.255.255.255"));
        $this->assertTrue(Utils::isIPFormat("0.1.1.1"));
        $this->assertFalse(Utils::isIPFormat("0.1.1.x"));
        $this->assertFalse(Utils::isIPFormat("0.1.1.256"));
        $this->assertFalse(Utils::isIPFormat("256.1.1.1"));
        $this->assertFalse(Utils::isIPFormat("0.1.1.0.1"));
        $this->assertTrue(Utils::isIPFormat("10.10.10.10:123"));
    }

    public function testToSimpleArray(): void
    {
        $this->assertEquals([], Utils::toSimpleArray([]));
        $this->assertEquals([1, 2, 3], Utils::toSimpleArray([1, 2, 3]));
        $this->assertEquals(
            ['1' => '1-1', '2' => '2-1', '3' => '3-1'],
            Utils::toSimpleArray(['1' => ['1-1', '1-2'], '2' => ['2-1', '2-2'], '3' => '3-1'])
        );
    }

    public function testCalcContentMd5(): void
    {
        $this->assertEquals('1B2M2Y8AsgTpgAmY7PhCfg==', Utils::calcContentMd5(Utils::streamFor('')));
        $body = Utils::streamFor("hello world\n");
        $this->assertEquals('b1kCrCNwJL3QwXbLkwY9xA==', Utils::calcContentMd5($body));
        $this->assertEquals('b1kCrCNwJL3QwXbLkwY9xA==', Utils::calcContentMd5($body));
    }

    public function testEscapeXml(): void
    {
        $bytes = [0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f];
        $encstr = '&#00;&#01;&#02;&#03;&#04;&#05;&#06;&#07;&#08;&#09;&#10;&#11;&#12;&#13;&#14;&#15;';
        $edata = Utils::escapeXml(pack("C*", ...$bytes));
        $this->assertEquals($encstr, $edata);

        $bytes = [
            0x10,
            0x11,
            0x12,
            0x13,
            0x14,
            0x15,
            0x16,
            0x17,
            0x18,
            0x19,
            0x1a,
            0x1b,
            0x1c,
            0x1d,
            0x1e,
            0x1f,
            0x20,
            0x21,
            0xe4,
            0xbd,
            0xa0,
            0xe5,
            0xa5,
            0xbd
        ];
        $encstr = '&#16;&#17;&#18;&#19;&#20;&#21;&#22;&#23;&#24;&#25;&#26;&#27;&#28;&#29;&#30;&#31; !你好';
        $edata = Utils::escapeXml(pack("C*", ...$bytes));
        $this->assertEquals($encstr, $edata);

        $data = '<>&"';
        $encstr = '&lt;&gt;&amp;&quot;';
        $edata = Utils::escapeXml($data);
        $this->assertEquals($encstr, $edata);
    }

    public function testParseHttpRange(): void
    {
        $values = Utils::parseHttpRange('bytes=22-33');
        $this->assertCount(2, $values);
        $this->assertEquals(22, $values[0]);
        $this->assertEquals(33, $values[1]);

        $values = Utils::parseHttpRange('bytes=-33');
        $this->assertCount(2, $values);
        $this->assertEquals(-1, $values[0]);
        $this->assertEquals(33, $values[1]);

        $values = Utils::parseHttpRange('bytes=22-');
        $this->assertCount(2, $values);
        $this->assertEquals(22, $values[0]);
        $this->assertEquals(-1, $values[1]);

        $values = Utils::parseHttpRange('bytes=0-');
        $this->assertCount(2, $values);
        $this->assertEquals(0, $values[0]);
        $this->assertEquals(-1, $values[1]);

        $values = Utils::parseHttpRange('bytes=-0');
        $this->assertCount(2, $values);
        $this->assertEquals(-1, $values[0]);
        $this->assertEquals(0, $values[1]);

        $values = Utils::parseHttpRange('bytes=-0');
        $this->assertCount(2, $values);
        $this->assertEquals(-1, $values[0]);
        $this->assertEquals(0, $values[1]);        

        $this->assertFalse(Utils::parseHttpRange(''));
        $this->assertFalse(Utils::parseHttpRange('bytes=1-42,55-60'));
        $this->assertFalse(Utils::parseHttpRange('bytes=1'));
        $this->assertFalse(Utils::parseHttpRange('bytes=abc-123'));
        $this->assertFalse(Utils::parseHttpRange('bytes=123-abc'));
    }

    public function testParseContentRange(): void
    {
        $values = Utils::parseContentRange('bytes 22-33/42');
        $this->assertCount(3, $values);
        $this->assertEquals(22, $values[0]);
        $this->assertEquals(33, $values[1]);
        $this->assertEquals(42, $values[2]);

        $values = Utils::parseContentRange('bytes 22-33/*');
        $this->assertCount(3, $values);
        $this->assertEquals(22, $values[0]);
        $this->assertEquals(33, $values[1]);
        $this->assertEquals(-1, $values[2]);

        $this->assertFalse(Utils::parseContentRange(''));
        $this->assertFalse(Utils::parseContentRange('invalid'));
        $this->assertFalse(Utils::parseContentRange('bytes abc'));
        $this->assertFalse(Utils::parseContentRange('bytes */42'));
        $this->assertFalse(Utils::parseContentRange('bytes abc/42'));
        $this->assertFalse(Utils::parseContentRange('bytes abc-33/42'));
        $this->assertFalse(Utils::parseContentRange('bytes 11-abc/42'));
        $this->assertFalse(Utils::parseContentRange('bytes 11-33/abc'));
    }

    public function testDefaultUserAgent(): void
    {
        $userAgent = Utils::defaultUserAgent();
        $this->assertStringContainsString('alibabacloud-php-sdk-v2/', $userAgent);
        $this->assertStringContainsString(Version::VERSION, $userAgent);
    }
}
