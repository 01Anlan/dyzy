# Alibaba Cloud OSS SDK for PHP v2

[![GitHub version](https://badge.fury.io/gh/aliyun%2Falibabacloud-oss-php-sdk-v2.svg)](https://badge.fury.io/gh/aliyun%2Falibabacloud-oss-php-sdk-v2)

alibabacloud-oss-php-sdk-v2 是OSS在PHP编译语言下的第二版SDK

## [English](README.md)

## 关于
> - 此PHP SDK基于[阿里云对象存储服务](http://www.aliyun.com/product/oss/)官方API构建。
> - 阿里云对象存储（Object Storage Service，简称OSS），是阿里云对外提供的海量，安全，低成本，高可靠的云存储服务。
> - OSS适合存放任意文件类型，适合各种网站、开发企业及开发者使用。
> - 使用此SDK，用户可以方便地在任何应用、任何时间、任何地点上传，下载和管理数据。

## 运行环境
> - PHP 7.4及以上。

## 安装方法
### 通过 composer 安装
如果您通过composer管理您的项目依赖，可以在你的项目根目录运行：
```bash
$ composer require alibabacloud/oss-v2
```
或者在你的`composer.json`中声明对Alibaba Cloud OSS SDK for PHP v2的依赖：
```php
"require": {
    "alibabacloud/oss-v2": "*"
}
```
然后通过`composer install`安装依赖

### 通过[PHAR 文件](https://github.com/aliyun/alibabacloud-oss-php-sdk-v2/releases)安装
```bash
require_once '/path/to/alibabacloud-oss-php-sdk-v2-{version}.phar'
```

## 快速使用
#### 获取存储空间列表（List Bucket）
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

#### 获取文件列表（List Objects）
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

#### 上传文件（Put Object）
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

## 更多示例
请参看`sample`目录

### 运行示例
> - 执行`composer install`下载依赖的库
> - 进入示例程序目录 `sample`。
> - 通过环境变量，配置访问凭证, `export OSS_ACCESS_KEY_ID="your access key id"`, `export OSS_ACCESS_KEY_SECRET="your access key secrect"`
> - 以 ListBuckets.php 为例，执行 `php ListBuckets.php --region cn-hangzhou`。

## 资源
[开发者指南](DEVGUIDE-CN.md) - 参阅该指南，来帮助您安装、配置和使用该开发套件。

## 许可协议
> - Apache-2.0, 请参阅 [许可文件](LICENSE)