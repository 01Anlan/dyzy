# ChangeLog - Alibaba Cloud OSS SDK for PHP v2

## 版本号：0.4.0 日期：2025-12-25
### 变更内容
- Feature: Add seal append object api
- Feature: Add bucket overwrite config api
- Break Change: Change the name and type of LifecycleRuleFilter.not

## 版本号：0.3.2 日期：2025-11-07
### 变更内容
- Update: ListBuckets api supports more parameters
- Update: Routing rule supports more parameters
- Update: Bucket logging supports more parameters
- Update: Bucket https configuration supports more parameters
- Update: Normalize parameter naming
- Fix：Rename bucket request payment class name

## 版本号：0.3.1 日期：2025-06-13
### 变更内容
- Update：Meta query api supports more search condition settings
- Update：Api-level options supports credentials_provider
 
## 版本号：0.3.0 日期：2025-04-24
### 变更内容
- Feature：Add bucket inventory api
- Feature：Add bucket style api
- Feature：Add bucket resource group api
- Feature：Add bucket https config api
- Feature：Add bucket redundancy transition api
- Feature：Add list cloud boxes api and add cloud box id param
- Feature：Add bucket replication api
- Feature：Add access point api

## 版本号：0.2.1 日期：2025-03-18
### 变更内容
- Fix：Add a default content-type if possible.
- Fix：Use end() to get last error
- Fix：Compatibility with php 8.0

## 版本号：0.2.0 日期：2025-02-27
### 变更内容
- Feature：Add bucket lifecycle api
- Feature：Add bucket bucket transfer acceleration api
- Feature：Add bucket policy api
- Feature：Add bucket logging api
- Feature：Add bucket website api
- Feature：Add bucket referer api
- Feature：Add bucket tags api
- Feature：Add bucket request payment api
- Feature：Add bucket cors api
- Feature：Add bucket cname api
- Feature：Add bucket access monitor api
- Feature：Add bucket meta query api
- Feature：Add bucket encryption api
- Feature：Add bucket archive direct read api
- Feature：Add public access block api for oss resource, bucket resource
- Update：change guzzlehttp/psr7 version to "^2.7"
  
## 版本号：0.1.0 日期：2025-01-03
### 变更内容
- Feature：Add credentials provider
- Feature：Add retryer
- Feature：Add signer v4/v1
- Feature：Add annotation for 8.x
- Feature：Add bucket's basic api
- Feature：Add object's api
- Feature：Add presigner
- Feature：Add paginator
- Feature：Add uploader, downloader and copier
- Feature：Add encryption client
- Feature：Add isObjectExist/isBucketExist api
- Feature：Add putObjectFromFile/getObjectToFile api
