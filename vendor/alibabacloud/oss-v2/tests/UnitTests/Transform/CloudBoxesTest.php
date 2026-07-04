<?php

namespace UnitTests\Transform;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform\CloudBoxes;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Utils;

class CloudBoxesTest extends \PHPUnit\Framework\TestCase
{
    public function testFromListCloudBoxes()
    {
        $request = new Models\ListCloudBoxesRequest();
        $input = CloudBoxes::fromListCloudBoxes($request);

        $request = new Models\ListCloudBoxesRequest(marker: '', maxKeys: 10, prefix: '/');
        $input = CloudBoxes::fromListCloudBoxes($request);
        $this->assertEquals('', $input->getParameters()['cloudboxes']);
        $this->assertEquals('', $input->getParameters()['marker']);
        $this->assertEquals('10', $input->getParameters()['max-keys']);
        $this->assertEquals('/', $input->getParameters()['prefix']);
    }

    public function testToListCloudBoxes()
    {
        // empty output
        $output = new OperationOutput();
        $result = CloudBoxes::toListCloudBoxes($output);
        $this->assertEquals('', $result->status);
        $this->assertEquals(0, $result->statusCode);
        $this->assertEquals('', $result->requestId);
        $this->assertEquals(0, count($result->headers));

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<ListCloudBoxResult>
  <Owner>
     <ID>51264</ID>
    <DisplayName>51264</DisplayName>
  </Owner>
  <CloudBoxes>
    <CloudBox>
      <ID>cb-f8z7yvzgwfkl9q0h****</ID>
      <Name>bucket1</Name>
      <Region>cn-shanghai</Region>
      <ControlEndpoint>cb-f8z7yvzgwfkl9q0h****.cn-shanghai.oss-cloudbox-control.aliyuncs.com</ControlEndpoint>
      <DataEndpoint>cb-f8z7yvzgwfkl9q0h****.cn-shanghai.oss-cloudbox.aliyuncs.com</DataEndpoint>
    </CloudBox>
    <CloudBox>
      <ID>cb-f9z7yvzgwfkl9q0h****</ID>
      <Name>bucket2</Name>
      <Region>cn-hangzhou</Region>
      <ControlEndpoint>cb-f9z7yvzgwfkl9q0h****.cn-hangzhou.oss-cloudbox-control.aliyuncs.com</ControlEndpoint>
      <DataEndpoint>cb-f9z7yvzgwfkl9q0h****.cn-hangzhou.oss-cloudbox.aliyuncs.com</DataEndpoint>
    </CloudBox>
  </CloudBoxes>
</ListCloudBoxResult>';
        $output = new OperationOutput(
            'OK',
            200,
            ['x-oss-request-id' => '123', 'content-type' => 'application/xml'],
            body: Utils::streamFor($body)
        );
        $result = CloudBoxes::toListCloudBoxes($output);
        $this->assertEquals('OK', $result->status);
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('123', $result->requestId);
        $this->assertEquals(2, count($result->headers));
        $this->assertEquals('123', $result->headers['x-oss-request-id']);
        $this->assertEquals('application/xml', $result->headers['content-type']);
        $this->assertEquals('51264', $result->listCloudBoxes->owner->id);
        $this->assertEquals('51264', $result->listCloudBoxes->owner->displayName);
        $this->assertEquals(2, count($result->listCloudBoxes->cloudBoxes->cloudBox));
        $this->assertEquals('cb-f8z7yvzgwfkl9q0h****', $result->listCloudBoxes->cloudBoxes->cloudBox[0]->id);
        $this->assertEquals('cb-f8z7yvzgwfkl9q0h****.cn-shanghai.oss-cloudbox.aliyuncs.com', $result->listCloudBoxes->cloudBoxes->cloudBox[0]->dataEndpoint);
        $this->assertEquals('cb-f8z7yvzgwfkl9q0h****.cn-shanghai.oss-cloudbox-control.aliyuncs.com', $result->listCloudBoxes->cloudBoxes->cloudBox[0]->controlEndpoint);
        $this->assertEquals('bucket1', $result->listCloudBoxes->cloudBoxes->cloudBox[0]->name);
        $this->assertEquals('cn-shanghai', $result->listCloudBoxes->cloudBoxes->cloudBox[0]->region);
        $this->assertEquals('cb-f9z7yvzgwfkl9q0h****', $result->listCloudBoxes->cloudBoxes->cloudBox[1]->id);
        $this->assertEquals('cb-f9z7yvzgwfkl9q0h****.cn-hangzhou.oss-cloudbox.aliyuncs.com', $result->listCloudBoxes->cloudBoxes->cloudBox[1]->dataEndpoint);
        $this->assertEquals('cb-f9z7yvzgwfkl9q0h****.cn-hangzhou.oss-cloudbox-control.aliyuncs.com', $result->listCloudBoxes->cloudBoxes->cloudBox[1]->controlEndpoint);
        $this->assertEquals('bucket2', $result->listCloudBoxes->cloudBoxes->cloudBox[1]->name);
        $this->assertEquals('cn-hangzhou', $result->listCloudBoxes->cloudBoxes->cloudBox[1]->region);
    }
}
