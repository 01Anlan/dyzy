<?php

namespace UnitTests\Types;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'ModelA.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'XmlModelA.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ModelPrivate.php';




class ModelTest extends \PHPUnit\Framework\TestCase
{
    public function testModelBaisc()
    {
        $m = new ModelA();
        $this->assertNull($m->strValue);
        $this->assertNull($m->intValue);
        $this->assertNull($m->boolValue);
        $this->assertNull($m->floatValue);

        #use getter
        $this->assertNull($m->getStrValue());
        $this->assertNull($m->getIntValue());
        $this->assertNull($m->getBoolValue());
        $this->assertNull($m->getFloatValue());

        #set from construct
        # all 
        $m = new ModelA(
            "str",
            1234,
            true,
            3.14
        );
        $this->assertEquals("str", $m->strValue);
        $this->assertEquals(1234, $m->intValue);
        $this->assertEquals(true, $m->boolValue);
        $this->assertEquals(3.14, $m->floatValue);

        $this->assertEquals("str", $m->getStrValue());
        $this->assertEquals(1234, $m->getIntValue());
        $this->assertEquals(true, $m->getBoolValue());
        $this->assertEquals(3.14, $m->getFloatValue());

        # part 
        $m = new ModelA(
            "str",
            null,
            null,
            3.14
        );
        $this->assertEquals("str", $m->strValue);
        $this->assertEquals(null, $m->intValue);
        $this->assertEquals(null, $m->boolValue);
        $this->assertEquals(3.14, $m->floatValue);

        $this->assertEquals("str", $m->getStrValue());
        $this->assertEquals(null, $m->getIntValue());
        $this->assertEquals(null, $m->getBoolValue());
        $this->assertEquals(3.14, $m->getFloatValue());

        #set from setter
        $m = new ModelA();

        $m->setStrValue("str-123");
        $m->setIntValue(321);
        $m->setBoolValue(false);
        $m->setFloatValue(1.2);

        $this->assertEquals("str-123", $m->strValue);
        $this->assertEquals(321, $m->intValue);
        $this->assertEquals(false, $m->boolValue);
        $this->assertEquals(1.2, $m->floatValue);

        $this->assertEquals("str-123", $m->getStrValue());
        $this->assertEquals(321, $m->getIntValue());
        $this->assertEquals(false, $m->getBoolValue());
        $this->assertEquals(1.2, $m->getFloatValue());

        # Chaining setter
        $m = new ModelA();
        $m->setStrValue("str-111")->setIntValue(555)->setBoolValue(true)->setFloatValue(2.2);
        $this->assertEquals("str-111", $m->getStrValue());
        $this->assertEquals(555, $m->getIntValue());
        $this->assertEquals(true, $m->getBoolValue());
        $this->assertEquals(2.2, $m->getFloatValue());
    }

    public function testModelTrait()
    {
        #set from construct
        $m = new ModelPrivate(
            "str",
            1234,
            true,
            3.14,
        );

        $this->assertEquals("str", $m->getStrValue());
        $this->assertEquals(1234, $m->getIntValue());
        $this->assertEquals(true, $m->getBoolValue());
        $this->assertEquals(3.14, $m->getFloatValue());

        $m->setStrValue('str-new')->setBoolValue(false)->setIntValue(321)->setFloatValue(1.12);

        $this->assertEquals("str-new", $m->getStrValue());
        $this->assertEquals(321, $m->getIntValue());
        $this->assertEquals(false, $m->getBoolValue());
        $this->assertEquals(1.12, $m->getFloatValue());
    }
}
