<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Transform\Functions;
use AlibabaCloud\Oss\V2\Exception;

class FunctionsTest extends \PHPUnit\Framework\TestCase
{
    public function testAssertFieldRequired()
    {
        try {
            Functions::assertFieldRequired('field', null);
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('missing required field, field', $e);
        }

        Functions::assertFieldRequired('field', 'hello world');
    }

    public function testAssertXmlRoot()
    {
        try {
            Functions::assertXmlRoot('', '');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag', $e);
        }

        try {
            Functions::assertXmlRoot('<Root></Root>', '');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag', $e);
        }

        try {
            Functions::assertXmlRoot('<Root></Root>', '123');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag', $e);
        }

        Functions::assertXmlRoot('<Root></Root>', 'Root');
        Functions::assertXmlRoot('<Root xmlns="http://doc.oss-cn-hangzhou.aliyuncs.com"></Root>', 'Root');
        Functions::assertXmlRoot('<Root any char></Root>', 'Root');

        $str = '<?xml version="1.0" encoding="UTF-8"?>
            <RootConfiguration>
                <Id>str1</Id>
                <Text>str2</Text>
                <SubConfiguration>
                    <StrField>str-sub</StrField>
                    <IntField>1234</IntField>
                </SubConfiguration>
            </RootConfiguration>';
        try {
            Functions::assertXmlRoot($str, 'Configuration');
            $this->assertTrue(false, 'should not here');
        } catch (Exception\DeserializationExecption $e) {
            $this->assertStringContainsString('Not found tag', $e);
        }

    }


    public function testtryToDatetime()
    {
        $elem = new \SimpleXMLElement('<Root><Data>2023-12-17T03:30:09Z</Data><Data1>invalid value</Data1></Root>');
        $this->assertNull(Functions::tryToDatetime($elem->NoNode));
        $this->assertNull(Functions::tryToDatetime($elem->Data1));
        $datetimeUtc = new \DateTime();
        $datetimeUtc->setTimestamp(1702783809);
        $this->assertEquals($datetimeUtc, Functions::tryToDatetime($elem->Data));
    }

    public function testtryToBool()
    {
        $elem = new \SimpleXMLElement('<Root><Data>True</Data><Data1>true</Data1><Data2>false</Data2><DataEmpty></DataEmpty></Root>');
        $this->assertNull(Functions::tryToBool($elem->NoNode));
        $this->assertEquals(true, Functions::tryToBool($elem->Data));
        $this->assertEquals(true, Functions::tryToBool($elem->Data1));
        $this->assertEquals(false, Functions::tryToBool($elem->Data2));
        $this->assertNotNull(Functions::tryToBool($elem->DataEmpty));
        $this->assertEquals(false, Functions::tryToBool($elem->DataEmpty));
    }

    public function testtryToInt()
    {
        $elem = new \SimpleXMLElement('<Root><Data>123</Data><Data1>0</Data1><Data2>invalid value</Data2><DataEmpty></DataEmpty></Root>');
        $this->assertNull(Functions::tryToInt($elem->NoNode));
        $this->assertEquals(123, Functions::tryToInt($elem->Data));
        $this->assertEquals(0, Functions::tryToInt($elem->Data1));
        $this->assertEquals(0, Functions::tryToInt($elem->Data2));
        $this->assertNotNull(Functions::tryToInt($elem->DataEmpty));
        $this->assertEquals(0, Functions::tryToInt($elem->DataEmpty));
    }

    public function testtryToString()
    {
        $elem = new \SimpleXMLElement('<Root><Data>123</Data><Data1>0</Data1><Data2></Data2></Root>');
        $this->assertNull(Functions::tryToString($elem->NoNode));
        $this->assertEquals('123', Functions::tryToString($elem->Data));
        $this->assertEquals('0', Functions::tryToString($elem->Data1));
        $this->assertNotNull(Functions::tryToString($elem->Data2));
        $this->assertEquals('', Functions::tryToString($elem->Data2));
    }
}
