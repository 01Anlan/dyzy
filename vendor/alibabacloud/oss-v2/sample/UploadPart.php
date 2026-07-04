<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "key" => ['help' => 'The name of the object', 'required' => True],
    "upload-id" => ['help' => 'The upload id', 'required' => True],
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
$uploadId = $options["upload-id"];

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
$bigFileName = "upload.tmp";
$partSize = 200 * 1024;
generateFile($bigFileName, 500 * 1024);
$file = fopen($bigFileName, 'r');
$parts = array();
if ($file) {
    $i = 1;
    while (!feof($file)) {
        $chunk = fread($file, $partSize);
        $result = $client->uploadPart(
            new Oss\Models\UploadPartRequest(
                $bucket,
                $key,
                $i,
                $uploadId,
                null,
                null,
                null,
                null,
                Oss\Utils::streamFor($chunk)
            )
        );
        printf(
            'status code:' . $result->statusCode . PHP_EOL .
            'request id:' . $result->requestId . PHP_EOL .
            'result:' . var_export($result, true) . PHP_EOL
        );
        $i++;
    }
    fclose($file);
}
unlink($bigFileName);

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