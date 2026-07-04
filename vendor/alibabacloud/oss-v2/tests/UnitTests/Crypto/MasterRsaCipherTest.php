<?php

namespace UnitTests\Crypto;

use AlibabaCloud\Oss\V2\Crypto\MasterRsaCipher;

class MasterRsaCipherTest extends \PHPUnit\Framework\TestCase
{
    const RSA_PUBLIC_KEY = <<<BBB
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCokfiAVXXf5ImFzKDw+XO/UByW
6mse2QsIgz3ZwBtMNu59fR5zttSx+8fB7vR4CN3bTztrP9A6bjoN0FFnhlQ3vNJC
5MFO1PByrE/MNd5AAfSVba93I6sx8NSk5MzUCA4NJzAUqYOEWGtGBcom6kEF6MmR
1EKib1Id8hpooY5xaQIDAQAB
-----END PUBLIC KEY-----
BBB;

    const RSA_PRIVATE_KEY = <<<BBB
-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKiR+IBVdd/kiYXM
oPD5c79QHJbqax7ZCwiDPdnAG0w27n19HnO21LH7x8Hu9HgI3dtPO2s/0DpuOg3Q
UWeGVDe80kLkwU7U8HKsT8w13kAB9JVtr3cjqzHw1KTkzNQIDg0nMBSpg4RYa0YF
yibqQQXoyZHUQqJvUh3yGmihjnFpAgMBAAECgYA49RmCQ14QyKevDfVTdvYlLmx6
kbqgMbYIqk+7w611kxoCTMR9VMmJWgmk/Zic9mIAOEVbd7RkCdqT0E+xKzJJFpI2
ZHjrlwb21uqlcUqH1Gn+wI+jgmrafrnKih0kGucavr/GFi81rXixDrGON9KBE0FJ
cPVdc0XiQAvCBnIIAQJBANXu3htPH0VsSznfqcDE+w8zpoAJdo6S/p30tcjsDQnx
l/jYV4FXpErSrtAbmI013VYkdJcghNSLNUXppfk2e8UCQQDJt5c07BS9i2SDEXiz
byzqCfXVzkdnDj9ry9mba1dcr9B9NCslVelXDGZKvQUBqNYCVxg398aRfWlYDTjU
IoVVAkAbTyjPN6R4SkC4HJMg5oReBmvkwFCAFsemBk0GXwuzD0IlJAjXnAZ+/rIO
ItewfwXIL1Mqz53lO/gK+q6TR585AkB304KUIoWzjyF3JqLP3IQOxzns92u9EV6l
V2P+CkbMPXiZV6sls6I4XppJXX2i3bu7iidN3/dqJ9izQK94fMU9AkBZvgsIPCot
y1/POIbv9LtnviDKrmpkXgVQSU4BmTPvXwTJm8APC7P/horSh3SVf1zgmnsyjm9D
hO92gGc+4ajL
-----END PRIVATE KEY-----
BBB;

    const RSA_PUBLIC_KEY_PKS1 = <<<BBB
'-----BEGIN RSA PUBLIC KEY-----
MIGJAoGBAKiR+IBVdd/kiYXMoPD5c79QHJbqax7ZCwiDPdnAG0w27n19HnO21LH7
x8Hu9HgI3dtPO2s/0DpuOg3QUWeGVDe80kLkwU7U8HKsT8w13kAB9JVtr3cjqzHw
1KTkzNQIDg0nMBSpg4RYa0YFyibqQQXoyZHUQqJvUh3yGmihjnFpAgMBAAE=
-----END RSA PUBLIC KEY-----
BBB;

    const RSA_PRIVATE_KEY_PKS1 = <<<BBB
-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQCokfiAVXXf5ImFzKDw+XO/UByW6mse2QsIgz3ZwBtMNu59fR5z
ttSx+8fB7vR4CN3bTztrP9A6bjoN0FFnhlQ3vNJC5MFO1PByrE/MNd5AAfSVba93
I6sx8NSk5MzUCA4NJzAUqYOEWGtGBcom6kEF6MmR1EKib1Id8hpooY5xaQIDAQAB
AoGAOPUZgkNeEMinrw31U3b2JS5sepG6oDG2CKpPu8OtdZMaAkzEfVTJiVoJpP2Y
nPZiADhFW3e0ZAnak9BPsSsySRaSNmR465cG9tbqpXFKh9Rp/sCPo4Jq2n65yood
JBrnGr6/xhYvNa14sQ6xjjfSgRNBSXD1XXNF4kALwgZyCAECQQDV7t4bTx9FbEs5
36nAxPsPM6aACXaOkv6d9LXI7A0J8Zf42FeBV6RK0q7QG5iNNd1WJHSXIITUizVF
6aX5NnvFAkEAybeXNOwUvYtkgxF4s28s6gn11c5HZw4/a8vZm2tXXK/QfTQrJVXp
VwxmSr0FAajWAlcYN/fGkX1pWA041CKFVQJAG08ozzekeEpAuByTIOaEXgZr5MBQ
gBbHpgZNBl8Lsw9CJSQI15wGfv6yDiLXsH8FyC9TKs+d5Tv4Cvquk0efOQJAd9OC
lCKFs48hdyaiz9yEDsc57PdrvRFepVdj/gpGzD14mVerJbOiOF6aSV19ot27u4on
Td/3aifYs0CveHzFPQJAWb4LCDwqLctfzziG7/S7Z74gyq5qZF4FUElOAZkz718E
yZvADwuz/4aK0od0lX9c4Jp7Mo5vQ4TvdoBnPuGoyw==
-----END RSA PRIVATE KEY-----
BBB;

    public function testNormal()
    {
        $cipher = new MasterRsaCipher();
        $this->assertNotNull($cipher);
        $this->assertEquals('', $cipher->getMatDesc());
        $this->assertEquals('RSA/NONE/PKCS1Padding', $cipher->getWrapAlgorithm());

        $cipher = new MasterRsaCipher(self::RSA_PUBLIC_KEY, self::RSA_PRIVATE_KEY, ['key' => 'value']);
        $this->assertNotNull($cipher);
        $this->assertEquals('{"key":"value"}', $cipher->getMatDesc());
        $this->assertEquals('RSA/NONE/PKCS1Padding', $cipher->getWrapAlgorithm());
        $data = 'hello world';
        $edata = $cipher->encrypt($data);
        $this->assertNotEquals($data, $edata);
        $eedata = $cipher->decrypt($edata);
        $this->assertEquals($data, $eedata);
    }

    public function testFail()
    {
        try {
            $cipher = new MasterRsaCipher('RSA_PUBLIC_KEY', self::RSA_PRIVATE_KEY, ['key' => 'value']);
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('RSA key format is not supported', $e->getMessage());
        }

        try {
            $cipher = new MasterRsaCipher(self::RSA_PUBLIC_KEY, 'RSA_PRIVATE_KEY', ['key' => 'value']);
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('RSA key format is not supported', $e->getMessage());
        }

        try {
            $cipher = new MasterRsaCipher(null, null, null);
            $cipher->encrypt('hello world 123');
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('RSA public key is none or invalid', $e->getMessage());
        }

        try {
            $cipher = new MasterRsaCipher(null, null, null);
            $cipher->decrypt('hello world 123');
            $this->assertTrue(false, 'should not here');
        } catch (\Exception $e) {
            $this->assertStringContainsString('RSA private key is none or invalid', $e->getMessage());
        }
    }
}
