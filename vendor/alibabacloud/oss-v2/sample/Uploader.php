<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

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
$totalSize = 250 * 1024 + 123;
$partSize = 100 * 1024;

$filename = "upload.tmp";
$partSize = 200 * 1024;
generateFile($filename, $totalSize);

// multi-part upload
$uploader = $client->newUploader();

$result = $uploader->uploadFile(
    new Oss\Models\PutObjectRequest(
        $bucket,
        $key
    ),
    $filename,
    [
        'part_size' => $partSize,
        'parallel_num' => 1,
    ]
);
printf(
    'multipart upload status code:' . $result->statusCode . PHP_EOL .
    'multipart upload request id:' . $result->requestId . PHP_EOL .
    'multipart upload result:' . var_export($result, true) . PHP_EOL
);

// single part upload
$result = $uploader->uploadFile(
    new Oss\Models\PutObjectRequest(
        $bucket,
        $key
    ),
    $filename,
    [
        'part_size' => $totalSize * 2,
    ]
);
printf(
    'single part upload status code:' . $result->statusCode . PHP_EOL .
    'single part upload request id:' . $result->requestId . PHP_EOL .
    'single part upload result:' . var_export($result, true) . PHP_EOL
);

// upload from
$result = $uploader->uploadFrom(
    new Oss\Models\PutObjectRequest(
        $bucket,
        $key
    ),
    new \GuzzleHttp\Psr7\LazyOpenStream($filename, 'rb'),
    [
        'part_size' => $totalSize * 2,
    ]
);
printf(
    'upload from status code:' . $result->statusCode . PHP_EOL .
    'upload from request id:' . $result->requestId . PHP_EOL .
    'upload from result:' . var_export($result, true) . PHP_EOL
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