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

use Tos\Model\GetObjectToFileInput;
use Tos\Model\PutObjectFromFileInput;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class GetObjectToFileTest extends TestCommon
{

    public function testNormal()
    {

        $client = self::getClient();
        $bucket = self::$fixedBucket;

        $key = self::genRandomString(10);
        $keyFolder = self::genRandomString(10) . '/';

        $sampleFilePath = './temp/' . self::genRandomString(10) . '.txt';
        try {
            $this->createSampleFile($sampleFilePath, 1000);
            $sourceFileMd5 = base64_encode(md5_file($sampleFilePath, true));

            $output = $client->putObjectFromFile(new PutObjectFromFileInput($bucket, $key, $sampleFilePath));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertTrue(strlen($output->getETag()) > 0);

            $output = $client->putObject(new PutObjectInput($bucket, $keyFolder));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertTrue(strlen($output->getETag()) > 0);

            // 验证下载文件
            $filePaths = ['./temp/1.txt', __DIR__ . '/temp/2.txt',
                './temp/aaa/bbb/ccc/ddd/3.txt', './temp',
                './temp2/aaa/bbb/ccc/', './temp2/aaa/bbb/ccc/1.txt',
                './temp/a/b.file', './temp/a/b/c.file', './temp/a/d/e/f/g.file',
                './temp/a/b/d.file', './temp/a/g/', './temp/a/f'];

            foreach ($filePaths as $filePath) {
                $input = new GetObjectToFileInput($bucket, $key);
                if (is_file($filePath)) {
                    unlink($filePath);
                }

                $input->setFilePath($filePath);
                $output = $client->getObjectToFile($input);
                $this->assertTrue(strlen($output->getRequestId()) > 0);
                $this->assertTrue(file_exists($output->getRealFilePath()));
                $this->assertEquals($sourceFileMd5, base64_encode(md5_file($output->getRealFilePath(), true)));
            }

            $this->assertTrue(is_file('./temp/1.txt'));
            $this->assertTrue(is_file(__DIR__ . '/temp/2.txt'));
            $this->assertTrue(is_file('./temp/aaa/bbb/ccc/ddd/3.txt'));
            $this->assertTrue(is_file('./temp' . DIRECTORY_SEPARATOR . $key));
            $this->assertTrue(is_file('./temp2/aaa/bbb/ccc/' . DIRECTORY_SEPARATOR . $key));
            $this->assertTrue(is_file('./temp2/aaa/bbb/ccc/1.txt'));
            $this->assertTrue(is_file('./temp/a/b.file'));
            $this->assertTrue(is_file('./temp/a/b/c.file'));
            $this->assertTrue(is_file('./temp/a/d/e/f/g.file'));
            $this->assertTrue(is_file('./temp/a/b/d.file'));
            $this->assertTrue(is_file('./temp/a/g/' . DIRECTORY_SEPARATOR . $key));
            $this->assertTrue(is_file('./temp/a/f'));

            // 验证下载文件夹
            $folderPaths = ['./temp/', './temp/123/456/', './temp', './temp/888'];
            foreach ($folderPaths as $folderPath) {
                if (is_file($folderPath)) {
                    unlink($folderPath);
                }
                $input = new GetObjectToFileInput($bucket, $keyFolder);
                $input->setFilePath($folderPath);
                $output = $client->getObjectToFile($input);
                $this->assertTrue(strlen($output->getRequestId()) > 0);
                $this->assertTrue(file_exists($output->getRealFilePath()));
            }

            $this->assertTrue(is_dir('./temp/' . DIRECTORY_SEPARATOR . $keyFolder));
            $this->assertTrue(is_dir('./temp/123/456/' . DIRECTORY_SEPARATOR . $keyFolder));
            $this->assertTrue(is_dir('./temp' . DIRECTORY_SEPARATOR . $keyFolder));
            $this->assertTrue(is_file('./temp/888'));

        } finally {
            if (file_exists($sampleFilePath)) {
                unlink($sampleFilePath);
            }
        }

    }
}