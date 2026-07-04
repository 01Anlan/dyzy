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

use GuzzleHttp\Client;
use Tos\Model\Enum;
use Tos\Model\PreSignedURLInput;

require_once 'TestCommon.php';

class PreSignedURLTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = 'folder/' . self::genRandomString(10);
        $data = 'hello world';

        $httpClient = new Client(
            [
                'timeout' => 10,
                'allow_redirects' => false,
                'http_errors' => false,
            ]
        );

        $input = new PreSignedURLInput(Enum::HttpMethodPut, $bucket, $key);
        $input->setHeader(['Content-Type' => 'text/plain']);
        $output = $client->preSignedURL($input);


        $output = $httpClient->put($output->getSignedUrl(), ['headers' => $output->getSignedHeader(), 'body' => $data]);
        $this->assertEquals($output->getStatusCode(), 200);
        $output->getBody()->close();

        $input = new PreSignedURLInput(Enum::HttpMethodGet, $bucket, $key);
        $output = $client->preSignedURL($input);

        $output = $httpClient->get($output->getSignedUrl(), ['headers' => $output->getSignedHeader()]);
        $this->assertEquals($output->getStatusCode(), 200);
        $this->assertEquals($output->getBody()->getContents(), $data);
        $output->getBody()->close();

        $input = new PreSignedURLInput(Enum::HttpMethodDelete, $bucket, $key);
        $output = $client->preSignedURL($input);

        $output = $httpClient->delete($output->getSignedUrl(), ['headers' => $output->getSignedHeader()]);
        $this->assertEquals($output->getStatusCode(), 204);
        $output->getBody()->close();

        $input = new PreSignedURLInput(Enum::HttpMethodGet, $bucket, $key);
        $output = $client->preSignedURL($input);
        $output = $httpClient->get($output->getSignedUrl(), ['headers' => $output->getSignedHeader()]);
        $this->assertEquals($output->getStatusCode(), 404);
        $output->getBody()->close();


        $input = new PreSignedURLInput(Enum::HttpMethodPut, $bucket, $key);
        $input->setHeader(['Content-Type' => 'text/plain']);
        $output = $client->preSignedURL($input);
        $output = $httpClient->put($output->getSignedUrl(), ['body' => $data]);
        $this->assertEquals($output->getStatusCode(), 403);
        $output->getBody()->close();
    }
}