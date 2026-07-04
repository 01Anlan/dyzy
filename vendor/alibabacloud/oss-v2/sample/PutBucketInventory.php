<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "account-id" => ['help' => 'The account id of the bucket', 'required' => True],
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
$accountId = $options["account-id"];

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
$inventoryId = 'test-inventory-id';
$request = new Oss\Models\PutBucketInventoryRequest(
    $bucket,
    inventoryId: $inventoryId,
    inventoryConfiguration: new Oss\Models\InventoryConfiguration(
    isEnabled: true,
    destination: new Oss\Models\InventoryDestination(
    ossBucketDestination: new Oss\Models\InventoryOSSBucketDestination(
        bucket: 'acs:oss:::' . $bucket,
        prefix: 'prefix1',
        format: Oss\Models\InventoryFormatType::CSV,
        accountId: $accountId,
        roleArn: 'acs:ram::' . $accountId . ':role/AliyunOSSRole'
    )
),
    schedule: new Oss\Models\InventorySchedule(
    frequency: Oss\Models\InventoryFrequencyType::DAILY
),
    filter: new Oss\Models\InventoryFilter(
    prefix: 'filterPrefix',
    lastModifyBeginTimeStamp: 1637883649,
    lastModifyEndTimeStamp: 1638347592,
    lowerSizeBound: 1024,
    upperSizeBound: 1048576,
    storageClass: 'Standard,IA'
),
    includedObjectVersions: 'All',
    optionalFields: new Oss\Models\OptionalFields(
    fields: array(
        Oss\Models\InventoryOptionalFieldType::STORAGE_CLASS
    )
),
    id: $inventoryId
));
$result = $client->putBucketInventory($request);

printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId
);