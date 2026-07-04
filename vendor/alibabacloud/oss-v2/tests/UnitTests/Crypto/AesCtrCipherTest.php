<?php

namespace UnitTests\Crypto;

use AlibabaCloud\Oss\V2\Crypto\AesCtrCipherBuilder;
use AlibabaCloud\Oss\V2\Crypto\MasterRsaCipher;
use AlibabaCloud\Oss\V2\Crypto\Envelope;
use AlibabaCloud\Oss\V2\Crypto\CipherData;
use AlibabaCloud\Oss\V2\Crypto\AesCtr;


class AesCtrCipherTest extends \PHPUnit\Framework\TestCase
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

    public static function getDataPath(): string
    {
        return dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR .'Data';
    }

    public function testCreateCipherData()
    {
        $cipher = new MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );
        $cipherBuider = new AesCtrCipherBuilder($cipher);
        $cihperData = $cipherBuider->createCipherData();
        $this->assertNotNull($cihperData);
        $this->assertEquals(16, strlen($cihperData->iv));
        $this->assertEquals(32, strlen($cihperData->key));
        $this->assertEquals(128, strlen($cihperData->encryptedIv));
        $this->assertEquals(128, strlen($cihperData->encryptedKey));
        $this->assertEquals('{"key":"value"}', $cihperData->matDesc);
        $this->assertEquals('RSA/NONE/PKCS1Padding', $cihperData->wrapAlgorithm);
        $this->assertEquals('AES/CTR/NoPadding', $cihperData->cekAlgorithm);
    }

    public function testFromEnvelope()
    {
        $cipher = new MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );
        $cipherBuider = new AesCtrCipherBuilder($cipher);
        $envelope = new Envelope(
            'De/S3T8wFjx7QPxAAFl7h7TeI2EsZlfCwox4WhLGng5DK2vNXxULmulMUUpYkdc9umqmDilgSy5Z3Foafw+v4JJThfw68T/9G2gxZLrQTbAlvFPFfPM9Ehk6cY4+8WpY32uN8w5vrHyoSZGr343NxCUGIp6fQ9sSuOLMoJg7hNw=',
            'nyXOp7delQ/MQLjKQMhHLaT0w7u2yQoDLkSnK8MFg/MwYdh4na4/LS8LLbLcM18m8I/ObWUHU775I50sJCpdv+f4e0jLeVRRiDFWe+uo7Puc9j4xHj8YB3QlcIOFQiTxHIB6q+C+RA6lGwqqYVa+n3aV5uWhygyv1MWmESurppg=',
            'AES/CTR/NoPadding',

        );


        $cihperData = $cipherBuider->createCipherData();
        $this->assertNotNull($cihperData);
        $this->assertEquals(16, strlen($cihperData->iv));
        $this->assertEquals(32, strlen($cihperData->key));
        $this->assertEquals(128, strlen($cihperData->encryptedIv));
        $this->assertEquals(128, strlen($cihperData->encryptedKey));
        $this->assertEquals('{"key":"value"}', $cihperData->matDesc);
        $this->assertEquals('RSA/NONE/PKCS1Padding', $cihperData->wrapAlgorithm);
        $this->assertEquals('AES/CTR/NoPadding', $cihperData->cekAlgorithm);
    }

    public function testEncrypt()
    {
        $cipherMaster = new MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );
        $cipherBuider = new AesCtrCipherBuilder($cipherMaster);
        $base64_encrypted_key = 'nyXOp7delQ/MQLjKQMhHLaT0w7u2yQoDLkSnK8MFg/MwYdh4na4/LS8LLbLcM18m8I/ObWUHU775I50sJCpdv+f4e0jLeVRRiDFWe+uo7Puc9j4xHj8YB3QlcIOFQiTxHIB6q+C+RA6lGwqqYVa+n3aV5uWhygyv1MWmESurppg=';
        $base64_encrypted_iv = 'De/S3T8wFjx7QPxAAFl7h7TeI2EsZlfCwox4WhLGng5DK2vNXxULmulMUUpYkdc9umqmDilgSy5Z3Foafw+v4JJThfw68T/9G2gxZLrQTbAlvFPFfPM9Ehk6cY4+8WpY32uN8w5vrHyoSZGr343NxCUGIp6fQ9sSuOLMoJg7hNw=';
        $encrypted_key = base64_decode($base64_encrypted_key);
        $encrypted_iv = base64_decode($base64_encrypted_iv);

        $key = $cipherMaster->decrypt($encrypted_key);
        $iv = $cipherMaster->decrypt($encrypted_iv);

        $cipherData = new CipherData(
            $iv,
            $key,
            $encrypted_iv,
            $encrypted_key,
            '',
            'RSA/NONE/PKCS1Padding',
            'AES/CTR/NoPadding'
        );

        $content = file_get_contents(self::getDataPath() . DIRECTORY_SEPARATOR . 'example.jpg');
        $enc_content = file_get_contents(self::getDataPath() . DIRECTORY_SEPARATOR . 'enc-example.jpg');

        $cipher = new AesCtr($cipherData, 0);
        foreach([16, 32, 64]as $key => $step) {
            $total = 0;
            for ($i = 0; $i < strlen($content); $i += $step) {
                $data = substr($content, $i, $step);
                $edata_pat = substr($enc_content, $i, $step);
                $edata = $cipher->encrypt($data);
                $this->assertEquals($edata_pat, $edata);
                $total += strlen($edata);
            }
    
            $this->assertEquals($total, strlen($content));
            $cipher->reset();
        }
        $cipher->reset();
        $this->assertEquals($enc_content, $cipher->encrypt($content));


        foreach([16, 32, 64] as $setp) {
            $total = 0;
            $cipher = new AesCtr($cipherData, 0);
            for ($i = 0; $i < strlen($content); $i += $setp) {
                $edata = substr($enc_content, $i, $setp);
                $data_pat = substr($content, $i, $setp);
                $dedata = $cipher->decrypt($edata);
                $this->assertEquals($data_pat, $dedata);
                $total += strlen($dedata);
            }
            $this->assertEquals($total, strlen($content));
        }
        $cipher = new AesCtr($cipherData, 0);
        $this->assertEquals($content, $cipher->decrypt($enc_content));
    }

    public function testEncrypt2()
    {
        $cipherMaster = new MasterRsaCipher(
            self::RSA_PUBLIC_KEY,
            self::RSA_PRIVATE_KEY,
            ['key' => 'value']
        );
        $cipherBuider = new AesCtrCipherBuilder($cipherMaster);
        $base64_encrypted_key = 'nyXOp7delQ/MQLjKQMhHLaT0w7u2yQoDLkSnK8MFg/MwYdh4na4/LS8LLbLcM18m8I/ObWUHU775I50sJCpdv+f4e0jLeVRRiDFWe+uo7Puc9j4xHj8YB3QlcIOFQiTxHIB6q+C+RA6lGwqqYVa+n3aV5uWhygyv1MWmESurppg=';
        $base64_encrypted_iv = 'De/S3T8wFjx7QPxAAFl7h7TeI2EsZlfCwox4WhLGng5DK2vNXxULmulMUUpYkdc9umqmDilgSy5Z3Foafw+v4JJThfw68T/9G2gxZLrQTbAlvFPFfPM9Ehk6cY4+8WpY32uN8w5vrHyoSZGr343NxCUGIp6fQ9sSuOLMoJg7hNw=';
        $encrypted_key = base64_decode($base64_encrypted_key);
        $encrypted_iv = base64_decode($base64_encrypted_iv);

        $key = $cipherMaster->decrypt($encrypted_key);
        $iv = $cipherMaster->decrypt($encrypted_iv);

        $cipherData = new CipherData(
            $iv,
            $key,
            $encrypted_iv,
            $encrypted_key,
            '',
            'RSA/NONE/PKCS1Padding',
            'AES/CTR/NoPadding'
        );

        $content = file_get_contents(self::getDataPath() . DIRECTORY_SEPARATOR . 'example.jpg');
        $enc_content = file_get_contents(self::getDataPath() . DIRECTORY_SEPARATOR . 'enc-example.jpg');

        $cipher = new AesCtr($cipherData, 0);
        foreach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]as $key => $step) {
            $total = 0;
            for ($i = 0; $i < strlen($content); $i += $step) {
                $data = substr($content, $i, $step);
                $edata_pat = substr($enc_content, $i, $step);
                $edata = $cipher->encrypt($data);
                $this->assertEquals($edata_pat, $edata);
                $total += strlen($edata);
            }
    
            $this->assertEquals($total, strlen($content));
            $cipher->reset();
        }
        $cipher->reset();
        $this->assertEquals($enc_content, $cipher->encrypt($content));

        foreach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] as $setp) {
            $total = 0;
            $cipher = new AesCtr($cipherData, 0);
            for ($i = 0; $i < strlen($content); $i += $setp) {
                $edata = substr($enc_content, $i, $setp);
                $data_pat = substr($content, $i, $setp);
                $dedata = $cipher->decrypt($edata);
                $this->assertEquals($data_pat, $dedata);
                $total += strlen($dedata);
            }
            $this->assertEquals($total, strlen($content));
        }
        $cipher = new AesCtr($cipherData, 0);
        $this->assertEquals($content, $cipher->decrypt($enc_content));

    }    
}
