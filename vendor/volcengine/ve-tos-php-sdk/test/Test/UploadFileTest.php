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

use Tos\Model\GetObjectInput;
use Tos\Model\UploadFileInput;

require_once 'TestCommon.php';

class UploadFileTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $sampleFilePath = './temp/' . self::genRandomString(10) . '.txt';
        $key = self::genRandomString(10);
        $key2 = self::genRandomString(10);

        if (!file_exists($sampleFilePath)) {
            self::createSampleFile($sampleFilePath);
            self::$bigSampleFilePath = $sampleFilePath;
        }

        $sourceFileMd5 = base64_encode(md5_file($sampleFilePath, true));

        $input = new UploadFileInput($bucket, $key, $sampleFilePath);
        $input->setPartSize(5 * 1024 * 1024);
        $input->setTaskNum(3);
        $output = $client->uploadFile($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(base64_encode(md5($output->getContent()->getContents(), true)), $sourceFileMd5);
        $output->getContent()->close();

        $input->setEnableCheckpoint(true);
        $input->setTaskNum(1);
        $input->setKey($key2);
        $output = $client->uploadFile($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $output = $client->getObject(new GetObjectInput($bucket, $key2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(base64_encode(md5($output->getContent()->getContents(), true)), $sourceFileMd5);
        $output->getContent()->close();

    }

}