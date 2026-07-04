<?php
namespace UnitTests\Credentials;

use AlibabaCloud\Oss\V2\Credentials\Credentials;
use AlibabaCloud\Oss\V2\Credentials\AnonymousCredentialsProvider;
use AlibabaCloud\Oss\V2\Credentials\CredentialsProviderFunc;

class CredentialsTest extends \PHPUnit\Framework\TestCase
{

    public function testCredentials() 
    {
        $credentials = new Credentials("", "");
        $this->assertEquals("", $credentials->getAccessKeyId());
        $this->assertEquals("", $credentials->getAccessKeySecret());
        $this->assertNull($credentials->getSecurityToken());
        $this->assertNull($credentials->getExpiration());
        $this->assertFalse($credentials->hasKeys());
        $this->assertFalse($credentials->isExpired());

        $credentials = new Credentials("ak", "sk");
        $this->assertEquals("ak", $credentials->getAccessKeyId());
        $this->assertEquals("sk", $credentials->getAccessKeySecret());
        $this->assertNull($credentials->getSecurityToken());
        $this->assertNull($credentials->getExpiration());
        $this->assertTrue($credentials->hasKeys());
        $this->assertFalse($credentials->isExpired());

        $credentials = new Credentials("ak", "sk", "token");
        $this->assertEquals("ak", $credentials->getAccessKeyId());
        $this->assertEquals("sk", $credentials->getAccessKeySecret());
        $this->assertEquals("token", $credentials->getSecurityToken());
        $this->assertNull($credentials->getExpiration());
        $this->assertTrue($credentials->hasKeys());
        $this->assertFalse($credentials->isExpired());


        $expiration = new \DateTime('now', new \DateTimeZone('UTC'));
        $expiration->modify("+ 100 seconds");
        $credentials = new Credentials("ak", "sk", "token", $expiration);
        $this->assertEquals("ak", $credentials->getAccessKeyId());
        $this->assertEquals("sk", $credentials->getAccessKeySecret());
        $this->assertEquals("token", $credentials->getSecurityToken());
        $this->assertNotNull($credentials->getExpiration());
        $this->assertTrue($credentials->hasKeys());
        $this->assertFalse($credentials->isExpired());

        $expiration = new \DateTime('now', new \DateTimeZone('UTC'));
        $expiration->modify("- 100 seconds");
        $credentials = new Credentials("ak", "sk", "token", $expiration);
        $this->assertEquals("ak", $credentials->getAccessKeyId());
        $this->assertEquals("sk", $credentials->getAccessKeySecret());
        $this->assertEquals("token", $credentials->getSecurityToken());
        $this->assertNotNull($credentials->getExpiration());
        $this->assertTrue($credentials->hasKeys());
        $this->assertTrue($credentials->isExpired());

    }

    public function testAnonymousCredentialsProvider() 
    {
        $provider = new AnonymousCredentialsProvider();
        $credentials = $provider->getCredentials();
        $this->assertEquals("", $credentials->getAccessKeyId());
        $this->assertEquals("", $credentials->getAccessKeySecret());
        $this->assertNull($credentials->getSecurityToken());
        $this->assertNull($credentials->getExpiration());
        $this->assertFalse($credentials->isExpired());
     }

     public function testCredentialsProviderFunc() 
     {
         $provider = new CredentialsProviderFunc( function () {
            return new Credentials("ak", "sk");
         });
         $credentials = $provider->getCredentials();
         $this->assertEquals("ak", $credentials->getAccessKeyId());
         $this->assertEquals("sk", $credentials->getAccessKeySecret());
         $this->assertNull($credentials->getSecurityToken());
         $this->assertNull($credentials->getExpiration());
         $this->assertTrue($credentials->hasKeys());
         $this->assertFalse($credentials->isExpired());
      }     
}
