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
use Tos\Model\DeleteObjectInput;
use Tos\Model\Enum;
use Tos\Model\GetObjectInput;
use Tos\Model\HeadObjectInput;
use Tos\Model\PutObjectInput;
use Tos\Model\SetObjectMetaInput;

require_once 'TestCommon.php';

class SetObjectMetaTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $data = 'hello world';

        $input = new PutObjectInput($bucket, $key, $data);
        $input->setStorageClass(Enum::StorageClassIa);
        $input->setACL(Enum::ACLPublicRead);
        $input->setContentDisposition('test-disposition');
        $expires = time() + 3600;
        $input->setExpires($expires);
        $input->setMeta(['aaa' => 'bbb', '中文键' => '中文值']);
        $input->setContentEncoding('test-encoding');
        $input->setContentLanguage('test-language');
        $input->setContentType('text/plain');
        $input->setWebsiteRedirectLocation('http://test-website-redirection-location');
        $input->setContentMD5(base64_encode(md5($data, true)));

        // 所有参数上传对象
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $this->assertEquals(strlen($data), $output->getContentLength());
        $this->assertEquals($input->getStorageClass(), $output->getStorageClass());
        $this->assertEquals($input->getContentDisposition(), $output->getContentDisposition());
        $this->assertEquals($input->getExpires(), $output->getExpires());
        $this->assertEquals($input->getContentEncoding(), $output->getContentEncoding());
        $this->assertEquals($input->getContentLanguage(), $output->getContentLanguage());
        $this->assertEquals($input->getContentType(), $output->getContentType());
        $this->assertEquals($input->getWebsiteRedirectLocation(), $output->getWebsiteRedirectLocation());
        $this->assertEquals(count($output->getMeta()), 2);
        $this->assertEquals($output->getMeta()['aaa'], 'bbb');
        $this->assertEquals($output->getMeta()['中文键'], '中文值');

        $input = new GetObjectInput($bucket, $key);
        $input->setResponseContentDisposition('attachment; filename="中文.txt"');
        $output = $client->getObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals($input->getResponseContentDisposition(), $output->getContentDisposition());
        echo $output->getContentDisposition() . PHP_EOL;

        $input = new SetObjectMetaInput($bucket, $key);
        $input->setContentDisposition('test-disposition-new');
        $expires = time() + 7200;
        $input->setExpires($expires);
        $input->setMeta(['ccc' => 'ddd', '中文键-new' => '中文值-new']);
        $input->setContentEncoding('test-encoding-new');
        $input->setContentLanguage('test-language-new');
        $input->setContentType('text/plain-new');

        $output = $client->setObjectMeta($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->headObject(new HeadObjectInput($bucket, $key));
        $this->assertEquals($input->getContentDisposition(), $output->getContentDisposition());
        $this->assertEquals($input->getExpires(), $output->getExpires());
        $this->assertEquals($input->getContentEncoding(), $output->getContentEncoding());
        $this->assertEquals($input->getContentLanguage(), $output->getContentLanguage());
        $this->assertEquals($input->getContentType(), $output->getContentType());
        $this->assertEquals(count($output->getMeta()), 2);
        $this->assertEquals($output->getMeta()['ccc'], 'ddd');
        $this->assertEquals($output->getMeta()['中文键-new'], '中文值-new');
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        try {
            $client->headObject(new HeadObjectInput(self::$nonExistsBucket, 'abc'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
        }

        try {
            $client->setObjectMeta(new SetObjectMetaInput(self::$nonExistsBucket, 'abc', ['aaa' => 'bbb']));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $client->setObjectMeta(new SetObjectMetaInput(self::$fixedBucket, self::genRandomString(400)));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            // todo xsj
//            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }

        $output = $client->deleteObject(new DeleteObjectInput(self::$fixedBucket, self::genRandomString(400)));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        try {
            $client->deleteObject(new DeleteObjectInput(self::$nonExistsBucket, 'abc'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        $key = self::genRandomString(10);
        $output = $client->putObject(new PutObjectInput(self::$fixedBucket, $key, 'hello world'));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);
        try {
            $client->setObjectMeta(new SetObjectMetaInput(self::$fixedBucket, $key, [], '123'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidArgument');
        }

        try {
            $client->headObject(new HeadObjectInput(self::$fixedBucket, $key, '123'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
        }

        try {
            $client->deleteObject(new DeleteObjectInput(self::$fixedBucket, $key, '123'));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidArgument');
        }
    }
}