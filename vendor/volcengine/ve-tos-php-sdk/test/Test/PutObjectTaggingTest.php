<?php
/**
 * Copyright (2024) Volcengine
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
use Tos\Helper\Helper;
use Tos\Model\CompleteMultipartUploadInput;
use Tos\Model\CopyObjectInput;
use Tos\Model\CreateMultipartUploadInput;
use Tos\Model\DeleteObjectTaggingInput;
use Tos\Model\Enum;
use Tos\Model\GetObjectInput;
use Tos\Model\GetObjectTaggingInput;
use Tos\Model\PutObjectInput;
use Tos\Model\PutObjectTaggingInput;
use Tos\Model\Tag;
use Tos\Model\TagSet;
use Tos\Model\UploadedPart;
use Tos\Model\UploadPartInput;

require_once 'TestCommon.php';

class PutObjectTaggingTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;

        $key = self::genRandomString(10);

        $data = 'hello world';

        $input = new PutObjectInput($bucket, $key, $data);
        $input->setACL(Enum::ACLPublicRead);
        $input->setTagging('key1=value1&Key2=Value2');
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 2);
        foreach ($output->getTagSet()->getTags() as $tag) {
            if ($tag->getKey() === 'key1') {
                $this->assertEquals($tag->getValue(), 'value1');
            } else {
                $this->assertEquals($tag->getKey(), 'Key2');
                $this->assertEquals($tag->getValue(), 'Value2');
            }
        }

        $output = $client->deleteObjectTagging(new DeleteObjectTaggingInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 0);

        $input = new PutObjectTaggingInput($bucket, $key);
        $input->setTagSet(new TagSet([
            new Tag("key1", "value1"),
            new Tag("key/ 2", "value/ 2")
        ]));
        $output = $client->putObjectTagging($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 2);
        foreach ($output->getTagSet()->getTags() as $tag) {
            if ($tag->getKey() === 'key1') {
                $this->assertEquals($tag->getValue(), 'value1');
            } else {
                $this->assertEquals($tag->getKey(), 'key/ 2');
                $this->assertEquals($tag->getValue(), 'value/ 2');
            }
        }

        $key2 = self::genRandomString(10);
        $input = new CopyObjectInput($bucket, $key2, $bucket, $key);
        $input->setTagging('key3=value3&Key4=Value4');
        $input->setTaggingDirective(Enum::TaggingDirectiveCopy);
        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 2);
        foreach ($output->getTagSet()->getTags() as $tag) {
            if ($tag->getKey() === 'key1') {
                $this->assertEquals($tag->getValue(), 'value1');
            } else {
                $this->assertEquals($tag->getKey(), 'key/ 2');
                $this->assertEquals($tag->getValue(), 'value/ 2');
            }
        }

        $input = new CopyObjectInput($bucket, $key2, $bucket, $key);
        $input->setTagging('key3=value3&Key4=Value4');
        $input->setTaggingDirective(Enum::TaggingDirectiveReplace);
        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);
        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 2);
        foreach ($output->getTagSet()->getTags() as $tag) {
            if ($tag->getKey() === 'key3') {
                $this->assertEquals($tag->getValue(), 'value3');
            } else {
                $this->assertEquals($tag->getKey(), 'Key4');
                $this->assertEquals($tag->getValue(), 'Value4');
            }
        }

        $key3 = self::genRandomString(10);
        $input = new CreateMultipartUploadInput($bucket, $key3);
        $input->setTagging('key4=value4&Key5=Value5');
        $output = $client->createMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getUploadID()) > 0);

        $uploadId = $output->getUploadID();
        $input = new UploadPartInput($bucket, $key3, $uploadId, 1);
        $input->setContent($data);
        $output = $client->uploadPart($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $etag1 = $output->getETag();

        $parts = [];
        $parts[] = new UploadedPart(1, $etag1);
        $input = new CompleteMultipartUploadInput($bucket, $key3, $uploadId, $parts);
        $output = $client->completeMultipartUpload($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key3));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $output = $client->getObjectTagging(new GetObjectTaggingInput($bucket, $key3));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals(count($output->getTagSet()->getTags()), 2);
        foreach ($output->getTagSet()->getTags() as $tag) {
            if ($tag->getKey() === 'key4') {
                $this->assertEquals($tag->getValue(), 'value4');
            } else {
                $this->assertEquals($tag->getKey(), 'Key5');
                $this->assertEquals($tag->getValue(), 'Value5');
            }
        }
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;

        $key = self::genRandomString(10);
        $data = 'hello world';

        $input = new PutObjectTaggingInput($bucket, $key);
        $input->setTagSet(new TagSet([
            new Tag("key1", "value1"),
            new Tag("key/ 2", "value/ 2")
        ]));
        try {
            $client->putObjectTagging($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }

        $input = new PutObjectInput($bucket, $key, $data);
        $input->setACL(Enum::ACLPublicRead);
        $input->setTagging(Helper::urlencodeWithSafe('中文键=中文值'));
        try {
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidTag');
        }

        $input->setTagging('');
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);
        $input = new PutObjectTaggingInput($bucket, $key);
        $input->setTagSet(new TagSet([
            new Tag("key1", "value1"),
            new Tag("中文键", "中文值")
        ]));
        try {
            $client->putObjectTagging($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidTag');
        }
    }
}