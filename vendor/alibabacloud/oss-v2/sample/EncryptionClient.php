<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

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


// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "key" => ['help' => 'The name of the object', 'required' => True],
];
$longopts = \array_map(function ($key) {
    return "$key:";
}, array_keys($optsdesc));
$options = getopt("", $longopts);
foreach ($optsdesc as $key => $value) {
    if ($value['required'] === True && empty($options[$key])) {
        $help = $value['help'];
        echo "Error: the following arguments are required: --$key, $help";
        exit(1);
    }
}

$region = $options["region"];
$bucket = $options["bucket"];
$key = $options["key"];

// Loading credentials values from the environment variables
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

// Using the SDK's default configuration
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion($region);
if (isset($options["endpoint"])) {
    $cfg->setEndpoint($options["endpoint"]);
}

$client = new Oss\Client($cfg);
$masterCipher = new Oss\Crypto\MasterRsaCipher(
    RSA_PUBLIC_KEY,
    RSA_PRIVATE_KEY,
    ['tag' => 'value']
);

$eclient = new Oss\EncryptionClient($client, $masterCipher);

// case 1: Simple Upload
$putObjRequest = new  Oss\Models\PutObjectRequest($bucket, $key);
$putObjResult = $eclient->putObject($putObjRequest);
printf(
    'put object status code:' . $putObjResult->statusCode . PHP_EOL .
    'request id:' . $putObjResult->requestId . PHP_EOL
);

// case 2: Multipart Upload
$initRequest = new Oss\Models\InitiateMultipartUploadRequest($bucket, $key);
$initRequest->cseDataSize = 500 * 1024;
$initRequest->csePartSize = 200 * 1024;
$initResult = $eclient->initiateMultipartUpload($initRequest);
$uploadPartRequest = new Oss\Models\UploadPartRequest($bucket, $key);
$bigFileName = "upload.tmp";
$partSize = 200 * 1024;
generateFile($bigFileName, 500 * 1024);
$file = fopen($bigFileName, 'r');
$parts = array();
if ($file) {
    $i = 1;
    while (!feof($file)) {
        $chunk = fread($file, $partSize);
        $uploadPartRequest = new Oss\Models\UploadPartRequest(
            $bucket,
            $key,
            $i,
            $initResult->uploadId,
            null,
            null,
            null,
            null,
            Oss\Utils::streamFor($chunk)
        );
        $uploadPartRequest->encryptionMultipartContext = $initResult->encryptionMultipartContext;
        $partResult = $eclient->uploadPart($uploadPartRequest);
        $part = new Oss\Models\UploadPart(
            $i,
            $partResult->etag,
        );
        array_push($parts, $part);
        $i++;
    }
    fclose($file);
}
$comResult = $eclient->completeMultipartUpload(
    new Oss\Models\CompleteMultipartUploadRequest(
        $bucket,
        $key,
        $initResult->uploadId,
        null,
        new Oss\Models\CompleteMultipartUpload(
            $parts
        ),
    )
);
unlink($bigFileName);
printf(
    'complete multipart upload status code:' . $comResult->statusCode . PHP_EOL .
    'complete multipart upload request id:' . $comResult->requestId . PHP_EOL .
    'complete multipart upload result:' . var_export($comResult, true)
);

function generateFile($filename, $size)
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
                break;
            }
        }
    }
    fclose($fp);
}