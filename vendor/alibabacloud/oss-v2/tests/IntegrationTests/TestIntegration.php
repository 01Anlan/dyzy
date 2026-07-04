<?php

namespace IntegrationTests;

use AlibabaCloud\Oss\V2 as Oss;

class TestIntegration extends \PHPUnit\Framework\TestCase
{
    static $ACCESS_ID;
    static $ACCESS_KEY;
    static $ENDPOINT;
    static $REGION;
    static $RAM_ROLE_ARN;
    static $USER_ID;

    static $PAYER_ACCESS_ID;
    static $PAYER_ACCESS_KEY;
    static $PAYER_UID;

    public static $BUCKETNAME_PREFIX = "php-sdk-test-bucket-";
    public static $OBJECTNAME_PREFIX = "php-sdk-test-object-";

    static ?Oss\Client $defaultClient = null;
    static ?Oss\Client $invalidAkClient = null;
    static ?Oss\Client $signV1Client = null;

    static ?string $tempDir;

    static ?string $bucketName = null;

    public static function setUpBeforeClass(): void
    {
        self::$ACCESS_ID = getenv("OSS_TEST_ACCESS_KEY_ID");
        self::$ACCESS_KEY = getenv("OSS_TEST_ACCESS_KEY_SECRET");
        self::$ENDPOINT = getenv("OSS_TEST_ENDPOINT");
        self::$REGION = getenv("OSS_TEST_REGION") ?? 'cn-hangzhou';
        self::$RAM_ROLE_ARN = getenv("OSS_TEST_RAM_ROLE_ARN");
        self::$USER_ID = getenv("OSS_TEST_USER_ID");

        self::$PAYER_ACCESS_ID = getenv("OSS_TEST_PAYER_ACCESS_KEY_ID");
        self::$PAYER_ACCESS_KEY = getenv("OSS_TEST_PAYER_ACCESS_KEY_SECRET");
        self::$PAYER_UID = getenv("OSS_TEST_PAYER_UID");

        $client = self::getDefaultClient();
        $bucketName = self::randomBucketName();
        self::$bucketName = $bucketName;

        self::$tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'oss-php-sdk-test-' . strval(time());
        mkdir(self::$tempDir);

        $client->putBucket(new Oss\Models\PutBucketRequest($bucketName));
    }

    public static function tearDownAfterClass(): void
    {
        //clean access points
        if (version_compare(PHP_VERSION, '8.0.0', '>=')) {        
            self::deleteAccessPoint(self::getDefaultClient(), self::$bucketName);
        }

        //clean buckets
        self::cleanBucket(self::getDefaultClient(), self::$bucketName);

        //clean temp dir
        self::deleteDir(self::$tempDir);
    }

