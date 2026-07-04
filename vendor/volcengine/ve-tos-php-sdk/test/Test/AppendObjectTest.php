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
use Tos\Model\AppendObjectInput;
use Tos\Model\DeleteObjectInput;
use Tos\Model\Enum;
use Tos\Model\GetObjectInput;

require_once 'TestCommon.php';

class AppendObjectTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);

        $data = '';
        for ($i = 0; $i < 20000; $i++) {
            $data .= uniqid();
        }

        $input = new AppendObjectInput($bucket, $key);
        $input->setContent($data);
        $output = $client->appendObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue($output->getNextAppendOffset() > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals(count($output->getMeta()), 0);

        $client->deleteObject(new DeleteObjectInput($bucket, $key));

        $input = new AppendObjectInput($bucket, $key);
        $input->setStorageClass(Enum::StorageClassStandard);
        $input->setACL(Enum::ACLPublicRead);
        $input->setContentDisposition('test-disposition');
        $expires = time() + 3600;
        $input->setExpires($expires);
        $input->setMeta(['aaa' => 'bbb', '中文键' => '中文值']);
        $input->setContentEncoding('test-encoding');
        $input->setContentLanguage('test-language');
        $input->setContentType('text/plain');
        $input->setWebsiteRedirectLocation('http://test-website-redirection-location');
        $input->setContent($data);
        $output = $client->appendObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue($output->getNextAppendOffset() > 0);
        $nextAppendOffset = $output->getNextAppendOffset();

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

        // append many times
        $input->setOffset($nextAppendOffset);
        $output = $client->appendObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue($output->getNextAppendOffset() > 0);

        $nextAppendOffset = $output->getNextAppendOffset();
        $input->setOffset($nextAppendOffset);
        $output = $client->appendObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue($output->getNextAppendOffset() > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data . $data . $data);
        $output->getContent()->close();
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);

        try {
            $input = new AppendObjectInput(self::$nonExistsBucket, $key);
            $input->setContent('hello world');
            $client->appendObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new AppendObjectInput($bucket, $key);
            $input->setContent('hello world');
            $input->setStorageClass('unknown_storage_class');
            $client->appendObject($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $input = new AppendObjectInput($bucket, $key);
            $input->setContent('hello world');
            $input->setACL('unknown_acl');
            $client->appendObject($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }
    }
}