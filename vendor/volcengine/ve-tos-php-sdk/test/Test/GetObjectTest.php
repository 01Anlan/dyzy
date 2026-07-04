<?php
/**
 * Copyright (2022) Volcengine
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Tos\Test;

use Tos\Exception\TosServerException;
use Tos\Model\DeleteObjectInput;
use Tos\Model\GetObjectInput;
use Tos\Model\PutObjectFromFileInput;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class GetObjectTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $data = 'hello world';

        $output = $client->putObject(new PutObjectInput($bucket, $key, $data));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $output = $client->putObjectFromFile(new PutObjectFromFileInput($bucket, $key, __DIR__ . '/1.jpg'));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $input = new GetObjectInput($bucket, $key);
        $input->setProcess('image/info');
        $output = $client->getObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $error = json_decode($output->getContent()->getContents(), true);
        $this->assertTrue(isset($error['FileSize']));
        $this->assertTrue(isset($error['Format']));
        $this->assertTrue(isset($error['ImageHeight']));
        $this->assertTrue(isset($error['ImageWidth']));

        $output->getContent()->close();
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);

        $output = $client->deleteObject(new DeleteObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        try {
            $client->getObject(new GetObjectInput($bucket, $key));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }
    }
}