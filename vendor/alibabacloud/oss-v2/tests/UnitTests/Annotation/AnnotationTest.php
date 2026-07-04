<?php
namespace UnitTests\Annotation;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'XmlObject.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ModelObject.php';

use AlibabaCloud\Oss\V2\Annotation\Functions;

class AnnotationTest extends \PHPUnit\Framework\TestCase
{
    public function testXmlAnnotation() 
    {
        $obj = new XmlObject();
        $ro = new \ReflectionObject($obj);
        $attributes = $ro->getAttributes();
        $this->assertCount(1, $attributes);
        $inst = $attributes[0]->newInstance();
        $this->assertNotNull($inst);
        $this->assertEquals("XmlRoot", $inst->name);

        $prop = $ro->getProperty("strValue");
        $this->assertNotNull($prop);
        $attributes = $prop->getAttributes();
        $this->assertCount(1, $attributes);
        $inst = $attributes[0]->newInstance();
        $this->assertNotNull($inst);
        $this->assertEquals("StrValue", $inst->rename);
        $this->assertEquals("string", $inst->type);

        $prop = $ro->getProperty("strValueLists");
        $this->assertNotNull($prop);
        $attributes = $prop->getAttributes();
        $this->assertCount(1, $attributes);
        $inst = $attributes[0]->newInstance();
        $this->assertNotNull($inst);
        $this->assertEquals("StrValueList", $inst->rename);
        $this->assertEquals("string", $inst->type);
    }

    public function testRequiredProperty() 
    {
        $obj = new ModelObject();
        $ro = new \ReflectionObject($obj);

        $prop = $ro->getProperty("strValue");
        $this->assertNotNull($prop);
        $this->assertTrue(Functions::isRequiredProperty($prop));

        $prop = $ro->getProperty("intValue");
        $this->assertNotNull($prop);
        $this->assertFalse(Functions::isRequiredProperty($prop));
    }

    public function testFunctions() 
    {
        $obj = new ModelObject();
        $ro = new \ReflectionObject($obj);

        $prop = $ro->getProperty("strValue");
        $this->assertNotNull($prop);
        
        $result = Functions::getXmlElementAnnotation($prop);
        $this->assertNull($result);

        $result = Functions::getTagAnnotation($prop);
        $this->assertNotNull($result);
        $this->assertEquals("input", $result->tag);
        $this->assertEquals("nop", $result->position);
        $this->assertEquals("StrValue", $result->rename);
        $this->assertEquals("string", $result->type);

        $result = Functions::getPropertyAnnotations($prop);
        $this->assertCount(2, $result);

        $this->assertEquals("input", $result[1]->tag);
        $this->assertEquals("nop", $result[1]->position);
        $this->assertEquals("StrValue", $result[1]->rename);
        $this->assertEquals("string", $result[1]->type);
    }
}
