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

if (is_file(__DIR__ . '/../autoloadTest.php')) {
    require_once __DIR__ . '/../autoloadTest.php';
}

if (is_file(__DIR__ . '/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
}

if (is_file(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

use PHPUnit\Framework\TestCase;
use Tos\Exception\TosServerException;
use Tos\Model\AbortMultipartUploadInput;
use Tos\Model\CreateBucketInput;
use Tos\Model\DeleteBucketInput;
use Tos\Model\DeleteObjectInput;
use Tos\Model\HeadBucketInput;
use Tos\Model\ListMultipartUploadsInput;
use Tos\Model\ListObjectVersionsInput;
use Tos\TosClient;

class TestCommon extends TestCase
{

    protected $buckets = [];
    protected $clearBucket = true;
    protected static $client;
    protected static $httpsClient;
    protected static $fixedBucket;
    protected static $nonExistsBucket;
    protected static $bigSampleFilePath;

    public function addBucketToTearDown($bucket)
    {
        $this->buckets[] = $bucket;
    }

    public function tearDown(): void
    {
        if ($this->clearBucket) {
            foreach ($this->buckets as $bucket) {
                self::cleanBucket($bucket);
            }
            $this->clearBucket = [];
        }
    }

    public static function setUpBeforeClass(): void
    {
        while (true) {
            $bucket = self::genRandomString(10);
            try {
                self::getClient()->createBucket(new CreateBucketInput($bucket));
                self::$fixedBucket = $bucket;
                break;
            } catch (TosServerException $ex) {
                if ($ex->getStatusCode() !== 409) {
                    throw $ex;
                }
            }
        }

        while (true) {
            $bucket = self::genRandomString(30);
            try {
                self::getClient()->headBucket(new HeadBucketInput($bucket));
            } catch (TosServerException $ex) {
                if ($ex->getStatusCode() === 404) {
                    self::$nonExistsBucket = $bucket;
                    break;
                }
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$fixedBucket) {
            self::cleanBucket(self::$fixedBucket);
        }

        if (file_exists(self::$bigSampleFilePath)) {
            unlink(self::$bigSampleFilePath);
        }
    }

    public static function cleanBucket($bucket)
    {
        $canDeleteBucket = true;
        try {
            $input = new ListObjectVersionsInput($bucket, 1000);
            while (true) {
                $output = self::getClient()->listObjectVersions($input);
                foreach ($output->getVersions() as $version) {
                    self::getClient()->deleteObject(new DeleteObjectInput($bucket, $version->getKey(), $version->getVersionID()));
                }

                foreach ($output->getDeleteMarkers() as $deleteMarker) {
                    self::getClient()->deleteObject(new DeleteObjectInput($bucket, $deleteMarker->getKey(), $deleteMarker->getVersionID()));
                }

                if (!$output->isTruncated()) {
                    break;
                }
                $input->setKeyMarker($output->getNextKeyMarker());
                $input->setVersionIDMarker($output->getNextVersionIDMarker());
            }

            $input = new ListMultipartUploadsInput($bucket, 1000);
            while (true) {
                $output = self::getClient()->listMultipartUploads($input);
                foreach ($output->getUploads() as $upload) {
                    self::getClient()->abortMultipartUpload(new AbortMultipartUploadInput($bucket, $upload->getKey(), $upload->getUploadID()));
                }
                if (!$output->isTruncated()) {
                    break;
                }
                $input->setKeyMarker($output->getNextKeyMarker());
                $input->setUploadIDMarker($output->getNextUploadIdMarker());
            }
        } catch (\RuntimeException $ex) {
            $canDeleteBucket = false;
        }

        if ($canDeleteBucket) {
            try {
                self::getClient()->deleteBucket(new DeleteBucketInput($bucket));
            } catch (\RuntimeException $ex) {
                echo $ex->getMessage() . PHP_EOL;
            }
        }
    }

    /**
     * @return TosClient
     */
    public static function getClient()
    {
        if (!self::$client) {
            self::$client = new TosClient(
                'test-region',
                getenv('TOS_ACCESS_KEY'),
                getenv('TOS_SECRET_KEY'),
                getenv('TOS_ENDPOINT')
            );
        }
        return self::$client;
    }

    /**
     * @return TosClient
     */
    public static function getHttpsClient()
    {
        if (!self::$httpsClient) {
            self::$httpsClient = new TosClient(
                'test-region',
                getenv('TOS_ACCESS_KEY'),
                getenv('TOS_SECRET_KEY'),
                getenv('TOS_HTTPS_ENDPOINT')
            );
        }
        return self::$httpsClient;
    }

    public static function genRandomString($length = 3)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function getNonExistsBucket()
    {
        return self::$nonExistsBucket;
    }

    public static function createSampleFile($filePath, $count = 500000)
    {
        if (file_exists($filePath)) {
            return;
        }
        $filePath = iconv('UTF-8', 'GBK', $filePath);
        if (is_string($filePath) && $filePath !== '') {
            $fp = null;
            $dir = dirname($filePath);
            try {
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                if (($fp = fopen($filePath, 'w'))) {
                    for ($i = 0; $i < $count; $i++) {
                        fwrite($fp, uniqid() . "\n");
                        fwrite($fp, uniqid() . "\n");
                        if ($i % 100 === 0) {
                            fflush($fp);
                        }
                    }
                }
            } finally {
                if ($fp) {
                    fclose($fp);
                }
            }
        }
    }
}