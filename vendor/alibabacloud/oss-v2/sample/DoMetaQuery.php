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

// case 1:meta search
$request = new \AlibabaCloud\Oss\V2\Models\DoMetaQueryRequest($bucket, new \AlibabaCloud\Oss\V2\Models\MetaQuery(
    maxResults: 5,
    query: "{'Field': 'Size','Value': '1048576','Operation': 'gt'}",
    sort: 'Size',
    order: \AlibabaCloud\Oss\V2\Models\MetaQueryOrderType::ASC,
    aggregations: new \AlibabaCloud\Oss\V2\Models\MetaQueryAggregations(
    [
        new \AlibabaCloud\Oss\V2\Models\MetaQueryAggregation(
            field: 'Size',
            operation: 'sum'
        ),
        new \AlibabaCloud\Oss\V2\Models\MetaQueryAggregation(
            field: 'Size',
            operation: 'max'
        ),
    ]
),
));
$result = $client->doMetaQuery($request);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);

// case 2: ai search
/*$request = new Oss\Models\DoMetaQueryRequest($bucket, new Oss\Models\MetaQuery(
    maxResults: 99,
    query: "Overlook the snow-covered forest",
    mediaTypes: new Oss\Models\MetaQueryMediaTypes('image'),
    simpleQuery: '{"Operation":"gt", "Field": "Size", "Value": "30"}',
), 'semantic');

$result = $client->doMetaQuery($request);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);*/