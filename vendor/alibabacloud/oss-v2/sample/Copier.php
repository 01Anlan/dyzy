<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "key" => ['help' => 'The name of the object', 'required' => True],
    "src-key" => ['help' => 'The name of the source object', 'required' => True],
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
$srcKey = $options["src-key"];

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
$copier = $client->newCopier();

// case 1, empty metadata & tagging
$dstKey = "multipart-copy-repalce-empty-metadata-and-tagging-$key.js";
$copyRequest = new Oss\Models\CopyObjectRequest(
    $bucket,
    $dstKey,
    $bucket,
    $srcKey
);
$copyRequest->metadataDirective = 'REPLACE';
$copyRequest->taggingDirective = 'REPLACE';
$result = $copier->copy(
    $copyRequest,
    [
        'multipart_copy_threshold' => 0,
        'disable_shallow_copy' => true
    ]
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL
);

// case 2, has metadata & tagging
$dstKey = "multipart-copy-repalce-metadata-and-tagging-$key.js";
$copyRequest = new Oss\Models\CopyObjectRequest(
    $bucket,
    $dstKey,
    $bucket,
    $srcKey
);
$copyRequest->metadataDirective = 'REPLACE';
$copyRequest->taggingDirective = 'REPLACE';
$copyRequest->contentType = 'text/txt';
$copyRequest->cacheControl = 'cache-123';
$copyRequest->contentDisposition = 'contentDisposition';
$copyRequest->expires = '123';
$copyRequest->metadata = ['test1' => 'val1', 'test2' => 'value2'];
$copyRequest->tagging = 'k3=v3&k4=v4';
$result = $copier->copy(
    $copyRequest,
    [
        'multipart_copy_threshold' => 0,
        'disable_shallow_copy' => true
    ]
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL
);

// multipart copy, 'copy' directive
$copyRequest = new Oss\Models\CopyObjectRequest(
    $bucket,
    $dstKey,
    $bucket,
    $srcKey
);
$copyRequest->contentType = 'text/txt';
$copyRequest->cacheControl = 'cache-123';
$copyRequest->contentDisposition = 'contentDisposition';
$copyRequest->expires = '123';
$copyRequest->metadata = ['test1' => 'val1', 'test2' => 'value2'];
$copyRequest->tagging = 'k3=v3&k4=v4';
$result = $copier->copy(
    $copyRequest,
    [
        'multipart_copy_threshold' => 0,
        'disable_shallow_copy' => true
    ]
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId
);

