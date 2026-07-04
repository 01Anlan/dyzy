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

use Tos\Model\CreateBucketInput;
use Tos\Model\DeleteBucketInput;

require_once 'TestCommon.php';

class DeleteBucketTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();

        $bucket1 = self::genRandomString(10);
        $output = $client->createBucket(new CreateBucketInput($bucket1));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $bucket2 = self::genRandomString(10);
        $client = self::getClient();
        $output = $client->createBucket(new CreateBucketInput($bucket2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $count = 0;
        $output = $client->listBuckets();
        foreach ($output->getBuckets() as $bucket) {
            if ($bucket->getName() === $bucket1 || $bucket->getName() === $bucket2) {
                $count++;
                $this->assertTrue(strlen($bucket->getLocation()) > 0);
            }
        }
        $this->assertEquals(2, $count);

        $output = $client->deleteBucket(new DeleteBucketInput($bucket1));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $output = $client->deleteBucket(new DeleteBucketInput($bucket2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $count = 0;
        $output = $client->listBuckets();
        foreach ($output->getBuckets() as $bucket) {
            if ($bucket->getName() === $bucket1 || $bucket->getName() === $bucket2) {
                $count++;
                $this->assertTrue(strlen($bucket->getLocation()) > 0);
            }
        }
        $this->assertEquals(0, $count);
    }
}