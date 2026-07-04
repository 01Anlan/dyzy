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

use GuzzleHttp\Psr7\Utils;
use Tos\Model\CompleteMultipartUploadInput;
use Tos\Model\CreateMultipartUploadInput;
use Tos\Model\GetObjectInput;
use Tos\Model\ListPartsInput;
use Tos\Model\UploadedPart;
use Tos\Model\UploadPartCopyInput;
use Tos\Model\UploadPartFromFileInput;
use Tos\Model\UploadPartInput;

require_once 'TestCommon.php';

class MultipartUploadTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $input = new CreateMultipartUploadInput($bucket, $key);
        $output = $client->createMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getUploadID()) > 0);

        $uploadId = $output->getUploadID();

        $sampleFilePath = './temp/' . self::genRandomString(10) . '.txt';
        if (!file_exists($sampleFilePath)) {
            self::createSampleFile($sampleFilePath);
            self::$bigSampleFilePath = $sampleFilePath;
        }

        $fileSize = filesize($sampleFilePath);
        $source = md5_file($sampleFilePath);

        $firstPartSize = 5 * 1024 * 1024;
        $partNumber1 = 1;
        $partNumber2 = 2;
        $file = null;
        try {
            $file = fopen($sampleFilePath, 'r');
            $input = new UploadPartInput($bucket, $key, $uploadId, $partNumber1);
            $input->setContentLength($firstPartSize);
            $input->setContent($file);
            $output = $client->uploadPart($input);
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $etag1 = $output->getETag();
            echo 'upload part etag1 done' . PHP_EOL;
        } finally {
            if (is_resource($file)) {
                fclose($file);
            }
        }

        $parts = [];
        $parts[] = new UploadedPart($partNumber1, $etag1);
        $file = null;
        try {
            $file = fopen($sampleFilePath, 'r');
            fseek($file, $firstPartSize, 0);
            $input = new UploadPartInput($bucket, $key, $uploadId, $partNumber2);
            $input->setContentLength(filesize($sampleFilePath) - $firstPartSize);
            $input->setContent($file);
            $output = $client->uploadPart($input);
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $etag2 = $output->getETag();
            echo 'upload part etag2 done' . PHP_EOL;
        } finally {
            if (is_resource($file)) {
                fclose($file);
            }
        }

        $parts[] = new UploadedPart($partNumber2, $etag2);

        $input = new ListPartsInput($bucket, $key, $uploadId);
        $output = $client->listParts($input);
        $this->assertEquals(count($output->getParts()), 2);

        $input = new CompleteMultipartUploadInput($bucket, $key, $uploadId, $parts);
        $output = $client->completeMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        echo 'complete parts done' . PHP_EOL;

        $dstFilePath = null;
        try {
            $input = new GetObjectInput($bucket, $key);
            $output = $client->getObject($input);
            $dstFilePath = $sampleFilePath . '_bak';
            $file = fopen($dstFilePath, 'w');
            Utils::copyToStream(Utils::streamFor($output->getContent()), Utils::streamFor($file));
            $this->assertEquals(md5_file($sampleFilePath), md5_file($dstFilePath));
            echo 'get object to check done' . PHP_EOL;
        } finally {
            if ($dstFilePath && file_exists($dstFilePath)) {
                unlink($dstFilePath);
            }
            if (is_resource($file)) {
                fclose($file);
            }
        }

        $dstFilePath2 = null;
        $key2 = self::genRandomString(10);
        $input = new CreateMultipartUploadInput($bucket, $key2);
        $output = $client->createMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getUploadID()) > 0);

        $uploadId = $output->getUploadID();

        $input = new UploadPartFromFileInput($bucket, $key2, $uploadId, $partNumber1, $sampleFilePath);
        $input->setPartSize($firstPartSize);
        $output = $client->uploadPartFromFile($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $etag1 = $output->getETag();
        $parts = [];
        $parts[] = new UploadedPart($partNumber1, $etag1);
        echo 'upload part etag1 done' . PHP_EOL;

        $input = new UploadPartFromFileInput($bucket, $key2, $uploadId, $partNumber2, $sampleFilePath);
        $input->setOffset($firstPartSize);
        $output = $client->uploadPartFromFile($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $etag2 = $output->getETag();
        $parts[] = new UploadedPart($partNumber2, $etag2);
        echo 'upload part etag2 done' . PHP_EOL;

        $input = new CompleteMultipartUploadInput($bucket, $key2, $uploadId, $parts);
        $output = $client->completeMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        echo 'complete parts done' . PHP_EOL;
        try {
            $input = new GetObjectInput($bucket, $key2);
            $output = $client->getObject($input);
            $dstFilePath2 = $sampleFilePath . '_bak2';
            $file = fopen($dstFilePath2, 'w');
            Utils::copyToStream(Utils::streamFor($output->getContent()), Utils::streamFor($file));
            $this->assertEquals(md5_file($sampleFilePath), md5_file($dstFilePath2));
            echo 'get object to check done' . PHP_EOL;
        } finally {
            if ($dstFilePath2 && file_exists($dstFilePath2)) {
                unlink($dstFilePath2);
            }
            if (is_resource($file)) {
                fclose($file);
            }
        }

        $key2 = self::genRandomString(10);
        $input = new CreateMultipartUploadInput($bucket, $key2);
        $output = $client->createMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getUploadID()) > 0);
        $uploadId = $output->getUploadID();
        $parts = [];

        $offset = 0;
        $partSize = 5 * 1024 * 1024;
        $input = new UploadPartCopyInput($bucket, $key2, $uploadId, 1, $bucket, $key);
        $input->setCopySourceRange('bytes=' . $offset . '-' . strval($partSize - 1));
        $output = $client->uploadPartCopy($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $parts[] = new UploadedPart(1, $output->getETag());

        $input = new UploadPartCopyInput($bucket, $key2, $uploadId, 2, $bucket, $key);
        $input->setCopySourceRange('bytes=' . strval($partSize) . '-' . strval($fileSize - 1));
        $output = $client->uploadPartCopy($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $parts[] = new UploadedPart(2, $output->getETag());

        $input = new CompleteMultipartUploadInput($bucket, $key2, $uploadId, $parts);
        $output = $client->completeMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        try {
            $input = new GetObjectInput($bucket, $key2);
            $output = $client->getObject($input);
            $dstFilePath2 = $sampleFilePath . '_bak2';
            $file = fopen($dstFilePath2, 'w');
            Utils::copyToStream(Utils::streamFor($output->getContent()), Utils::streamFor($file));
            $this->assertEquals($source, md5_file($dstFilePath2));
        } finally {
            if ($dstFilePath2 && file_exists($dstFilePath2)) {
                unlink($dstFilePath2);
            }
            if (is_resource($file)) {
                fclose($file);
            }
        }
    }

}