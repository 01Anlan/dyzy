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
use Tos\Model\CreateBucketInput;
use Tos\Model\DeleteMultiObjectsInput;
use Tos\Model\ListObjectsInput;
use Tos\Model\ListObjectVersionsInput;
use Tos\Model\ObjectTobeDeleted;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class ListObjectsTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        // 必选参数创建桶
        $bucket = self::genRandomString(10);
        $output = $client->createBucket(new CreateBucketInput($bucket));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->addBucketToTearDown($bucket);

        $prefix = self::genRandomString(5) . '/';
        $keysMap = [];
        for ($i = 0; $i < 100; $i++) {
            $key = self::genRandomString(10);
            $keysMap[$key] = $key;
        }

        $keysArray = [];
        foreach ($keysMap as $item) {
            $keysArray[] = $item;
        }

        $keys = [];
        $index = 0;
        foreach ($keysMap as $item) {
            $keys[] = $prefix . $item;
            $output = $client->putObject(new PutObjectInput($bucket, $keys[$index], 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $index++;
        }

        $keysFromServer = [];
        $input = new ListObjectsInput($bucket, 10);
        $input->setPrefix($prefix);
        while (true) {
            $output = $client->listObjects($input);
            foreach ($output->getContents() as $content) {
                $keysFromServer[] = $content->getKey();
                $this->assertTrue(strlen($content->getHashCrc64ecma()) > 0);
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setMarker($output->getNextMarker());
        }

        sort($keys);
        sort($keysFromServer);
        $this->assertEquals($keys, $keysFromServer);

        $objects = [];
        for ($i = 0; $i < 50; $i++) {
            $objects[] = new ObjectTobeDeleted($keys[$i]);
        }

        $output = $client->deleteMultiObjects(new DeleteMultiObjectsInput($bucket, $objects));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getDeleted()), 50);
        $this->assertEquals(count($output->getError()), 0);

        $keys = array_slice($keys, 50);
        $keysFromServer = [];
        $input = new ListObjectsInput($bucket, 10);
        $input->setPrefix($prefix);
        while (true) {
            $output = $client->listObjects($input);
            foreach ($output->getContents() as $content) {
                $keysFromServer[] = $content->getKey();
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setMarker($output->getNextMarker());
        }
        sort($keysFromServer);
        $this->assertEquals($keys, $keysFromServer);

        $keys = [];
        $prefix2 = self::genRandomString(5) . '/';

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'abc/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'abc/123/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'bcd/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'bcd/456/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'cde/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'cde/789/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->putObject(new PutObjectInput($bucket, $key, 'hello world'));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        $keysFromServer = [];
        $commonPrefixes = [];
        $commonPrefixes[] = $prefix2;
        while (count($commonPrefixes) > 0) {
            $prefix = $commonPrefixes[0];
            $commonPrefixes = array_slice($commonPrefixes, 1);
            $this->listByPrefix($bucket, $prefix, $keysFromServer, $commonPrefixes);
        }

        sort($keys);
        sort($keysFromServer);
        $this->assertEquals($keys, $keysFromServer);
    }

    private function listByPrefix($bucket, $prefix, array &$keysFromServer, array &$commonPrefixes)
    {
        $input = new ListObjectsInput($bucket, 1000);
        $input->setPrefix($prefix);
        $input->setDelimiter('/');
        $client = self::getClient();
        while (true) {
            $output = $client->listObjects($input);

            foreach ($output->getContents() as $content) {
                $keysFromServer[] = $content->getKey();
            }

            foreach ($output->getCommonPrefixes() as $commonPrefix) {
                $commonPrefixes[] = $commonPrefix->getPrefix();
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setMarker($output->getNextMarker());
        }
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        try {
            $input = new ListObjectsInput(self::$nonExistsBucket);
            $client->listObjects($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new ListObjectVersionsInput($input->getBucket());
            $client->listObjectVersions($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new DeleteMultiObjectsInput($input->getBucket());
            $input->setObjects([0 => new ObjectTobeDeleted('test-key')]);
            $client->deleteMultiObjects($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }
    }
}