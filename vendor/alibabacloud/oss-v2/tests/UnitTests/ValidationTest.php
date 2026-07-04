<?php

namespace UnitTests;

use AlibabaCloud\Oss\V2\Validation;

class ValidationTest extends \PHPUnit\Framework\TestCase
{
    public function testIsValidRegion()
    {
        $this->assertTrue(Validation::isValidRegion("xxx"));
        $this->assertFalse(Validation::isValidRegion("XXXqwe123"));
        $this->assertFalse(Validation::isValidRegion("XX"));
        $this->assertFalse(Validation::isValidRegion("/X"));
        $this->assertFalse(Validation::isValidRegion(""));
    }

    public function testIsValidateBucketName()
    {
        $this->assertTrue(Validation::isValidBucketName("xxx"));
        $this->assertFalse(Validation::isValidBucketName("XXXqwe123"));
        $this->assertFalse(Validation::isValidBucketName("XX"));
        $this->assertFalse(Validation::isValidBucketName("/X"));
        $this->assertFalse(Validation::isValidBucketName(""));
        $this->assertTrue(Validation::isValidBucketName(str_repeat('a', 3)));
        $this->assertTrue(Validation::isValidBucketName(str_repeat('a', 63)));
        $this->assertFalse(Validation::isValidBucketName(str_repeat('a', 64)));
        $this->assertFalse(Validation::isValidBucketName(str_repeat('a', 2)));
    }

    public function testIsValidateObjectName()
    {
        $this->assertTrue(Validation::isValidObjectName("xxx"));
        $this->assertTrue(Validation::isValidObjectName("xxx23"));
        $this->assertTrue(Validation::isValidObjectName("12321-xxx"));
        $this->assertTrue(Validation::isValidObjectName("x"));
        $this->assertTrue(Validation::isValidObjectName("/aa"));
        $this->assertTrue(Validation::isValidObjectName("\\aa"));
        $this->assertFalse(Validation::isValidObjectName(""));
        $this->assertTrue(Validation::isValidObjectName(str_repeat('a', 1)));
        $this->assertTrue(Validation::isValidObjectName(str_repeat('a', 1023)));
        $this->assertFalse(Validation::isValidObjectName(str_repeat('a', 1024)));
    }
}
