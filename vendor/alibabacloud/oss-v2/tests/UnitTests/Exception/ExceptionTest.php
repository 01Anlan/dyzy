<?php

namespace UnitTests\Exception;

use AlibabaCloud\Oss\V2\Exception\OperationException;

class ExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testOperationException()
    {
        $e = new OperationException('GetBucketAcl', new \InvalidArgumentException('abc is invalid'));
        $this->assertNotNull($e);
        $this->assertEquals('Operation error GetBucketAcl: abc is invalid', $e->getMessage());
    }
}
