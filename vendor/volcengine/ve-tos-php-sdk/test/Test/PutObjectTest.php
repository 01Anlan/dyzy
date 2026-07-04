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
use Tos\Model\DeleteObjectInput;
use Tos\Model\Enum;
use Tos\Model\GetObjectInput;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class PutObjectTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $data = 'hello world';

        $testKeys = [];
        $testKeys[] = '  ';
        $testKeys[] = 'a';
        $testKeys[] = '仅包含中文';
        $testKeys[] = 'にほんご';
        $testKeys[] = 'Ελληνικά';
        $testKeys[] = '（!-_.*()/&$@=;:+ ,?\{^}%`]>[~<#|\'"）';
        $testKeys[] = '//';

        // 上传各种有效字符的对象
        foreach ($testKeys as $testKey) {
            $output = $client->putObject(new PutObjectInput($bucket, $testKey, $data));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertTrue(strlen($output->getETag()) > 0);
            $this->assertEquals(200, $output->getStatusCode());

            $output = $client->getObject(new GetObjectInput($bucket, $testKey));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertEquals($output->getContent()->getContents(), $data);
            $output->getContent()->close();

            $output = $client->deleteObject(new DeleteObjectInput($bucket, $testKey));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
        }

        //必选参数上传对象
        $input = new PutObjectInput($bucket, $key, $data);
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals(count($output->getMeta()), 0);

        // 测试流式上传
        $key2 = self::genRandomString(10);
        $getOutput = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($getOutput->getRequestId()) > 0);
        $output = $client->putObject(new PutObjectInput($bucket, $key2, $getOutput->getContent()));
        $getOutput->getContent()->close();
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key2));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();
        $this->assertEquals(count($output->getMeta()), 0);

        $input->setStorageClass(Enum::StorageClassIa);
        $input->setACL(Enum::ACLPublicRead);
        $input->setContentDisposition('attachment; filename=中文.txt');
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

        // 上传大小为 0 的对象
        $input = new PutObjectInput($bucket, $key, null);
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);

        $output = $client->getObject(new GetObjectInput($bucket, $key));
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), '');
        $output->getContent()->close();
        $this->assertEquals($output->getContentLength(), 0);
        $this->assertEquals($output->getContentType(), 'binary/octet-stream');

        // 测试自动设置 MIME 类型
        $keys = ['test', 'test.TXT', 'test.xml', 'test.jpg', 'test.HTML', 'test.json', 'test.mp4'];
        $keysContentTypes = ['binary/octet-stream', 'text/plain', 'application/xml', 'image/jpeg',
            'text/html', 'application/json', 'video/mp4'];
        $contentTypes = ['text/plain', ''];
        $index = 0;
        foreach ($keys as $key) {
            foreach ($contentTypes as $contentType) {
                $input = new PutObjectInput($bucket, $key, null);
                $input->setContentType($contentType);
                $output = $client->putObject($input);
                $this->assertTrue(strlen($output->getRequestId()) > 0);
                $this->assertTrue(strlen($output->getETag()) > 0);

                $output = $client->getObject(new GetObjectInput($bucket, $key));
                $this->assertTrue(strlen($output->getRequestId()) > 0);
                $this->assertEquals($output->getContent()->getContents(), '');
                $output->getContent()->close();
                $this->assertEquals($output->getContentLength(), 0);
                if ($contentType) {
                    $this->assertEquals($output->getContentType(), $contentType);
                } else {
                    $this->assertEquals($output->getContentType(), $keysContentTypes[$index]);
                }
            }
            $index++;
        }
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;

        // /验证各种错误的对象名参数
        try {
            $client->putObject(new PutObjectInput($bucket, chr(1)));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->putObject(new PutObjectInput($bucket, ''));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->putObject(new PutObjectInput($bucket, self::genRandomString(697)));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $client->putObject(new PutObjectInput($bucket, '\\a'));
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $input = new PutObjectInput($bucket, 'abc');
            $input->setStorageClass('unknown_storage_class');
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $input = new PutObjectInput($bucket, 'abc');
            $input->setACL('unknown_acl');
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $input = new PutObjectInput($bucket, 'abc');
            $input->setSSECAlgorithm('unknown_ssec_algorithm');
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosClientException $ex) {
            $this->assertTrue(strlen($ex->getMessage()) > 0);
        }

        try {
            $input = new PutObjectInput(self::$nonExistsBucket, 'abc');
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $input = new GetObjectInput($bucket, self::genRandomString(400));
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }

        try {
            $input = new GetObjectInput($bucket, self::$nonExistsBucket, '', '123');
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidArgument');
        }

        try {
            $data = 'hello world';
            $input = new PutObjectInput($bucket, self::genRandomString(3), $data);
            $input->setContentMD5(base64_encode($data));
            $client->putObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidDigest');
        }
    }

    public function testSsec()
    {
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(3);
        $data = 'hello world';
        $input = new PutObjectInput($bucket, $key, $data);
        $input->setContentMD5(base64_encode(md5($data, true)));
        $input->setSSECAlgorithm('AES256');
        $ssecKey = '01234567890123456789012345678901';
        $input->setSSECKey(base64_encode($ssecKey));
        $input->setSSECKeyMD5(base64_encode(md5($ssecKey, true)));
        $output = self::getHttpsClient()->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $input2 = new GetObjectInput($bucket, $key);
        $input2->setSSECAlgorithm($input->getSSECAlgorithm());
        $input2->setSSECKey($input->getSSECKey());
        $input2->setSSECKeyMD5($input->getSSECKeyMD5());
        $output = self::getHttpsClient()->getObject($input2);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        try {
            self::getHttpsClient()->getObject(new GetObjectInput($bucket, $key));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 400);
            $this->assertEquals($ex->getErrorCode(), 'InvalidRequest');
        }
    }

    public function testCas()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(3);
        $data = 'hello world';

        $output = $client->putObject(new PutObjectInput($bucket, $key, $data));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $etag = $output->getETag();
        $noneMatchEtag = md5('hi world');
        $now = time();

        $input = new GetObjectInput($bucket, $key);
        $input->setIfMatch($etag);
        $input->setIfNoneMatch($noneMatchEtag);
        $input->setIfModifiedSince($now - 3600);
        $input->setIfUnmodifiedSince($now + 3600);
        $output = $client->getObject($input);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $lastModified = $output->getLastModified();

        $input = new GetObjectInput($bucket, $key);
        $input->setIfMatch($noneMatchEtag);
        try {
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 412);
            $this->assertEquals($ex->getErrorCode(), 'PreconditionFailed');
        }

        $input = new GetObjectInput($bucket, $key);
        $input->setIfNoneMatch($etag);
        try {
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 304);
        }

        $input = new GetObjectInput($bucket, $key);
        $input->setIfUnmodifiedSince($lastModified - 3600);
        try {
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 412);
            $this->assertEquals($ex->getErrorCode(), 'PreconditionFailed');
        }


        $input = new GetObjectInput($bucket, $key);
        $input->setIfModifiedSince($lastModified + 3600);
        try {
            $client->getObject($input);
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 304);
        }
    }

    public function testRange()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(3);
        $data = 'hello world';

        $output = $client->putObject(new PutObjectInput($bucket, $key, $data));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $input = new GetObjectInput($bucket, $key);
        $input->setRangeStart(0);
        $input->setRangeEnd(0);
        $output = $client->getObject($input);
        $this->assertEquals($output->getContent()->getContents(), substr($data, 0, 1));
        $output->getContent()->close();

        $input = new GetObjectInput($bucket, $key);
        $input->setRange('bytes=3-4');
        $output = $client->getObject($input);
        $this->assertEquals($output->getContent()->getContents(), substr($data, 3, 4 - 3 + 1));
        $output->getContent()->close();
    }

    public function testResponseHttpHeader()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(3);
        $data = 'hello world';
        $now = time();

        $output = $client->putObject(new PutObjectInput($bucket, $key, $data));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $input = new GetObjectInput($bucket, $key);
        $input->setResponseContentType('test-content-type');
        $input->setResponseCacheControl('test-cache-control');
        $input->setResponseContentDisposition('test-disposition');
        $input->setResponseContentEncoding('test-content-encoding');
        $input->setResponseContentLanguage('test-content-language');
        $input->setResponseExpires($now);
        $output = $client->getObject($input);
        $this->assertEquals($output->getContent()->getContents(), $data);
        $output->getContent()->close();

        $this->assertEquals($output->getContentType(), $input->getResponseContentType());
        $this->assertEquals($output->getCacheControl(), $input->getResponseCacheControl());
        $this->assertEquals($output->getContentLanguage(), $input->getResponseContentLanguage());
        $this->assertEquals($output->getContentDisposition(), $input->getResponseContentDisposition());
        $this->assertEquals($output->getContentEncoding(), $input->getResponseContentEncoding());
        $this->assertEquals($output->getExpires(), $input->getResponseExpires());
    }
}