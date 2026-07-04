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

$downloader = $client->newDownloader();

// download to
$stream = new \GuzzleHttp\Psr7\BufferStream(1 * 1024 * 1024);
$result = $downloader->downloadTo(
    new Oss\Models\GetObjectRequest(
        $bucket,
        $key
    ),
    $stream,
);
printf(
    'download to body:' . $stream->getContents() . PHP_EOL
);

// download file
$filename = "download.tmp";
touch($filename);
$result = $downloader->downloadFile(
    new Oss\Models\GetObjectRequest(
        $bucket,
        $key
    ),
    $filename,
);
printf(
    'download file body:' . file_get_contents($filename)
);
unlink($filename);