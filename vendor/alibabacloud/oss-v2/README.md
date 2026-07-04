# Alibaba Cloud OSS SDK for PHP v2

[![GitHub version](https://badge.fury.io/gh/aliyun%2Falibabacloud-oss-php-sdk-v2.svg)](https://badge.fury.io/gh/aliyun%2Falibabacloud-oss-php-sdk-v2)

alibabacloud-oss-php-sdk-v2 is the v2 of the OSS SDK for the PHP programming language

## [简体中文](README-CN.md)

## About
> - This PHP SDK is based on the official APIs of [Alibaba Cloud OSS](http://www.aliyun.com/product/oss/).
> - Alibaba Cloud Object Storage Service (OSS) is a cloud storage service provided by Alibaba Cloud, featuring massive capacity, security, a low cost, and high reliability. 
> - The OSS can store any type of files and therefore applies to various websites, development enterprises and developers.
> - With this SDK, you can upload, download and manage data on any app anytime and anywhere conveniently. 

## Running Environment
> - PHP 7.4 or above. 

## Installing
### Install the sdk through composer
If you use the composer to manage project dependencies, run the following command in your project's root directory:
```bash
$ composer require alibabacloud/oss-v2
```
You can also declare the dependency on Alibaba Cloud OSS SDK for PHP v2 in the composer.json file.
```json
"require": {
    "alibabacloud/oss-v2": "*"
}
```

### Install from the [PHAR File](https://github.com/aliyun/alibabacloud-oss-php-sdk-v2/releases) directly
```bash
$ require_once '/path/to/alibabacloud-oss-php-sdk-v2-{version}.phar'
```


## Getting Started
#### List Bucket
```php
<?php

use AlibabaCloud\Oss\V2 as Oss;

$region = 'cn-hangzhou';

// Loading credentials values from the environment variables
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

// Using the SDK's default configuration
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion($region);

$client = new Oss\Client($cfg);

// Create the Paginator for the ListBuckets operation
$paginator = new Oss\Paginator\ListBucketsPaginator($client);
$iter = $paginator->iterPage(new Oss\Models\ListBucketsRequest());

// Iterate through the bucket pages
foreach ($iter as $page) {
    foreach ($page->buckets ?? [] as $bucket) {
        print("Bucket: $bucket->name, $bucket->location\n");
    }
}
```

#### List Objects
```php
<?php

use AlibabaCloud\Oss\V2 as Oss;

$region = 'cn-hangzhou';
$bucket = 'your bucket name';

// Loading credentials values from the environment variables
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

// Using the SDK's default configuration
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion($region);

$client = new Oss\Client($cfg);

# Create the Paginator for the ListBuckets operation
$paginator = new Oss\Paginator\ListObjectsV2Paginator($client);
$iter = $paginator->iterPage(new Oss\Models\ListObjectsV2Request($bucket));

// Iterate through the object pages
foreach ($iter as $page) {
    foreach ($page->contents ?? [] as $object) {
        print("Object: $object->key, $object->type, $object->size\n");
    }
}
```

#### Put Object
```php
<?php

use AlibabaCloud\Oss\V2 as Oss;

$region = 'cn-hangzhou';
$bucket = 'your bucket name';
$key = 'your object name';

// Loading credentials values from the environment variables
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

// Using the SDK's default configuration
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion($region);

$client = new Oss\Client($cfg);

$data = 'Hello OSS';

$request = new Oss\Models\PutObjectRequest($bucket, $key);
$request->body = Oss\Utils::streamFor($data);

$result = $client->putObject($request);

printf(
    'status code:'. $result->statusCode .PHP_EOL.
    'request id:'. $result->requestId .PHP_EOL.
    'etag:'. $result->etag. PHP_EOL
);
```

##  Complete Example
More example projects can be found in the `sample` folder 

### Running Example
> - Run `composer install` to download the dependent libraries.
> - Go to the sample code folder `sample`。
> - Configure credentials values from the environment variables, like `export OSS_ACCESS_KEY_ID="your access key id"`, `export OSS_ACCESS_KEY_SECRET="your access key secrect"`
> - Take ListBuckets.php as an example，run `php ListBuckets.php --region cn-hangzhou` command。

## Resources
[Developer Guide](DEVGUIDE-CN.md) - Use this document to learn how to get started and use this sdk.

## License
> - Apache-2.0, see [license file](LICENSE)