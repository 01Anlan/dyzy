<?php

namespace IntegrationTests;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestIntegration.php';

use AlibabaCloud\Oss\V2 as Oss;
use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\NoSeekStream;
use GuzzleHttp\Psr7\BufferStream;

class ClientListCloudBoxesTest extends TestIntegration
{
    public function testListCloudBoxes()
    {
        $client = $this->getDefaultClient();

        // ListCloudBoxes
        try {        
            $result = $client->listCloudBoxes(new Oss\Models\ListCloudBoxesRequest());
            $this->assertEquals(200, $result->statusCode);
            $this->assertEquals('OK', $result->status);
            $this->assertEquals(True, count($result->headers) > 0);
            $this->assertEquals(24, strlen($result->requestId));
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListCloudBoxes', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('You are forbidden to oss-cloudbox:ListCloudBoxes', $se->getErrorMessage());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            } else {
                $this->assertTrue(false, "should not here");
            }
        }
    }

    public function testListCloudBoxesFail()
    {
        $client = $this->getInvalidAkClient();

        try {
            $result = $client->listCloudBoxes(new Oss\Models\ListCloudBoxesRequest());
            $this->assertTrue(false, "should not here");
        } catch (Oss\Exception\OperationException $e) {
            $this->assertStringContainsString('Operation error ListCloudBoxes', $e);
            $se = $e->getPrevious();
            $this->assertInstanceOf(Oss\Exception\ServiceException::class, $se);
            if ($se instanceof Oss\Exception\ServiceException) {
                $this->assertEquals('InvalidAccessKeyId', $se->getErrorCode());
                $this->assertEquals(403, $se->getStatusCode());
                $this->assertEquals(24, strlen($se->getRequestId()));
            }
        }
    }
}
