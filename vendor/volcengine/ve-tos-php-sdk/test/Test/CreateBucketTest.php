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

use Tos\Exception\TosClientException;
use Tos\Exception\TosServerException;
use Tos\Model\CreateBucketInput;
use Tos\Model\Enum;
use Tos\Model\HeadBucketInput;

require_once 'TestCommon.php';

final class CreateBucketTest extends TestCommon
{

    public function testNormal()
    {
        $client = self::getClient();
        // 必选参数创建桶
        $bucket1 = self::genRandomString(40);
        $output = $client->createBucket(new CreateBucketInput($bucket1));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->addBucketToTearDown($bucket1);

        $output = $client->headBucket(new HeadBucketInput($bucket1));
        $this->assertEquals(Enum::StorageClassStandard, $output->getStorageClass());
        $this->assertTrue(Enum::AzRedundancySingleAz === $output->getAzRedundancyType() || strlen($output->getAzRedundancyType()) === 0);
        $this->assertTrue(strlen($output->getRegion()) > 0);

        // 所有参数创建桶
        $bucket2 = self::genRandomString(40);
        $input = new CreateBucketInput($bucket2);
        $input->setACL(Enum::ACLPublicRead);
        $input->setAzRedundancy(Enum::AzRedundancyMultiAz);
        $input->setStorageClass(Enum::StorageClassIa);
        $output = $client->createBucket($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->addBucketToTearDown($bucket2);

        $output = $client->headBucket(new HeadBucketInput($bucket2));
        $this->assertEquals(Enum::StorageClassIa, $output->getStorageClass());
        $this->assertTrue(Enum::AzRedundancyMultiAz === $output->getAzRedundancyType() || strlen($output->getAzRedundancyType()) === 0);
        $this->assertTrue(strlen($output->getRegion()) > 0);
    }

    public function testAbnormal()
    {
        // 验证各种错误的创桶参数
        $client = self::getClient();
        // 查询不存在的桶
        $bucket3 = self::genRandomString(10);
        try {
            $client->headBucket(new HeadBucketInput($bucket3));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals(404, $ex->getStatusCode());
        }

        $bucket1 = self::genRandomString(10);
        $input = new CreateBucketInput($bucket1);
        $input->setACL('unknown_acl');
        try {
            $client->createBucket($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        $input = new CreateBucketInput($bucket1);
        $input->setStorageClass('unknown_storage_class');
        try {
            $client->createBucket($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        $input = new CreateBucketInput($bucket1);
        $input->setAzRedundancy('unknown_az_redundancy');
        try {
            $client->createBucket($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->createBucket(new CreateBucketInput('a#b#c'));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->createBucket(new CreateBucketInput('a'));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->createBucket(new CreateBucketInput('-abc'));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->createBucket(new CreateBucketInput('abc-'));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }
    }

}
