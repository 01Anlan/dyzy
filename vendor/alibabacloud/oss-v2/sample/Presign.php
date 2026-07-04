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

// case 1:PutObject
$request = new Oss\Models\PutObjectRequest($bucket, $key);
$result = $client->presign($request);
print(
    'put object presign result:' . var_export($result, true) . PHP_EOL .
    'put object url:' . $result->url . PHP_EOL
);

// case 2:GetObject
$request = new Oss\Models\GetObjectRequest($bucket, $key);
$result = $client->presign($request);
print(
    'get object presign result:' . var_export($result, true) . PHP_EOL .
    'get object url:' . $result->url . PHP_EOL
);

// case 3:HeadObject
$request = new Oss\Models\HeadObjectRequest($bucket, $key);
$result = $client->presign($request);
print(
    'head object presign result:' . var_export($result, true) . PHP_EOL .
    'head object url:' . $result->url . PHP_EOL
);

// case 4:InitiateMultipartUpload
$request = new Oss\Models\InitiateMultipartUploadRequest($bucket, $key);
$result = $client->presign($request);
print(
    'initiate multipart upload presign result:' . var_export($result, true) . PHP_EOL .
    'initiate multipart upload url:' . $result->url . PHP_EOL
);

// case 5:UploadPart
$request = new Oss\Models\UploadPartRequest($bucket, $key);
$request->partNumber = 1;
$request->uploadId = 'your upload id';
$result = $client->presign($request);
print(
    'upload part presign result:' . var_export($result, true) . PHP_EOL .
    'upload part url:' . $result->url . PHP_EOL
);

// case 6:CompleteMultipartUpload
$request = new Oss\Models\CompleteMultipartUploadRequest($bucket, $key);
$request->uploadId = 'your upload id';
$result = $client->presign($request);
print(
    'complete multipart upload presign result:' . var_export($result, true) . PHP_EOL .
    'complete multipart upload url:' . $result->url . PHP_EOL
);

// case 6:AbortMultipartUpload
$request = new Oss\Models\AbortMultipartUploadRequest($bucket, $key);
$request->uploadId = 'your upload id';
$result = $client->presign($request);
print(
    'abort multipart upload presign result:' . var_export($result, true) . PHP_EOL .
    'abort multipart upload url:' . $result->url
);

