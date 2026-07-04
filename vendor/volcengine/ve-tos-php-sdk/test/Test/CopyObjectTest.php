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
use Tos\Model\CopyObjectInput;
use Tos\Model\CreateBucketInput;
use Tos\Model\Enum;
use Tos\Model\GetObjectInput;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class CopyObjectTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $data = 'hello world';

        $input = new PutObjectInput($bucket, $key, $data);
        $input->setMeta(['aaa' => 'bbb', 'ccc' => 'ddd']);
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $dstKey = $key . '-bak';
        $input = new CopyObjectInput($bucket, $dstKey, $bucket, $key);
        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $dstKey));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $bucket2 = self::genRandomString(40);
        $output = $client->createBucket(new CreateBucketInput($bucket2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->addBucketToTearDown($bucket2);

        $input = new CopyObjectInput($bucket2, $dstKey, $bucket, $key);
        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket2, $dstKey));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        // 包含所有参数 + COPY
        $dstKey2 = self::genRandomString(40);
        $input = new CopyObjectInput($bucket2, $dstKey2, $bucket, $key);
        $input->setACL(Enum::ACLPublicRead);
        $input->setContentDisposition('test-disposition');
        $expires = time() + 3600;
        $input->setExpires($expires);
        $input->setMeta(['aaa' => 'bbb', '中文键' => '中文值']);
        $input->setContentEncoding('test-encoding');
        $input->setContentLanguage('test-language');
        $input->setContentType('text/plain');
        $input->setStorageClass(Enum::StorageClassIa);
        $input->setWebsiteRedirectLocation('http://test-website-redirection-location');
        $input->setMetadataDirective(Enum::MetadataDirectiveCopy);

        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket2, $dstKey2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals($input->getStorageClass(), $output->getStorageClass());
        $this->assertEquals(count($output->getMeta()), 2);
        $this->assertEquals($output->getMeta()['aaa'], 'bbb');
        $this->assertEquals($output->getMeta()['ccc'], 'ddd');

        $input->setStorageClass(Enum::StorageClassStandard);
        $input->setMetadataDirective(Enum::MetadataDirectiveReplace);
        $output = $client->copyObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);


        $output = $client->getObject(new GetObjectInput($bucket2, $dstKey2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals($input->getContentDisposition(), $output->getContentDisposition());
        $this->assertEquals($input->getExpires(), $output->getExpires());
        $this->assertEquals($input->getContentEncoding(), $output->getContentEncoding());
        $this->assertEquals($input->getContentLanguage(), $output->getContentLanguage());
        $this->assertEquals($input->getContentType(), $output->getContentType());
        $this->assertEquals(count($output->getMeta()), 2);
        $this->assertEquals($output->getMeta()['aaa'], 'bbb');
        $this->assertEquals($output->getMeta()['中文键'], '中文值');
        $this->assertEquals($input->getStorageClass(), $output->getStorageClass());
        // todo xsj
//        $this->assertEquals($input->getWebsiteRedirectLocation(), $output->getWebsiteRedirectLocation());

        try {
            $client->copyObject(new CopyObjectInput($bucket2, $dstKey2, $bucket, $key, '123'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidArgument');
        }
    }
}