    static function getDefaultClient()
    {
        if (self::$defaultClient != null) {
            return self::$defaultClient;
        }

        $cfg = Oss\Config::loadDefault();
        $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
            self::$ACCESS_ID,
            self::$ACCESS_KEY
        ));
        $cfg->setRegion(self::$REGION);
        $cfg->setEndpoint(self::$ENDPOINT);
        self::$defaultClient = new Oss\Client($cfg);
        return self::$defaultClient;
    }

    static function getClient(string $ak, $sk, $region, $endpoint)
    {
        $cfg = Oss\Config::loadDefault();
        $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
            $ak,
            $sk
        ));
        $cfg->setRegion($region);
        if (!empty($endpoint)) {
            $cfg->setEndpoint($endpoint);
        }
        return new Oss\Client($cfg);
    }

    static function getInvalidAkClient()
    {
        if (self::$invalidAkClient != null) {
            return self::$invalidAkClient;
        }

        $cfg = Oss\Config::loadDefault();
        $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
            'invalid-ak',
            'invalid'
        ));
        $cfg->setRegion(self::$REGION);
        $cfg->setEndpoint(self::$ENDPOINT);
        self::$invalidAkClient = new Oss\Client($cfg);
        return self::$invalidAkClient;
    }

    public static function getTempFileName()
    {
        return tempnam(self::$tempDir, 'test');
    }

    /**
     * @return Oss\Client|null
     * @throws \Throwable
     */
    public function getClientUseStsToken()
    {
        try {
            $resp = $this->stsAssumeRole(self::$ACCESS_ID, self::$ACCESS_KEY, self::$RAM_ROLE_ARN);
            $accessKeyId = $resp['Credentials']['AccessKeyId'];
            $accessKeySecret = $resp['Credentials']['AccessKeySecret'];
            $token = $resp['Credentials']['SecurityToken'];
            $cfg = Oss\Config::loadDefault();
            $cfg->setCredentialsProvider(new Oss\Credentials\StaticCredentialsProvider(
                $accessKeyId,
                $accessKeySecret,
                $token
            ));
            $cfg->setRegion(self::$REGION);
            $cfg->setEndpoint(self::$ENDPOINT);
            return new Oss\Client($cfg);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function stsAssumeRole($accessKeyId, $accessKeySecret, $roleArn)
    {
        // Constants
        $StsSignVersion = "1.0";
        $StsAPIVersion = "2015-04-01";
        $StsHost = "https://sts.aliyuncs.com/";
        $TimeFormat = "Y-m-d\TH:i:s\Z";
        $RespBodyFormat = "JSON";
        $PercentEncode = "%2F";
        $HTTPGet = "GET";
        $uuid = "Nonce-" . rand(1000, 9999);
        $currentTime = (new \DateTime('now', new \DateTimeZone('UTC')))->format($TimeFormat);
        $queryStr = http_build_query([
            "SignatureVersion" => $StsSignVersion,
            "Format" => $RespBodyFormat,
            "Timestamp" => $currentTime,
            "RoleArn" => $roleArn,
            "RoleSessionName" => "oss_test_sess",
            "AccessKeyId" => $accessKeyId,
            "SignatureMethod" => "HMAC-SHA1",
            "Version" => $StsAPIVersion,
            "Action" => "AssumeRole",
            "SignatureNonce" => $uuid,
            "DurationSeconds" => 3600
        ]);
        parse_str($queryStr, $queryParams);
        ksort($queryParams);
        $queryStr = http_build_query($queryParams);
        $strToSign = $HTTPGet . "&" . $PercentEncode . "&" . rawurlencode($queryStr);
        $signature = base64_encode(hash_hmac('sha1', $strToSign, $accessKeySecret . "&", true));
        $assumeURL = $StsHost . "?" . $queryStr . "&Signature=" . urlencode($signature);
        $httpClient = new \GuzzleHttp\Client();
        try {
            $resp = $httpClient->get($assumeURL);
            $result = json_decode($resp->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode JSON response");
            }
            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public static function randomBucketName()
    {
        return self::$BUCKETNAME_PREFIX . strval(rand(0, 100)) . '-' . strval(time());
    }

    public static function randomObjectName()
    {
        return self::$OBJECTNAME_PREFIX . strval(rand(0, 100)) . '-' . strval(time());
    }

    public static function randomLowStr()
    {
        self::$BUCKETNAME_PREFIX . strval(rand(0, 100)) . '-' . strval(time());
    }

    public static function randomStr()
    {
        self::$BUCKETNAME_PREFIX . strval(rand(0, 100)) . '-' . strval(time());
    }

    public static function waitFor(float $sec)
    {
        usleep((int)($sec * 1000000));
    }

    public static function cleanBucket(Oss\Client $client, string $bucketName): void
    {
        //delete Objects
        $paginator = new Oss\Paginator\ListObjectsPaginator($client);
        $iter = $paginator->iterPage(new Oss\Models\ListObjectsRequest($bucketName));
        foreach ($iter as $page) {
            $objects = [];
            foreach ($page->contents ?? [] as $content) {
                $objects[] = new Oss\Models\DeleteObject($content->key);
            }
            if (!empty($objects)) {
                $client->deleteMultipleObjects(new Oss\Models\DeleteMultipleObjectsRequest(
                    $bucketName,
                    $objects
                ));
            }
        }

        //delete uploadpart
        $paginator = new Oss\Paginator\ListMultipartUploadsPaginator($client);
        $iter = $paginator->iterPage(new Oss\Models\ListMultipartUploadsRequest($bucketName));
        foreach ($iter as $page) {
            foreach ($page->uploads ?? [] as $upload) {
                $client->abortMultipartUpload(new Oss\Models\AbortMultipartUploadRequest(
                    $bucketName,
                    $upload->key,
                    $upload->uploadId
                ));
            }
        }

        $client->deleteBucket(new Oss\Models\DeleteBucketRequest($bucketName));
    }

    public static function deleteDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        if ($handle = opendir($dir)) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $file = $dir . DIRECTORY_SEPARATOR . $entry;

                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            closedir($handle);
        }

        rmdir($dir);
    }

    public static function getDataPath(): string
    {
        return dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Data';
    }

    public static function deleteAccessPoint(Oss\Client $client, string $bucketName): void
    {
        $listRequest = new Oss\Models\ListAccessPointsRequest();
        $listRequest->bucket = $bucketName;
        while (true) {
            $listResult = $client->listAccessPoints($listRequest);
            if (isset($listResult->listAccessPoints->accessPoints->accessPoints) && count($listResult->listAccessPoints->accessPoints->accessPoints) > 0) {
                foreach ($listResult->listAccessPoints->accessPoints->accessPoints as $accessPoint) {
                    switch ($accessPoint->status) {
                        case 'creating':
                            sleep(3);
                            while (true) {
                                $getResult = $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
                                    $bucketName, $accessPoint->accessPointName
                                ));
                                if ($getResult->getAccessPoint->status != 'creating') {
                                    break;
                                } else {
                                    sleep(3);
                                }
                            }
                            $client->deleteAccessPoint(new Oss\Models\DeleteAccessPointRequest(
                                $bucketName, $accessPoint->accessPointName
                            ));
                            break;
                        case 'deleting':
                            sleep(5);
                            break;
                        default:
                            $client->deleteAccessPoint(new Oss\Models\DeleteAccessPointRequest(
                                $bucketName, $accessPoint->accessPointName
                            ));
                            break;
                    }
                    while (true) {
                        try {
                            $client->getAccessPoint(new Oss\Models\GetAccessPointRequest(
                                $bucketName, $accessPoint->accessPointName
                            ));
                        } catch (Oss\Exception\OperationException $e) {
                            $se = $e->getPrevious();
                            if ($se instanceof Oss\Exception\ServiceException) {
                                if ($se->getErrorCode() == 'NoSuchAccessPoint' && $se->getStatusCode() == 404) {
                                    break;
                                }
                            }
                        }
                        sleep(3);
                    }

                }
                if ($listResult->listAccessPoints->isTruncated == false) {
                    break;
                }
                if ($listResult->listAccessPoints->nextContinuationToken != "") {
                    $listRequest->continuationToken = $listResult->listAccessPoints->nextContinuationToken;
                }
            } else {
                break;
            }
        }
    }

    /**
     * @param $filename
     * @param $size
     */
    public function generateFile($filename, $size)
    {
        if (
            file_exists($filename) &&
            $size == sprintf('%u', filesize($filename))
        ) {
            return;
        }
        $part_size = 32;
        $fp = fopen($filename, "w");
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        if ($fp) {
            while ($size > 0) {
                if ($size < $part_size) {
                    $write_size = $size;
                } else {
                    $write_size = $part_size;
                }
                $size -= $write_size;
                $a = $characters[rand(0, $charactersLength - 1)];
                $content = str_repeat($a, $write_size);
                $flag = fwrite($fp, $content);
                if (!$flag) {
                    $this->assertTrue(false, "write to $filename failed.");
                    break;
                }
            }
        } else {
            $this->assertTrue(false, "open $filename failed.");
        }
        fclose($fp);
    }

    public function object_get_contents($client, $bucket, $key): string
    {
        $result = $client->getObject(new Oss\Models\GetObjectRequest(
            self::$bucketName,
            $key,
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        return $result->body->getContents();
    }

    public function object_get_content_type($client, $bucket, $key): string
    {
        $result = $client->headObject(new Oss\Models\HeadObjectRequest(
            self::$bucketName,
            $key,
        ));
        $this->assertEquals(200, $result->statusCode);
        $this->assertEquals('OK', $result->status);
        return $result->contentType;
    }
}
