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
use Tos\Model\Enum;
use Tos\Model\GetObjectACLInput;
use Tos\Model\Grant;
use Tos\Model\Grantee;
use Tos\Model\Owner;
use Tos\Model\PutObjectACLInput;
use Tos\Model\PutObjectInput;

require_once 'TestCommon.php';

class PutObjectACLTest extends TestCommon
{
    public function testNormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $key = self::genRandomString(10);
        $data = 'hello world';

        $input = new PutObjectInput($bucket, $key, $data);
        $input->setACL(Enum::ACLPublicRead);
        $output = $client->putObject($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);
        $this->assertTrue(strlen($output->getETag()) > 0);


        $output = $client->getObjectACL(new GetObjectACLInput($bucket, $key));
        $this->assertEquals(count($output->getGrants()), 1);
        $this->assertEquals($output->getGrants()[0]->getPermission(), Enum::PermissionRead);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getType(), Enum::GranteeGroup);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getCanned(), Enum::CannedAllUsers);

        $output = $client->putObjectACL(new PutObjectACLInput($bucket, $key, Enum::ACLPublicReadWrite));
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObjectACL(new GetObjectACLInput($bucket, $key));
        $this->assertEquals(count($output->getGrants()), 2);
        foreach ($output->getGrants() as $grant) {
            $this->assertTrue($grant->getPermission() === Enum::PermissionRead || $grant->getPermission() === Enum::PermissionWrite);
            $this->assertEquals($grant->getGrantee()->getType(), Enum::GranteeGroup);
            $this->assertEquals($grant->getGrantee()->getCanned(), Enum::CannedAllUsers);
        }

        $ownerId = $output->getOwner()->getID();

        $input = new PutObjectACLInput($bucket, $key);
        $input->setOwner(new Owner($ownerId));
        $input->setGrants([0 => new Grant(new Grantee('', Enum::GranteeGroup, Enum::CannedAuthenticatedUsers), Enum::PermissionRead)]);
        $output = $client->putObjectACL($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObjectACL(new GetObjectACLInput($bucket, $key));
        $this->assertEquals(count($output->getGrants()), 1);
        $this->assertEquals($output->getGrants()[0]->getPermission(), Enum::PermissionRead);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getType(), Enum::GranteeGroup);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getCanned(), Enum::CannedAuthenticatedUsers);


        $input = new PutObjectACLInput($bucket, $key);
        $input->setOwner(new Owner($ownerId));
        $input->setGrants([0 => new Grant(new Grantee($ownerId, Enum::GranteeUser), Enum::PermissionFullControl)]);
        $output = $client->putObjectACL($input);
        $this->assertTrue(strlen($output->getRequestId()) > 0);

        $output = $client->getObjectACL(new GetObjectACLInput($bucket, $key));
        $this->assertEquals(count($output->getGrants()), 1);
        $this->assertEquals($output->getGrants()[0]->getPermission(), Enum::PermissionFullControl);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getType(), Enum::GranteeUser);
        $this->assertEquals($output->getGrants()[0]->getGrantee()->getID(), $ownerId);
    }

    public function testAbnormal()
    {
        $client = self::getClient();
        $bucket = self::$fixedBucket;
        $nonExistsBucket = self::$nonExistsBucket;
        $key = self::genRandomString(10);

        try {
            $client->putObjectACL(new PutObjectACLInput($nonExistsBucket, $key, Enum::ACLPublicReadWrite));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $client->putObjectACL(new PutObjectACLInput($bucket, self::genRandomString(400), Enum::ACLPublicReadWrite));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }

        try {
            $client->getObjectACL(new GetObjectACLInput($nonExistsBucket, $key));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchBucket');
        }

        try {
            $client->getObjectACL(new GetObjectACLInput($bucket, self::genRandomString(400)));
            $this->assertTrue(false);
        } catch (TosServerException $ex) {
            $this->assertEquals($ex->getStatusCode(), 404);
            $this->assertEquals($ex->getErrorCode(), 'NoSuchKey');
        }
    }
}