<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Models\LifecycleConfiguration;

// parse args
$optsdesc = [
    "region" => ['help' => 'The region in which the bucket is located.', 'required' => True],
    "endpoint" => ['help' => 'The domain names that other services can use to access OSS.', 'required' => False],
    "bucket" => ['help' => 'The name of the bucket', 'required' => True],
    "target-bucket" => ['help' => 'The name of the target bucket', 'required' => True],
    "target-location" => ['help' => 'The location of the target bucket', 'required' => True],
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
$targetBucket = $options["target-bucket"];
$targetLocation = $options["target-location"];

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
$request = new Oss\Models\PutBucketReplicationRequest($bucket, replicationConfiguration: new Oss\Models\ReplicationConfiguration(
    array(
        new Oss\Models\ReplicationRule(
            destination: new Oss\Models\ReplicationDestination(
            bucket: $targetBucket,
            location: $targetLocation,
        ),
            rtc: new Oss\Models\ReplicationTimeControl(
                status: 'enabled'
            )
        )
    )
));
$result = $client->putBucketReplication($request);

printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'replication rule id:' . $result->replicationRuleId
);