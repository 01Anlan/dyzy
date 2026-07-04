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
use Tos\Model\AbortMultipartUploadInput;
use Tos\Model\CompleteMultipartUploadInput;
use Tos\Model\CreateBucketInput;
use Tos\Model\CreateMultipartUploadInput;
use Tos\Model\ListMultipartUploadsInput;
use Tos\Model\ListPartsInput;
use Tos\Model\UploadedPart;
use Tos\Model\UploadPartInput;

require_once 'TestCommon.php';

class MultipartListTest extends TestCommon
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
        $uploadIds = [];
        $index = 0;
        foreach ($keysMap as $item) {
            $keys[] = $prefix . $item;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $keys[$index]));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertTrue(strlen($output->getUploadID()) > 0);
            $uploadIds[$keys[$index]] = $output->getUploadID();
            $index++;
        }

        $keysFromServer = [];
        $uploadIdsFromServer = [];
        $input = new ListMultipartUploadsInput($bucket, 10);
        $input->setPrefix($prefix);
        while (true) {
            $output = $client->listMultipartUploads($input);
            foreach ($output->getUploads() as $upload) {
                $keysFromServer[] = $upload->getKey();
                $uploadIdsFromServer[$upload->getKey()] = $upload->getUploadID();
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setKeyMarker($output->getNextKeyMarker());
            $input->setUploadIDMarker($output->getNextUploadIdMarker());
        }

        sort($keys);
        sort($keysFromServer);
        $this->assertEquals($keys, $keysFromServer);

        ksort($uploadIds);
        ksort($uploadIdsFromServer);
        $this->assertEquals($keys, $keysFromServer);

        for ($i = 0; $i < 50; $i++) {
            $output = $client->abortMultipartUpload(new AbortMultipartUploadInput($bucket, $keys[$i], $uploadIds[$keys[$i]]));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        $keys = array_slice($keys, 50);
        $keysFromServer = [];

        $input = new ListMultipartUploadsInput($bucket, 10);
        $input->setPrefix($prefix);
        while (true) {
            $output = $client->listMultipartUploads($input);
            foreach ($output->getUploads() as $upload) {
                $keysFromServer[] = $upload->getKey();
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setKeyMarker($output->getNextKeyMarker());
            $input->setUploadIDMarker($output->getNextUploadIdMarker());
        }
        sort($keysFromServer);
        $this->assertEquals($keys, $keysFromServer);

        $keys = [];
        $prefix2 = self::genRandomString(5) . '/';

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'abc/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'abc/123/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'bcd/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'bcd/456/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'cde/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        for ($i = 0; $i < 3; $i++) {
            $key = $prefix2 . 'cde/789/' . $keysArray[$i];
            $keys[] = $key;
            $output = $client->createMultipartUpload(new CreateMultipartUploadInput($bucket, $key));
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
        $input = new ListMultipartUploadsInput($bucket, 1000);
        $input->setPrefix($prefix);
        $input->setDelimiter('/');
        $client = self::getClient();
        while (true) {
            $output = $client->listMultipartUploads($input);

            foreach ($output->getUploads() as $upload) {
                $keysFromServer[] = $upload->getKey();
            }

            foreach ($output->getCommonPrefixes() as $commonPrefix) {
                $commonPrefixes[] = $commonPrefix->getPrefix();
            }

            if (!$output->isTruncated()) {
                break;
            }

            $input->setKeyMarker($output->getNextKeyMarker());
            $input->setUploadIDMarker($output->getNextUploadIdMarker());
        }
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        try {
            $input = new CreateMultipartUploadInput(self::$nonExistsBucket, self::genRandomString(3));
            $client->createMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new ListMultipartUploadsInput(self::$nonExistsBucket);
            $client->listMultipartUploads($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new UploadPartInput(self::$nonExistsBucket, self::genRandomString(3), '123');
            $input->setContent('hello world');
            $client->uploadPart($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new AbortMultipartUploadInput(self::$nonExistsBucket, self::genRandomString(3), '123');
            $client->abortMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        $key = self::genRandomString(3);
        $output = $client->createMultipartUpload(new CreateMultipartUploadInput(self::$fixedBucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getUploadID()) > 0);

        try {
            $input = new UploadPartInput(self::$fixedBucket, self::genRandomString(400), $output->getUploadID());
            $input->setContent('hello world');
            $client->uploadPart($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new UploadPartInput(self::$fixedBucket, $key, '123');
            $input->setContent('hello world');
            $client->uploadPart($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new ListPartsInput(self::$fixedBucket, self::genRandomString(400), $output->getUploadID());
            $client->listParts($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new ListPartsInput(self::$fixedBucket, $key, '123');
            $client->listParts($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new CompleteMultipartUploadInput(self::$fixedBucket, self::genRandomString(400), $output->getUploadID(), [0 => new UploadedPart(1, '123')]);
            $client->completeMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new CompleteMultipartUploadInput(self::$fixedBucket, $key, '123', [0 => new UploadedPart(1, '123')]);
            $client->completeMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new AbortMultipartUploadInput(self::$fixedBucket, self::genRandomString(400), $output->getUploadID());
            $client->abortMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }

        try {
            $input = new AbortMultipartUploadInput(self::$fixedBucket, $key, '123');
            $client->abortMultipartUpload($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchUpload');
        }
    }
}