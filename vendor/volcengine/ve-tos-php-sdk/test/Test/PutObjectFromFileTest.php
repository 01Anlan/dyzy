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


use Tos\Model\Enum;
use Tos\Model\GetObjectInput;
use Tos\Model\PutObjectFromFileInput;

require_once 'TestCommon.php';

class PutObjectFromFileTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;

        $key = self::genRandomString(10);

        $file = null;
        $sampleFilePath = './temp/' . self::genRandomString(10) . '.txt';
        try {
            $this->createSampleFile($sampleFilePath, 1000);

            $input = new PutObjectFromFileInput($bucket, $key, $sampleFilePath);
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
            $input->setContentMD5(base64_encode(md5_file($sampleFilePath, true)));
            $output = $client->putObjectFromFile($input);
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertTrue(strlen($output->getETag()) > 0);

            $sourceFileMd5 = base64_encode(md5_file($sampleFilePath, true));

            $output = $client->getObject(new GetObjectInput($bucket, $key));
            $this->assertTrue(strlen($output->getRequestId()) > 0);
            $this->assertEquals(base64_encode(md5($output->getContent()->getContents(), true)), $sourceFileMd5);
            $output->getContent()->close();

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
        } finally {
            if (file_exists($sampleFilePath)) {
                unlink($sampleFilePath);
            }

            if (is_resource($file)) {
                fclose($file);
            }
        }
    }
}