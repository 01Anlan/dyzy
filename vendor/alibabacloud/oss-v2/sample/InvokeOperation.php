<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
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
// PutCname sample case 1:
$body = Oss\Utils::streamFor("<BucketCnameConfiguration>
  <Cname>
    <Domain>example.com</Domain>
  </Cname>
</BucketCnameConfiguration>");

$input = new Oss\OperationInput(
    "PutCname",
    "POST",
);
$input->setParameter("cname", "");
$input->setParameter("comp", "add");
$input->setBody($body);
$input->setBucket($bucket);

$result = $client->invokeOperation($input);
printf(
    'status code:' . $result->GetStatusCode() . PHP_EOL .
    'request id:' . $result->getHeaders()["x-oss-request-id"] . PHP_EOL
);

// PutCname sample case 2:
//$body = Oss\Utils::streamFor("<BucketCnameConfiguration>
//  <Cname>
//    <Domain>example.com</Domain>
//  </Cname>
//</BucketCnameConfiguration>");
//
//
//$input = new Oss\OperationInput(
//    opName: "PutCname",
//    method: "POST",
//    headers: null,
//    parameters: ["cname" => "", "comp" => "add"],
//    body: $body,
//    bucket: $bucket
//);
//$result = $client->invokeOperation($input);
//printf(
//    'status code:' . $result->GetStatusCode() . PHP_EOL .
//    'request id:' . $result->getHeaders()["x-oss-request-id"] . PHP_EOL
//);

// GetCnameToken sample
$input = new Oss\OperationInput(
    "GetCnameToken",
    "GET",
    null,
    ["cname" => "example.com", "comp" => "token"],
    NULL,
    $bucket
);
$result = $client->invokeOperation($input);
printf(
    'status code:' . $result->GetStatusCode() . PHP_EOL .
    'request id:' . $result->getHeaders()["x-oss-request-id"] . PHP_EOL .
    'body:' . $result->getBody()->getContents()
);
