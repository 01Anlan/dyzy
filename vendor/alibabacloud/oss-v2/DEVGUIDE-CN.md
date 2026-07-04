

# 开发者指南
## [English](DEVGUIDE.md)

阿里云对象存储（Object Storage Service，简称OSS），是阿里云对外提供的海量、安全、低成本、高可靠的云存储服务。用户可以通过调用API，在任何应用、任何时间、任何地点上传和下载数据，也可以通过用户Web控制台对数据进行简单的管理。OSS适合存放任意文件类型，适合各种网站、开发企业及开发者使用。

该开发套件隐藏了许多较低级别的实现，例如身份验证、请求重试和错误处理, 通过其提供的接口，让您不用复杂编程即可访问阿里云OSS服务。

该开发套件同时提供实用的模块，例如上传和下载管理器，自动将大对象分成多块并行传输。

您可以参阅该指南，来帮助您安装、配置和使用该开发套件。

跳转到:

* [安装](#安装)
* [配置](#配置)
* [接口说明](#接口说明)
* [场景示例](#场景示例)
* [迁移指南](#迁移指南)

# 安装

## 环境准备

使用PHP 7.4及以上版本。
请参考[php安装](https://www.php.net/manual/install.php)下载和安装php运行环境。
您可以执行以下命令查看php语言版本。
```
php -v
```

## 安装SDK

### composer 方式
在项目的根目录运行 composer require alibabacloud/oss-v2，
</br>或者在composer.json文件中添加如下依赖关系，并运行 composer install 安装依赖。
```
"require": {
    "alibabacloud/oss-v2": "*"
}
```
安装完成后，目录结构如下，其中app.php是您的应用程序：
```
        .
        ├── src
        |   └──app.php
        ├── composer.json
        ├── composer.lock
        └── vendor
                            
```
您可以通过将以下内容添加到您的代码中来自动加载所有依赖项
```
require_once __DIR__ . '/../vendor/autoload.php';                   
```

### phar方式
在[GitHub](https://github.com/aliyun/alibabacloud-oss-php-sdk-v2/releases)中选择相应的版本并下载打包好的phar文件。

在代码中引入phar文件：
```
require_once '/path/to/alibabacloud-oss-php-sdk-v2-{version}.phar';
```

### 源码方式
在[GitHub](https://github.com/aliyun/alibabacloud-oss-php-sdk-v2/releases)中选择相应的版本并并下载打包好的zip文件。

解压后的根目录中包含一个autoload.php文件，在代码中引入此文件：
```
require_once '/path/to/alibabacloud-oss-php-sdk-v2/autoload.php';          
```

## 验证SDK
运行以下代码查看SDK版本：
```
<?php
require_once 'vendor/autoload.php';
use AlibabaCloud\Oss\V2 as Oss;

print_r(Oss\Version::VERSION);
```

# 配置
您可以配置服务客户端的常用设置，例如超时、日志级别和重试配置，大多数设置都是可选的。
但是，对于每个客户端，您必须指定区域和凭证。 SDK使用这些信息签署请求并将其发送到正确的区域。

此部分的其它主题
* [区域](#区域)
* [凭证](#凭证)
* [访问域名](#访问域名)
* [HTTP客户端](#http客户端)
* [重试](#重试)
* [配置参数汇总](#配置参数汇总)

## 加载配置
配置客户端的设置有多种方法，以下是推荐的模式。

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;

$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$region = 'cn-hangzhou';
$cfg->setRegion($region);
```

## 区域
指定区域时，您可以指定向何处发送请求，例如 cn-hangzhou 或 cn-shanghai。有关所支持的区域列表，请参阅 [OSS访问域名和数据中心](https://www.alibabacloud.com/help/zh/oss/user-guide/regions-and-endpoints)。
SDK 没有默认区域，您需要加载配置时使用`(new Config())->setRegion`作为参数显式设置区域。例如
```
$cfg = Oss\Config::loadDefault();
$cfg->setRegion("cn-hangzhou");
```

>**说明**：该SDK默认使用v4签名，所以必须指定该参数。

## 凭证

SDK需要凭证（访问密钥）来签署对 OSS 的请求, 所以您需要显式指定这些信息。当前支持凭证配置如下：
* [环境变量](#环境变量)
* [静态凭证](#静态凭证)
* [ECS实例角色](#ecs实例角色)
* [RAM角色](#ram角色)
* [OIDC角色SSO](#oidc角色sso)
* [自定义凭证提供者](#自定义凭证提供者)

### 环境变量

SDK 支持从环境变量获取凭证，支持的环境变量名如下：
* OSS_ACCESS_KEY_ID
* OSS_ACCESS_KEY_SECRET
* OSS_SESSION_TOKEN（可选）

以下展示了如何配置环境变量。

1. Linux、OS X 或 Unix
```
$ export OSS_ACCESS_KEY_ID=YOUR_ACCESS_KEY_ID
$ export OSS_ACCESS_KEY_SECRET=YOUR_ACCESS_KEY_SECRET
$ export OSS_SESSION_TOKEN=TOKEN
```

2. Windows
```
$ set OSS_ACCESS_KEY_ID=YOUR_ACCESS_KEY_ID
$ set OSS_ACCESS_KEY_SECRET=YOUR_ACCESS_KEY_SECRET
$ set OSS_SESSION_TOKEN=TOKEN
```

使用环境变量凭证

```
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
```
### 静态凭证

您可以在应用程序中对凭据进行硬编码，显式设置要使用的访问密钥。

> **注意:** 请勿将凭据嵌入应用程序中，此方法仅用于测试目的。

1. 长期凭证
```
$credentialsProvider = new Oss\Credentials\StaticCredentialsProvider("AKId", "AKSecrect");
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
```

2. 临时凭证
```
$credentialsProvider = new Oss\Credentials\StaticCredentialsProvider("AKId", "AKSecrect","Token");
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
```

### ECS实例角色

如果你需要在阿里云的云服务器ECS中访问您的OSS，您可以通过ECS实例RAM角色的方式访问OSS。实例RAM角色允许您将一个角色关联到云服务器实例，在实例内部基于STS临时凭证通过指定方法访问OSS。

SDK 不直接提供该访问凭证实现，需要结合阿里云凭证库[credentials-php](https://github.com/aliyun/credentials-php)，具体配置如下:

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Credentials\Credential;

// ...
$config = new Credential\Config([
    // 填写Credential类型，固定值为ecs_ram_role。
    'type' => 'ecs_ram_role',
    'roleName' => "<role_name>",
]);

$credential = new Credential($config);
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider(new Oss\Credentials\CredentialsProviderFunc(function () use ($credential) {
    $cred = $credential->getCredential();
    return new AlibabaCloud\Oss\V2\Credentials\Credentials($cred->getAccessKeyId(), $cred->getAccessKeySecret(), $cred->getSecurityToken());
}));
$region = 'cn-hangzhou';
$cfg->setRegion($region);

```

### RAM角色

如果您需要授权访问或跨账号访问OSS，您可以通过RAM用户扮演对应RAM角色的方式授权访问或跨账号访问OSS。

SDK 不直接提供该访问凭证实现，需要结合阿里云凭证库[credentials-php](https://github.com/aliyun/credentials-php)，具体配置如下:

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Credentials\Credential;

// ...
$config = new Credential\Config([
    // Which type of credential you want
    'type' => 'ram_role_arn',
    //  AccessKeyId of your account
    'accessKeyId' => 'AccessKeyId',
    // AccessKeySecret of your account
    'accessKeySecret' => 'AccessKeySecret',
    // Format: acs:ram::USER_Id:role/ROLE_NAME
    'roleArn' => 'RoleArn',
    // Role Session Name
    'roleSessionName' => 'yourRoleSessionName',
    // Not required, limit the permissions of STS Token
    'policy' => 'Policy',
]);

$credential = new Credential($config);
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider(new Oss\Credentials\CredentialsProviderFunc(function () use ($credential) {
    $cred = $credential->getCredential();
    return new AlibabaCloud\Oss\V2\Credentials\Credentials($cred->getAccessKeyId(), $cred->getAccessKeySecret(), $cred->getSecurityToken());
}));
$region = 'cn-hangzhou';
$cfg->setRegion($region);

```

### OIDC角色SSO

您也可以在应用或服务中使用OIDC认证访问OSS服务，关于OIDC角色SSO的更多信息，请参见[OIDC角色SSO概览](https://www.alibabacloud.com/help/zh/ram/user-guide/overview-of-oidc-based-sso)。

SDK 不直接提供该访问凭证实现，需要结合阿里云凭证库[credentials-php](https://github.com/aliyun/credentials-php)，具体配置如下:

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Credentials\Credential;

// ...
$config = new Credential\Config([
    'type' => 'oidc_role_arn',
    // Specify the ARN of the OIDC IdP by specifying the ALIBABA_CLOUD_OIDC_PROVIDER_ARN environment variable.
    'oidcProviderArn' => '<oidc_provider_arn>',
    // Specify the path of the OIDC token file by specifying the ALIBABA_CLOUD_OIDC_TOKEN_FILE environment variable.
    'oidcTokenFilePath' => '<oidc_token_file_path>',
    // Specify the ARN of the RAM role by specifying the ALIBABA_CLOUD_ROLE_ARN environment variable.
    'roleArn' => '<role_arn>',
    // Specify the role session name by specifying the ALIBABA_CLOUD_ROLE_SESSION_NAME environment variable.
    'roleSessionName' => '<role_session_name>',
    // Optional. Specify limited permissions for the RAM role. Example: {"Statement": [{"Action": ["*"],"Effect": "Allow","Resource": ["*"]}],"Version":"1"}.
    'policy' => '',
    // Optional. Specify the validity period of the session.
    'roleSessionExpiration' => 3600,
]);

$credential = new Credential($config);
$cfg = Oss\Config::loadDefault();
$providerWrapper = new AlibabaCloudCredentialsWrapper($credential);
$cfg->setCredentialsProvider(new Oss\Credentials\CredentialsProviderFunc(function () use ($credential) {
    $cred = $credential->getCredential();
    return new AlibabaCloud\Oss\V2\Credentials\Credentials($cred->getAccessKeyId(), $cred->getAccessKeySecret(), $cred->getSecurityToken());
}));
$region = 'cn-hangzhou';
$cfg->setRegion($region);
```

### 自定义凭证提供者

当以上凭证配置方式不满足要求时，您可以自定义获取凭证的方式。SDK 支持多种实现方式。

1. 实现 Oss\Credentials\CredentialsProvider 接口
```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Credentials\Credentials;

class CustomerCredentialsProvider implements Oss\Credentials\CredentialsProvider
{
    public function getCredentials(): Credentials
    {
        // 返回长期凭证
        return new Credentials('id', 'secret');
        // 返回临时凭证
//        return new Credentials(
//            'id',
//            'secret',
//            'token',
//        );
    }
}

$provider = new CustomerCredentialsProvider();
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($provider);
$region = 'cn-hangzhou';
$cfg->setRegion($region);

```

2. 通过 Oss\Credentials\CredentialsProviderFunc

Oss\Credentials\CredentialsProviderFunc 是 Oss\Credentials\CredentialsProvider 的 易用性封装。

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use AlibabaCloud\Oss\V2\Credentials\Credentials;

$provider = new Oss\Credentials\CredentialsProviderFunc(
    function () {
        return new Credentials('id', 'secret');
        // 返回临时凭证
//        return new Credentials(
//            'id',
//            'secret',
//            'token',
//        );
    }
);
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($provider);
$region = 'cn-hangzhou';
$cfg->setRegion($region);
```
## 访问域名

您可以通过Endpoint参数，自定义服务请求的访问域名。

当不指定时，SDK根据Region信息，构造公网访问域名。例如当Region为'cn-hangzhou'时，构造出来的访问域名为'oss-cn-hangzhou.aliyuncs.com'。

您可以通过修改配置参数，构造出其它访问域名，例如 内网访问域名，传输加速访问域名 和 双栈(IPV6,IPV4)访问域名。有关OSS访问域名规则，请参考[OSS访问域名使用规则](https://www.alibabacloud.com/help/zh/oss/user-guide/oss-domain-names)。

当通过自定义域名访问OSS服务时，您需要指定该配置参数。在使用自定义域名发送请求时，请先绑定自定域名至Bucket默认域名，具体操作详见 [绑定自定义域名](https://www.alibabacloud.com/help/zh/oss/user-guide/map-custom-domain-names-5)。

### 使用标准域名访问

以 访问 Region 'cn-hangzhou' 为例

1. 使用公网域名

```
$cfg = Oss\Config::loadDefault();
$region = 'cn-hangzhou';
$cfg->setRegion($region);

或者

$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setEndpoint('oss-cn-hanghzou.aliyuncs.com');
```

2. 使用内网域名

```
$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setUseInternalEndpoint(true);

或者

$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setEndpoint('oss-cn-hanghzou-internal.aliyuncs.com');
```

3. 使用传输加速域名
```
$cfg = Oss\Config::loadDefault();
$cfg = $cfg->setRegion('cn-hangzhou')->setUseAccelerateEndpoint(true);

或者

$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setEndpoint('oss-accelerate.aliyuncs.com');
```

4. 使用双栈域名
```
$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setUseDualStackEndpoint(true);

或者

$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setEndpoint('cn-hangzhou.oss.aliyuncs.com');
```

### 使用自定义域名访问

以 'www.example-***.com' 域名 绑定到 'cn-hangzhou' 区域 的 bucket-example 存储空间为例

```
$cfg = Oss\Config::loadDefault();
$cfg->setRegion('cn-hangzhou')->setEndpoint('www.example-***.com')->setUseCname(true);
```

### 访问专有云或专有域

```
$region = 'YOUR Region';
$endpoint = 'YOUR Endpoint';
$cfg = Oss\Config::loadDefault();
$cfg->setRegion($region)->setEndpoint($endpoint);
```

## HTTP客户端

在大多数情况下，使用具有默认值的默认HTTP客户端 能够满足业务需求。您也可以更改HTTP 客户端，或者更改 HTTP 客户端的默认配置，以满足特定环境下的使用需求。

本部分将介绍如何设置 和 创建 HTTP 客户端。

### 设置HTTP客户端常用配置

通过config修改常用的配置，支持参数如下：

|参数名字 | 说明 | 示例
|:-------|:-------|:-------
|connectTimeout|建立连接的超时时间, 默认值为 10 秒|setConnectTimeout(10)
|readWriteTimeout|应用读写数据的超时时间, 默认值为 20 秒|setReadWriteTimeout(30)
|insecureSkipVerify|是否跳过SSL证书校验，默认检查SSL证书|setInsecureSkipVerify(true)
|enabledRedirect|是否开启HTTP重定向, 默认不开启|setEnabledRedirect(true)
|proxyHost|设置代理服务器|setProxyHost(’http://user:passswd@proxy.example-***.com’)

示例

```
$cfg->setConnectTimeout(10)->setInsecureSkipVerify(true);
```

### 使用 Swoole 配置

常用配置参数在 Swoole 项目中运行会出错，可以使用 $options 中的 handler 修改默认的 HTTP 客户端。

以 hyperf 框架为例，常用配置已经无法正常运行 sdk，需要添加 handler 。

```
<?php
require_once 'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use function Hyperf\Coroutine\co;

$region = 'cn-hangzhou';
$bucket = 'bucket-name';
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion($region);
$cfg->setEndpoint('http://oss-cn-hangzhou.aliyuncs.com');
$client = new Oss\Client($cfg, ['handler' => HandlerStack::create(new CoroutineHandler())]);
co(function () use ($client, $bucket) {
    try {
        $key = 'swoole.txt';
        $data = 'Hello OSS';
        $request = new Oss\Models\PutObjectRequest($bucket, $key);
        $request->body = Oss\Utils::streamFor($data);
        $result = $client->putObject($request);
        echo var_export($result, true);
    } catch (\Exception $e) {
        print_r($e->getMessage());
    }
});

co(function () use ($client, $bucket) {
    try {
        $key = 'hyperf.txt';
        $data = 'Hello OSS';
        $request = new Oss\Models\PutObjectRequest($bucket, $key);
        $request->body = Oss\Utils::streamFor($data);
        $result = $client->putObject($request);
        echo var_export($result, true);
    } catch (\Exception $e) {
        print_r($e->getMessage());
    }
});
```

## 重试

您可以配置对HTTP请求的重试行为。

### 默认重试策略

当没有配置重试策略时，SDK 使用 AlibabaCloud\Oss\V2\Retry\StandardRetryer 作为客户端的默认实现，其默认配置如下：

|参数名称 | 说明 | 默认值
|:-------|:-------|:-------
|maxAttempts|最大尝试次数| 3
|maxBackoff|最大退避时间| 20秒, 20
|baseDelay|基础延迟| 200毫秒, 0.2
|backoff|退避算法| FullJitter 退避,  [0.0, 1.0) * min(2 ^ attempts * baseDealy, maxBackoff)
|errorRetryables|可重试的错误| 具体的错误信息，请参见[重试错误](src/Retry)

当发生可重试错误时，将使用其提供的配置来延迟并随后重试该请求。请求的总体延迟会随着重试次数而增加，如果默认配置不满足您的场景需求时，需要配置重试参数 或者修改重试实现。

### 调整最大尝试次数

您可以通过以下两种方式修改最大尝试次数。例如 最多尝试 5  次

```
$cfg = Oss\Config::loadDefault();
$cfg->setRetryMaxAttempts(5);

或者

$cfg = Oss\Config::loadDefault();
$cfg->setRetryer(new AlibabaCloud\Oss\V2\Retry\StandardRetryer(
    maxAttempts: 5
));
```

### 调整退避延迟

例如 调整 BaseDelay 为 500毫秒，最大退避时间为 25秒

```
$cfg = Oss\Config::loadDefault();
$cfg->setRetryer(new AlibabaCloud\Oss\V2\Retry\StandardRetryer(
    maxBackoff: 25,
    baseDelay: 0.5
));
```

### 调整退避算法

例如 使用固定时间退避算法，每次延迟2秒

```
$cfg = Oss\Config::loadDefault();
$cfg->setRetryer(new AlibabaCloud\Oss\V2\Retry\StandardRetryer(
    backoffDelayer: new Oss\Retry\FixedDelayBackoff(2)
));
```

### 调整重试错误

例如 在原有基础上，新增自定义可重试错误

```
class CustomErrorCodeRetryable implements AlibabaCloud\Oss\V2\Retry\ErrorRetryableInterface
{
    public function isErrorRetryable(\Throwable $reason): bool
    {
        return false;
    }
}

$cfg = Oss\Config::loadDefault();
$cfg->setRetryer(new AlibabaCloud\Oss\V2\Retry\StandardRetryer(
    errorRetryables: [
        new AlibabaCloud\Oss\V2\Retry\ClientErrorRetryable(),
        new AlibabaCloud\Oss\V2\Retry\ServiceErrorCodeRetryable(),
        new AlibabaCloud\Oss\V2\Retry\HTTPStatusCodeRetryable(),
        new CustomErrorCodeRetryable()
    ]
));
```

### 禁用重试

当您希望禁用所有重试尝试时，可以使用 AlibabaCloud\Oss\V2\Retry\NopRetryer 实现
```
$cfg->setRetryer(new AlibabaCloud\Oss\V2\Retry\NopRetryer());
```
## 配置参数汇总

支持的配置参数：

|参数名字 | 说明 | 示例
|:-------|:-------|:-------
|region|(必选)请求发送的区域, 必选|setRegion("cn-hangzhou")
|credentialsProvider|(必选)设置访问凭证|setCredentialsProvider(provider)
|endpoint|访问域名|setEndpoint("oss-cn-hanghzou.aliyuncs.com")
|retryMaxAttempts|HTTP请求时的最大尝试次数, 默认值为 3|setRetryMaxAttempts(5)
|retryer|HTTP请求时的重试实现|setRetryer(customRetryer)
|connectTimeout|建立连接的超时时间, 默认值为 10 秒|setConnectTimeout(5* time.Second)
|readWriteTimeout|应用读写数据的超时时间, 默认值为 20 秒|setReadWriteTimeout(30 * time.Second)
|insecureSkipVerify|是否跳过SSL证书校验，默认检查SSL证书|setInsecureSkipVerify(true)
|enabledRedirect|是否开启HTTP重定向, 默认不开启|setEnabledRedirect(true)
|proxyHost|设置代理服务器|setProxyHost(‘http://user:passswd@proxy.example-***.com’)
|signatureVersion|签名版本，默认值为v4|setSignatureVersion("v1")
|disableSSL|不使用https请求，默认使用https|setDisableSSL(true)
|usePathStyle|使用路径请求风格，即二级域名请求风格，默认为bucket托管域名|setUsePathStyle(true)
|useCName|是否使用自定义域名访问，默认不使用|setUseCName(true)
|useDualStackEndpoint|是否使用双栈域名访问，默认不使用|setUseDualStackEndpoint(true)
|useAccelerateEndpoint|是否使用传输加速域名访问，默认不使用|setUseAccelerateEndpoint(true)
|useInternalEndpoint|是否使用内网域名访问，默认不使用|setUseInternalEndpoint(true)
|additionalHeaders|指定额外的签名请求头，V4签名下有效|setAdditionalHeaders([‘content-length’])
|userAgent|指定额外的User-Agent信息|setUserAgent(‘user identifier’)

# 接口说明

本部分介绍SDK提供的接口, 以及如何使用这些接口。

此部分的其它主题
* [基础接口](#基础接口)
* [预签名接口](#预签名接口)
* [分页器](#分页器)
* [传输管理器](#传输管理器)
* [客户端加密](#客户端加密)
* [其它接口](#其它接口)
* [上传下载接口对比](#上传下载接口对比)

## 基础接口

SDK 提供了 与 REST API 对应的接口，把这类接口叫做 基础接口 或者 低级别API。您可以通过这些接口访问OSS的服务，例如创建存储空间，更新和删除存储空间的配置等。

这些接口采用了相同的命名规则，其接口定义如下：

```
public function <operationName>(Models\<OperationName>Request $request, array $args = []): Models\<OperationName>Result
public function <operationName>Async(Models\<OperationName>Request $request, array $args = []): \GuzzleHttp\Promise\Promise
```

**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|request|\<OperationName\>Request|设置具体接口的请求参数，例如bucket，key
|args|array|(可选)接口级的配置参数, 例如修改此次调用接口时读写超时

**返回值列表**：
|返回值名|类型|说明
|:-------|:-------|:-------
|result|\<OperationName\>Result|接口返回值
## 预签名接口

您可以使用预签名接口生成预签名URL，授予对存储空间中对象的限时访问权限，或者允许他人将特定对象的上传到存储空间。在过期时间之前，您可以多次使用预签名URL。

预签名接口定义如下：
```
public function presign(request: <OperationName>Request,args: $args): Models\PresignResult
```

**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|request|object|设置需要生成签名URL的接口名，和 '$OperationNameRequest' 一致
|args|array|(可选)，设置过期时间，如果不指定，默认有效期为15分钟

**返回值列表**：
|返回值名|类型|说明
|:-------|:-------|:-------
|result|PresignResult|返回结果，包含 预签名URL，HTTP 方法，过期时间 和 参与签名的请求头

**request参数支持的类型**：
|类型|对应的接口
|:-------|:-------
|GetObjectRequest|GetObject
|PutObjectRequest|PutObject
|HeadObjectRequest|HeadObject
|InitiateMultipartUploadRequest|InitiateMultipartUpload
|UploadPartRequest|UploadPart
|CompleteMultipartUploadRequest|CompleteMultipartUpload
|AbortMultipartUploadRequest|AbortMultipartUpload

**args选项**
|选项值|类型|说明
|:-------|:-------|:-------
|expires|DateTime|从当前时间开始，多长时间过期。例如 设置一个有效期为30分钟，new DateInterval('PT30M')
|expiration|DateInterval|绝对过期时间

> **注意:** 在签名版本4下，有效期最长为7天。同时设置 expiration和 expires时，优先取 expiration。

**PresignResult返回值**：
|参数名|类型|说明
|:-------|:-------|:-------
|method|string|HTTP 方法，和 接口对应，例如GetObject接口，返回 GET
|url|string|预签名 URL
|expiration|DateTime| 签名URL的过期时间
|signedHeaders|array|被签名的请求头，例如PutObject接口，设置了Content-Type 时，会返回 Content-Type 的信息。


示例
1. 为对象生成预签名 URL，然后下载对象（GET 请求）
```
$client = new Oss\Client($cfg);
$result = $client->presign(new Oss\Models\GetObjectRequest('bucket', 'key'));
$httpClient = new GuzzleHttp\Client();
$response = $httpClient->request($result->method, $result->url);
```

2. 为上传生成预签名 URL, 设置自定义元数据，有效期为10分钟，然后上传文件（PUT 请求）
```
$client = new Oss\Client($cfg);
$result = $client->presign(new Oss\Models\PutObjectRequest('bucket', 'key', metadata: ['user' => 'jack']), ['expires' => new DateInterval('PT10M')]);
$httpClient = new GuzzleHttp\Client();
$response = $httpClient->request($result->method, $result->url, ['headers' => $result->signedHeaders]);
```

更多的示例，请参考 sample 目录

## 分页器

对于列举类接口，当响应结果太大而无法在单个响应中返回时，都会返回分页结果，该结果同时包含一个用于检索下一页结果的标记。当需要获取下一页结果时，您需要在发送请求时设置该标记。

对常用的列举接口，V2 SDK 提供了分页器（Paginator），支持自动分页，当进行多次调用时，自动为您获取下一页结果。使用分页器时，您只需要编写处理结果的代码。

分页器 包含了 分页器对象 '\<OperationName\>Paginator'。分页器创建方法返回一个分页器对象，该对象通过'isTruncated' 参数判断是否还有更多页, 并调用操作来获取下一页。

分页器对象初始化 '\<OperationName\>Paginator' 里的 request 参数类型 与 '\<OperationName\>' 接口中的 reqeust 参数类型一致。

'\<OperationName\>Paginator' 返回的结果类型是一个Generator 对象， 该对象是 '\<OperationName\>' 接口 返回的结果类型 的集合。

```
public function iterPage(<OperationName>Request $request, array $args = []): \Generator
{
    $limit = $args['limit'] ?? ($this->limit ?? null);
    if (isset($args['limit'])) {
        unset($args['limit']);
    }
    $req = clone $request;
    if (isset($limit) && is_int($limit)) {
        $req->maxKeys = $limit;
    }

    $firstPage = true;
    $isTruncated = false;

    while ($firstPage || $isTruncated) {
        $result = $this->client-><OperationName>($req, $args);
        yield $result;

        $firstPage = false;
        $isTruncated = $result->isTruncated ?? false;
        $req->continuationToken = $result->nextContinuationToken;
    }
}

```

支持的分页器对象如下：
|分页器对象|创建方法|对应的列举接口
|:-------|:-------|:-------
|ListObjectsPaginator|new Oss\Paginator\ListObjectsPaginator($client)|ListObjects, 列举存储空间中的对象信息
|ListObjectsV2Paginator|new Oss\Paginator\ListObjectsV2Paginator($client)|ListObjectsV2, 列举存储空间中的对象信息
|ListObjectVersionsPaginator|new Oss\Paginator\ListObjectVersionsPaginator($client)|ListObjectVersions, 列举存储空间中的对象版本信息
|ListBucketsPaginator|new Oss\Paginator\ListBucketsPaginator($client)|ListBuckets, 列举存储空间
|ListPartsPaginator|new Oss\Paginator\ListPartsPaginator($client)|ListParts, 列举指定Upload ID所属的所有已经上传成功分片
|ListMultipartUploadsPaginator|new Oss\Paginator\ListMultipartUploadsPaginator($client)|ListMultipartUploads, 列举存储空间中的执行中的分片上传事件

**args选项说明：**
|参数|说明
|:-------|:-------
|Limit|指定返回结果的最大数


以 ListObjects 为例，分页器遍历所有对象 和 手动分页遍历所有对象 对比

```
// 分页器遍历所有对象
...
$client = new Oss\Client($cfg);
$paginator = new Oss\Paginator\ListObjectsPaginator($client);
$iter = $paginator->iterPage(new Oss\Models\ListObjectsRequest($bucket));
foreach ($iter as $page) {
    foreach ($page->contents ?? [] as $object) {
        printf("object name:%s,type:%s,size:%s" . PHP_EOL, $object->key, $object->type, $object->size);
    }
}
```

```
// 手动分页遍历所有对象
...
$client = new Oss\Client($cfg);
$firstPage = true;
$isTruncated = false;
$request = new Oss\Models\ListObjectsRequest($bucket);
while ($firstPage || $isTruncated) {
    $result = $client->listObjects($request);
    foreach ($result->contents as $object) {
        printf("object name:%s,type:%s,size:%s" . PHP_EOL, $object->key, $object->type, $object->size);
    }
    $firstPage = false;
    $isTruncated = $result->isTruncated ?? false;
    $request->marker = $result->nextMarker;
}
```

## 传输管理器

针对大文件的传输场景，新增了 'Uploader'，'Downloader' 和 'Copier' 模块，分别管理对象的 上传，下载 和 拷贝。

### 上传管理器(Uploader)

上传管理器 利用分片上传接口，把大文件或者流分成多个较小的分片并发上传，提升上传的性能。
</br>针对文件的上传场景，还提供了断点续传的能力，即在上传过程中，记录已完成的分片状态，如果出现网络中断、程序异常退出等问题导致文件上传失败，甚至重试多次仍无法完成上传，再次上传时，可以通过断点记录文件恢复上传。

```
final class Uploader
{
	...
    public function __construct($client, array $args = [])

    public function uploadFile(Models\PutObjectRequest $request, string $filepath, array $args = []): Models\UploadResult

    public function uploadFrom(Models\PutObjectRequest $request, StreamInterface $stream, array $args = []): Models\UploadResult
	...
}
```

**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|request|PutObjectRequest|上传对象的请求参数，和 PutObject 接口的 请求参数一致
|stream|StreamInterface|需要上传的流。
|filePath|string|本地文件路径
|args|array|(可选)，配置选项


**args选项说明：**
|参数|类型|说明
|:-------|:-------|:-------
|part_size|int|指定分片大小，默认值为 6MiB
|parallel_num|int|指定上传任务的并发数，默认值为 3。针对的是单次调用的并发限制，而不是全局的并发限制
|leave_parts_on_error|bool|当上传失败时，是否保留已上传的分片，默认不保留

当使用Uploader实例化实例时，您可以指定多个配置选项来自定义对象的上传行为。也可以在每次调用上传接口时，指定多个配置选项来自定义每次上传对象的行为。

设置Uploader的配置参数
```
$u = $client->newUploader(['part_size' => 10 * 1024 * 1024]);
```

设置每次上传请求的配置参数
```
$u->uploadFile(
    new Oss\Models\PutObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    filepath: '/local/dir/example',
    args: [
        'part_size' => 10 * 1024 * 1024,
    ]
);
```

示例

1. 使用 Uploader上传流

```
...
$client = new Oss\Client($cfg);
$u = $client->newUploader();
$result = $u->uploadFrom(
    new Oss\Models\PutObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    stream: new \GuzzleHttp\Psr7\LazyOpenStream('/local/dir/example', 'rb'),
);
printf(
    'upload form status code:' . $result->statusCode . PHP_EOL .
    'upload form request id:' . $result->requestId . PHP_EOL .
    'upload form result:' . var_export($result, true) . PHP_EOL
);
```

2. 使用 Uploader上传文件

```
...
$client = new Oss\Client($cfg);
$u = $client->newUploader();
$result = $u->uploadFile(
    new Oss\Models\PutObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    filepath: '/local/dir/example',
);
printf(
    'upload file status code:' . $result->statusCode . PHP_EOL .
    'upload file request id:' . $result->requestId . PHP_EOL .
    'upload file result:' . var_export($result, true) . PHP_EOL
);
```
### 下载管理器(Downloader)

下载管理器 利用范围下载，把大文件分成多个较小的分片并发下载，提升下载的性能。

```
final class Downloader
{
	...
    public function __construct($client, array $args = [])
    
    public function downloadFile(Models\GetObjectRequest $request, string $filepath, array $args = []): Models\DownloadResult   
    
    public function downloadTo(Models\GetObjectRequest $request, \Psr\Http\Message\StreamInterface $stream, array $args = []): Models\DownloadResult
}
```

**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|request|GetObjectRequest|下载对象的请求参数，和 GetObject 接口的 请求参数一致
|filePath|string|本地文件路径
|args|array|(可选)，配置选项


**args选项说明：**
|参数|类型|说明
|:-------|:-------|:-------
|part_size|int|指定分片大小，默认值为 6MiB
|parallel_num|int|指定上传任务的并发数，默认值为 3。针对的是单次调用的并发限制，而不是全局的并发限制
|use_temp_file|bool|下载文件时，是否使用临时文件，默认使用。先下载到临时文件上，当成功后，再重命名为目标文件

当使用Downloader实例化实例时，您可以指定多个配置选项来自定义对象的下载行为。也可以在每次调用下载接口时，指定多个配置选项来自定义每次下载对象的行为。

设置Downloader的配置参数
```
$d = $client->newDownloader(['part_size' => 10 * 1024 * 1024]);
```

设置每次下载请求的配置参数
```
$d->downloadFile(
    new Oss\Models\GetObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    filepath: '/local/dir/example',
    args: ['part_size' => 10 * 1024 * 1024]
);
```

示例

1. 使用 Downloader 下载到本地文件

```
...
$client = new Oss\Client($cfg);
$d = $client->newDownloader();
$d->downloadFile(
    new Oss\Models\GetObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    filepath: '/local/dir/example',
);
```
2. 使用 Downloader 下载到流中

```
...
$client = new Oss\Client($cfg);
$d = $client->newDownloader();
$stream = new \GuzzleHttp\Psr7\BufferStream(1 * 1024 * 1024);
$d->downloadTo(
    new Oss\Models\GetObjectRequest(
        bucket: 'bucket',
        key: 'key'
    ),
    stream: $stream,
);
```

### 拷贝管理器(Copier)
当需要将对象从存储空间复制到另外一个存储空间，或者修改对象的属性时，您可以通过拷贝接口 或者分片拷贝接口来完成这个操作。
</br>这两个接口有其适用的场景，例如：
* 拷贝接口(CopyObject) 只适合拷贝 5GiB 以下的对象；
* 分片拷贝接口(UploadPartCopy) 不支持 元数据指令(x-oss-metadata-directive) 和 标签指令(x-oss-tagging-directive) 参数,
  拷贝时，您需要主动去设置需要复制的元数据和标签。
* 服务端优化了拷贝(CopyObject)接口，使其具备浅拷贝的能力，在特定的场景下也支持拷贝大文件。

拷贝管理器提供了通用的拷贝接口，隐藏了接口的差异和实现细节，根据拷贝的请求参数，自动选择合适的接口复制对象。

```
final class Copier
{
    ...
    public function __construct($client, array $args = [])

    public function copy(Models\CopyObjectRequest $request, array $args = []): Models\CopyResult
}
```
**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|request|CopyObjectRequest|拷贝对象的请求参数，和 CopyObject 接口的 请求参数一致
|args|array|(可选)，配置选项


**args选项说明：**
|参数|类型|说明
|:-------|:-------|:-------
|part_size|int|指定分片大小，默认值为 64MiB
|parallel_num|int|指定上传任务的并发数，默认值为 3。针对的是单次调用的并发限制，而不是全局的并发限制
|multipart_copy_threshold|int|使用分片拷贝的阈值，默认值为 200MiB
|leave_parts_on_error|bool|当拷贝失败时，是否保留已拷贝的分片，默认不保留
|disable_shallow_copy|bool|不使用浅拷贝行为，默认使用


当使用Copier实例化实例时，您可以指定多个配置选项来自定义对象的下载行为。也可以在每次调用下载接口时，指定多个配置选项来自定义每次下载对象的行为。

设置Copier的配置参数
```
$c = $client->newCopier(['part_size' => 100 * 1024 * 1024]);
```

设置每次拷贝请求的配置参数
```
$c->copy(
    new Oss\Models\CopyObjectRequest(
        bucket: 'bucket',
        key: 'key',
        sourceBucket: 'src-bucket',
        sourceKey: 'src-key',
    ),
    args: ['part_size' => 100 * 1024 * 1024]
);
```

> **注意:**
> </br>拷贝对象时，CopyObjectRequest 中 metadataDirective 属性决定了对象元数据的拷贝行为，默认 复制 源对象标签
> </br>拷贝对象时，CopyObjectRequest 中 taggingDirective 属性决定了对象标签的拷贝行为，默认 复制 源对象标签


示例

1. 拷贝文件，默认会复制 元数据 和 标签
```
...
$client = new Oss\Client($cfg);
$c = $client->newCopier();
$result = $c->copy(
    new Oss\Models\CopyObjectRequest(
        bucket: 'bucket',
        key: 'key',
        sourceBucket: 'src-bucket',
        sourceKey: 'src-key',
    )
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);
```

2. 拷贝文件，只拷贝数据，不拷贝元数据和标签
```
...
$client = new Oss\Client($cfg);
$c = $client->newCopier();
$result = $c->copy(
    new Oss\Models\CopyObjectRequest(
        bucket: 'bucket',
        key: 'key',
        sourceBucket: 'src-bucket',
        sourceKey: 'src-key',
        metadataDirective: 'Replace',
        taggingDirective: 'Replace'
    )
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);
```

3. 修改 对象的存储类型 为标准类型

```
...
$client = new Oss\Client($cfg);
$c = $client->newCopier();
$result = $c->copy(
    new Oss\Models\CopyObjectRequest(
        bucket: 'bucket',
        key: 'key',
        sourceBucket: 'src-bucket',
        sourceKey: 'src-key',
        storageClass: 'Standard',
    )
);
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);
```
## 客户端加密

客户端加密是在数据上传至OSS之前，由用户在本地对数据进行加密处理，确保只有密钥持有者才能解密数据，增强数据在传输和存储过程中的安全性。

> **注意:**
> </br>使用客户端加密功能时，您需要对主密钥的完整性和正确性负责。
> </br>在对加密数据进行复制或者迁移时，您需要对加密元数据的完整性和正确性负责。

如果您需要了解OSS客户端加密实现的原理，请参考OSS用户指南中的[客户端加密](https://www.alibabacloud.com/help/zh/oss/user-guide/client-side-encryption)。

使用客户端加密，首先您需要实例化加密客户端，然后调用其提供的接口进行操作。您的对象将作为请求的一部分自动加密和解密。

```
final class EncryptionClient
{
    ...

    public function __construct(Client $client, Crypto\MasterCipherInterface $masterCipher, ?array $decryptMasterCiphers = null)
}
```

**参数列表**：
|参数名|类型|说明
|:-------|:-------|:-------
|client|Client| 非加密客户端实例
|masterCipher|Crypto\MasterCipherInterface|主密钥实例，用于加密和解密数据密钥
|decryptMasterCiphers|array|(可选)，加密客户端配置选项

**返回值列表**：
|返回值名|类型|说明
|:-------|:-------|:-------
|eclient|EncryptionClient|加密客户端实例

**decryptMasterCiphers选项说明：**
|参数|类型|说明
|:-------|:-------|:-------
|decryptMasterCiphers|array<Crypto\MasterCipherInterface>|主密钥实例组, 用于解密数据密钥。

**EncryptionClient接口：**
|基础接口名|说明
|:-------|:-------
|GetObjectMeta|获取对象的部分元信息
|HeadObject|获取对象的部元信息
|GetObject|下载对象，并自动解密
|PutObject|上传对象，并自动加密
|InitiateMultipartUpload|初始化一个分片上传事件 和 分片加密上下文（EncryptionMultiPartContext）
|UploadPart|初始化一个分片上传事件, 调用该接口上传分片数据，并自动加密。调用该接口时，需要设置 分片加密上下文
|CompleteMultipartUpload|在将所有分片数据上传完成后，调用该接口合并成一个文件
|AbortMultipartUpload|取消分片上传事件,并删除对应的分片数据
|ListParts|列举指定上传事件所属的所有已经上传成功分片
|**高级接口名**|**说明**
|NewDownloader|创建下载管理器实例
|NewUploader|创建上传管理器实例
|**辅助接口名**|**说明**
|Unwrap|获取非加密客户端实例，可以通过该实例访问其它基础接口

> **说明:** EncryptionClient 采用了 和 Client 一样的接口命名规则 和 调用方式，有关接口的详细用法，请参考指南的其它章节说明。

### 使用RSA主密钥

**创建RAS加密客户端**

```
use AlibabaCloud\Oss\V2 as Oss;
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();
$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion('cn-hangzhou');
$client = new Oss\Client($cfg);

// 创建一个主密钥的描述信息，创建后不允许修改。主密钥描述信息和主密钥一一对应。
// 如果所有的对象都使用相同的主密钥，主密钥描述信息可以为空，但后续不支持更换主密钥。
// 如果主密钥描述信息为空，解密时无法判断使用的是哪个主密钥。
// 强烈建议为每个主密钥都配置主密钥描述信息，由客户端保存主密钥和描述信息之间的对应关系。
$materialDesc['desc'] = 'your master encrypt key material describe information';

// 创建只包含 主密钥 的 加密客户端
$masterCipher = new Oss\Crypto\MasterRsaCipher(
    publicKey: "yourRsaPublicKey",
    privateKey: "yourRsaPrivateKey",
    matDesc: $materialDesc
);
$eclient = new Oss\EncryptionClient($client, $masterCipher);

// 创建包含主密钥 和 多个解密密钥的 加密客户端
// 当解密时，先匹配解密密钥的描述信息，如果不匹配，则使用主密钥解密
//$decryptMC = new Oss\Crypto\MasterRsaCipher(
//    // TODO
//);
//$eclient = new Oss\EncryptionClient($client, $masterCipher,[$decryptMC]);
```

**使用加密客户端上传或者下载**
```
...
$eclient = new Oss\EncryptionClient($client, $masterCipher);
// Use PutObject
$eclient->putObject(
    new Oss\Models\PutObjectRequest(
        bucket: 'bucket',
        key: 'key',
        body: Oss\Utils::streamFor('hi oss')
    )
);

// Use GetObject
$eclient->getObject(
    new Oss\Models\GetObjectRequest(
        bucket: 'bucket',
        key: 'key',
    )
);

// Use Downloader
$d = $eclient->newDownloader();
$d->downloadFile(
    new Oss\Models\GetObjectRequest(
        bucket: 'bucket',
        key: 'key',
    ),
    filepath: '/local/dir/example',
);

// Use Uploader
$u = $eclient->newUploader();
$u->uploadFile(
    new Oss\Models\PutObjectRequest(
        bucket: 'bucket',
        key: 'key',
        body: Oss\Utils::streamFor('hi oss')
    ),
    filepath: '/local/dir/example',
);
```

**使用加密客户端以分片方式上传数据**
</br>以上传500K内存数据为例
```
...
$eclient = new Oss\EncryptionClient($client, $masterCipher);
$partSize = 200 * 1024;
$length = 500 * 1024;
$bucket = 'bucket';
$key = 'key';
// 文件的大小是  500 * 1024
$fileName = '/local/dir/example';

// 加密客户端 需要 设置分片大小和总文件大小
$initResult = $client->initiateMultipartUpload(
    new Oss\Models\InitiateMultipartUploadRequest(
        bucket: $bucket,
        key: $key,
        cseDataSize: $length,
        csePartSize: $partSize
    )
);

$file = fopen($fileName, 'r');
$parts = array();
if ($file) {
    $i = 1;
    while (!feof($file)) {
        $chunk = fread($file, $partSize);
        $partResult = $eclient->uploadPart(new Oss\Models\UploadPartRequest(
            bucket: $bucket,
            key: $key,
            partNumber: $i,
            uploadId: $initResult->uploadId,
            contentLength: strlen($chunk),
            body: Oss\Utils::streamFor($chunk),
            encryptionMultipartContext: $initResult->encryptionMultipartContext,
        ));
        $part = new Oss\Models\UploadPart(
            $i,
            $partResult->etag,
        );
        array_push($parts, $part);
        $i++;
    }
    fclose($file);
}
$comResult = $eclient->completeMultipartUpload(
    new Oss\Models\CompleteMultipartUploadRequest(
        bucket: $bucket,
        key: $key,
        uploadId: $initResult->uploadId,
        completeMultipartUpload: new Oss\Models\CompleteMultipartUpload(
            $parts
        ),
    )
);
```
### 使用自定义主密钥
当RSA主密钥方式无法满足需求时，您可自定主密钥的加密实现。主密钥的接口定义如下：
```
interface MasterCipherInterface
{
    public function encrypt(string $data): string;

    public function decrypt(string $data): string;

    public function getWrapAlgorithm(): string;
    
    public function getMatDesc(): string;
}
```
**MasterCipher接口说明**
|接口名|说明
|:-------|:-------
|encrypt|加密 数据加密密钥 和 加密数据的初始值(IV)
|decrypt|解密 数据加密密钥  和 加密数据的初始值(IV)
|getWrapAlgorithm|返回 数据密钥的加密算法信息，建议采用 算法/模式/填充 格式，例如RSA/NONE/PKCS1Padding
|getMatDesc|返回 主密钥的描述信息，JSON格式

例如

```
...
final class MasterCustomCipher implements Oss\Crypto\MasterCipherInterface
{
    private $matDesc;

    private ?string $secrectKey;

    public function __construct(
        $matDesc = null,
        ?string $secrectKey = null
    )
    {
        $this->secrectKey = $secrectKey;
        $this->matDesc = null;
        if (\is_array($matDesc)) {
            $val = json_encode($matDesc);
            if ($val !== false) {
                $this->matDesc = $val;
            }
        } else if (is_string($matDesc)) {
            $this->matDesc = $matDesc;
        }
    }

    public function encrypt(string $data): string
    {
        // TODO
    }

    public function decrypt(string $data): string
    {
        // TODO
    }

    public function getWrapAlgorithm(): string
    {
        return "Custom/None/NoPadding";
    }

    public function getMatDesc(): string
    {
        return $this->matDesc;
    }
}

$client = new Oss\Client($cfg);
$materialDesc['desc'] = 'your master encrypt key material describe information';
$masterCipher = new MasterCustomCipher(
    matDesc: $materialDesc,
    secrectKey: "yourSecrectKey"
);
$eclient = new Oss\EncryptionClient($client, $masterCipher);
```

## 其它接口

为了方便用户使用，封装了一些易用性接口。当前扩展的接口如下：

|接口名 | 说明
|:-------|:-------
|isObjectExist|判断对象(object)是否存在
|isBucketExist|判断存储空间(bucket)是否存在
|putObjectFromFile|上传本地文件到存储空间
|getObjectToFile|下载对象到本地文件

### isObjectExist/isBucketExist

这两个接口的返回值为 bool  如果bool 为 true，表示存在，如果 bool值为 false，表示不存在。当抛出异常 时，表示无法从该错误信息判断 是否存在。

```
public function isObjectExist(string $bucket, string $key, ?string $versionId = null, array $args = []): bool
public function isBucketExist(string $bucket, array $args = []): bool
```

例如 判断对象是否存在

```
client := oss.NewClient(cfg)

$existed = $client->isObjectExist( bucket: "examplebucket", key: "exampleobject");
//$existed = $client->isObjectExist( bucket: 'examplebucket', key: 'exampleobject',versionId: 'versionId');

printf('object existed :%s',var_export($existed,true));
```

### PutObjectFromFile

使用简单上传(PutObject)接口 把本地文件上传到存储空间，该接口不支持并发。

```
public function putObjectFromFile(Models\PutObjectRequest $request, string $filePath, array $args = []): Models\PutObjectResult
```

示例

```
...
$client = new Oss\Client($cfg);

$result = $client->putObjectFromFile(
    new Oss\Models\PutObjectRequest(
        bucket: 'examplebucket',
        key: 'exampleobject',
    ),
    filePath: '/local/dir/example'
);
```

### GetObjectToFile

使用GetObject接口，把存储空间的对象下载到本地文件，该接口不支持并发。

```
public function getObjectToFile(Models\GetObjectRequest $request, string $filePath, array $args = []): Models\GetObjectResult
```

示例

```
...
$client = new Oss\Client($cfg);

$client->getObjectToFile(
    new Oss\Models\GetObjectRequest(
        bucket: 'examplebucket',
        key: 'exampleobject',
    ),
    filePath: '/local/dir/example'
);
```

## 上传下载接口对比

提供了各种上传下载接口，您可以根据使用场景，选择适合的接口。

**上传接口**
|接口名 | 说明
|:-------|:-------
|putObject|简单上传, 最大支持5GiB
|putObjectFromFile|与putObject接口能力一致</br>请求body数据来源于文件路径
|分片上传接口</br>initiateMultipartUpload</br>uploadPart</br>completeMultipartUpload|分片上传，单个分片最大5GiB，文件最大48.8TiB
|uploadFrom|封装了简单上传 和 分片上传接口，最大支持48.8TiB
|uploadFile|与uploadFrom接口能力一致</br>请求body数据来源于文件路径
|appendObject|追加上传, 最终文件最大支持5GiB

**下载接口**
|接口名| 说明
|:-------|:-------
|getObject|流式下载, 响应体为StreamInterface类型
|getObjectToFile|下载到本地文件</br>单连接下载
|downloadFile|采用分片方式下载到本地文件</br>支持自定义分片大小和并发数
# 场景示例

本部分将从使用场景出发, 介绍如何使用SDK。

包含的主题
* [数据校验](#数据校验)

OSS提供基于MD5的数据校验，确保请求的过程中的数据完整性。

## MD5校验

当向OSS发送请求时，如果设置了Content-MD5，OSS会根据接收的内容计算MD5。当OSS计算的MD5值和上传提供的MD5值不一致时，则返回InvalidDigest异常，从而保证数据的完整性。

基础接口里， 除了PutObject, AppendObject, UploadPart 接口外，会自动计算MD5, 并设置Content-MD5, 保证请求的完整性。

如果您需要在 PutObject, AppendObject, UploadPart 接口里使用MD5校验，可以参考以下写法

```
...
$client = new Oss\Client($cfg);

$body = Oss\Utils::streamFor('hi oss');
$result = $client->putObject(
    new Oss\Models\PutObjectRequest(
        bucket: 'demo-1889',
        key: 'demo',
        contentMd5: base64_encode(md5($body, true)),
        body: $body
    ));
printf(
    'status code:' . $result->statusCode . PHP_EOL .
    'request id:' . $result->requestId . PHP_EOL .
    'result:' . var_export($result, true)
);
```
# 迁移指南

本部分介绍如何从V1 版本([aliyun-oss-php-sdk](https://github.com/aliyun/aliyun-oss-php-sdk)) 迁移到 V2 版本。
## 最低 PHP 版本

V2 版本 要求 PHP 版本最低为 7.4。
## 导入路径

V2 版本使用新的代码仓库，同时也对代码结构进行了调整，按照功能模块组织，以下是这些模块路径和说明：

|模块路径 | 说明
|:-------|:-------
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src|SDK核心，接口 和 高级接口实现
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Credentials|访问凭证相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Retry|重试相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Signer|签名相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Annotation|tag，xml 转换相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Crypto|客户端加密相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Models|请求对象，返回对象相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Paginator|列表分页器相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Exception|异常类型相关
|github.com/aliyun/alibabacloud-oss-php-sdk-v2/src/Types|类型相关

示例

```
// v1 
require_once 'vendor/autoload.php';
use OSS\OssClient;
```

```
// v2 
require_once  'vendor/autoload.php';

use AlibabaCloud\Oss\V2 as Oss;
```

## 配置加载

V2 版本简化了配置设置方式，全部迁移到 [config](src/Config.php) 下，并提供了以set为前缀的辅助函数，方便以编程方式覆盖缺省配置。

V2 默认使用 V4签名，所以必须配置区域（Region）。

V2 支持从区域（Region）信息构造 访问域名(Endpoint), 当访问的是公有云时，可以不设置Endpoint。

示例

```
// v1
require_once 'vendor/autoload.php';
use OSS\OssClient;
...

// 环境变量中获取访问凭证
$provider = new \OSS\Credentials\EnvironmentVariableCredentialsProvider();

// Endpoint
$endpoint = "http://oss-cn-hangzhou.aliyuncs.com";

$config = array(
       "provider" => $provider,
       "endpoint" => $endpoint,
       "signatureVersion" => OssClient::OSS_SIGNATURE_VERSION_V4,
       "region" => "cn-hangzhou"
   );
$ossClient = new OssClient($config);
// 不校验SSL证书校验
$ossClient->setUseSSL(false);
```

```
// v2
require_once 'vendor/autoload.php';
use AlibabaCloud\Oss\V2 as Oss;
...

// 环境变量中获取访问凭证
$provider = new \OSS\Credentials\EnvironmentVariableCredentialsProvider();

$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
// 设置HTTP连接超时时间为20秒
$cfg->setConnectTimeout(20);
// HTTP读取或写入超时时间为60秒
$cfg->setReadWriteTimeout(60);
// 不校验SSL证书校验
$cfg->setDisableSSL(true);
// 设置区域
$cfg->setRegion('cn-hangzhou');
$client = new Oss\Client($cfg);
```

## 创建Client

V2 版本 把 Client 的创建 函数 从 New 修改 为 NewClient， 同时 创建函数 不在支持传入Endpoint 以及 access key id 和 access key secrect 参数。

示例

```
// v1
$endpoint = "http://oss-cn-hangzhou.aliyuncs.com";

$config = array(
    "provider" => $provider,
    "endpoint" => $endpoint,
    "signatureVersion" => OssClient::OSS_SIGNATURE_VERSION_V4,
    "region" => "cn-hangzhou"
);
$ossClient = new OssClient($config);

or

$ossClient = new OssClient('accessKeyId','accessKeySecret','endpoint');
```

```
// v2
$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion('cn-hangzhou');
$ossClient = new Oss\Client($cfg);
```

## 调用API操作

基础 API 接口 都 合并为 单一操作方法 '\<OperationName\>'，操作的请求参数为 '\<OperationName\>Request'，操作的返回值为 '\<OperationName\>Result'。这些操作方法都 迁移到 Client下，如下格式：

```
public function <operationName>(Models\<OperationName>Request $request, array $args = []): Models\<OperationName>Result
public function <operationName>Async(Models\<OperationName>Request $request, array $args = []): \GuzzleHttp\Promise\Promise
```

关于API接口的详细使用说明，请参考[基础接口](#基础接口)。

示例

```
// v1
require_once 'vendor/autoload.php';
use OSS\OssClient;
$provider = new \OSS\Credentials\EnvironmentVariableCredentialsProvider();
$endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
$config = array(
    "provider" => $provider,
    "endpoint" => $endpoint,
    "signatureVersion" => OssClient::OSS_SIGNATURE_VERSION_V4,
    "region" => "cn-hangzhou"
);
$ossClient = new OssClient($config);
$ossClient->putObject('examplebucket','exampleobject.txt','example data');
```

```
// v2
require_once 'vendor/autoload.php';
use AlibabaCloud\Oss\V2 as Oss;

$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion('cn-hangzhou');
$ossClient = new Oss\Client($cfg);

$client->putObject(
    new Oss\Models\PutObjectRequest(
        bucket: 'examplebucket',
        key: 'exampleobject.txt',
        body:  Oss\Utils::streamFor('example data'),
    ),
);
```

## 预签名

V2 版本 把 预签名接口 名字从 signUrl 修改为 presign，接口形式如下：

```
public function presign(request: $request,args: $options): Models\PresignResult
```
对于 request 参数，其类型 与 API 接口中的 '\<OperationName\>Request' 一致。

对于返回结果，除了返回 预签名 URL 外，还返回 HTTP 方法，过期时间 和 被签名的请求头，如下：
```
final class PresignResult
{
    public ?string $method;
    
    public ?string $url;
    
    public ?\DateTime $expiration;
    
    public ?array $signedHeaders;
}
```

关于预签名的详细使用说明，请参考[预签名接口](#预签名接口)。

以 生成下载对象的预签名URL 为例，如何从 V1 迁移到 V2

```
// v1
require_once 'vendor/autoload.php';
use OSS\OssClient;
$provider = new \OSS\Credentials\EnvironmentVariableCredentialsProvider();
$endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
$config = array(
    "provider" => $provider,
    "endpoint" => $endpoint,
    "signatureVersion" => OssClient::OSS_SIGNATURE_VERSION_V4,
    "region" => "cn-hangzhou"
);
$ossClient = new OssClient($config);
$url = $ossClient->signUrl('examplebucket','exampleobject.txt',60,OssClient::OSS_HTTP_GET);

printf("Sign Url:%s\n", $url);
```
```
// v2
require_once 'vendor/autoload.php';
use AlibabaCloud\Oss\V2 as Oss;

$credentialsProvider = new Oss\Credentials\EnvironmentVariableCredentialsProvider();

$cfg = Oss\Config::loadDefault();
$cfg->setCredentialsProvider($credentialsProvider);
$cfg->setRegion('cn-hangzhou');
$ossClient = new Oss\Client($cfg);

$result = $ossClient->presign(
    new Oss\Models\GetObjectRequest(
        bucket: 'examplebucket',
        key: 'exampleobject.txt',
    ),
    args: ['expiration' => (new \DateTime('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT60S'))],
);
print(
    'sign url:' . $result->url . PHP_EOL .
    'sign method :' . $result->method . PHP_EOL .
    'sign expiration:' . var_export($result->expiration, true) . PHP_EOL .
    'sign headers:' . var_export($result->signedHeaders, true) . PHP_EOL
);
```