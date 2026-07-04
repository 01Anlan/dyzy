<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\Utils;
use AlibabaCloud\Oss\V2\Exception;

final class Functions
{
    const API_MAPS_DEFAULT = [
        //bucket basic
        'PutBucket' => 'BucketBasic',
        'DeleteBucket' => 'BucketBasic',
        'PutBucketAcl' => 'BucketBasic',
        'GetBucketAcl' => 'BucketBasic',
        'GetBucketStat' => 'BucketBasic',
        'GetBucketLocation' => 'BucketBasic',
        'GetBucketInfo' => 'BucketBasic',
        'PutBucketVersioning' => 'BucketBasic',
        'GetBucketVersioning' => 'BucketBasic',
        'ListObjects' => 'BucketBasic',
        'ListObjectsV2' => 'BucketBasic',
        'ListObjectVersions' => 'BucketBasic',
        // service
        'ListBuckets' => 'BucketBasic',
        // region
        'DescribeRegions' => 'BucketBasic',
        //object basic
        'PutObject' => 'ObjectBasic',
        'CopyObject' => 'ObjectBasic',
        'GetObject' => 'ObjectBasic',
        'AppendObject' => 'ObjectBasic',
        'SealAppendObject' => 'ObjectBasic',
        'DeleteObject' => 'ObjectBasic',
        'DeleteMultipleObjects' => 'ObjectBasic',
        'HeadObject' => 'ObjectBasic',
        'GetObjectMeta' => 'ObjectBasic',
        'RestoreObject' => 'ObjectBasic',
        'CleanRestoredObject' => 'ObjectBasic',
        //object acl
        'PutObjectAcl' => 'ObjectBasic',
        'GetObjectAcl' => 'ObjectBasic',
        //object tagging
        'PutObjectTagging' => 'ObjectBasic',
        'GetObjectTagging' => 'ObjectBasic',
        'DeleteObjectTagging' => 'ObjectBasic',
        //object symlink
        'PutSymlink' => 'ObjectBasic',
        'GetSymlink' => 'ObjectBasic',
        //object process
        'ProcessObject' => 'ObjectBasic',
        'AsyncProcessObject' => 'ObjectBasic',
        //object multipart
        'InitiateMultipartUpload' => 'ObjectMultipart',
        'UploadPart' => 'ObjectMultipart',
        'CompleteMultipartUpload' => 'ObjectMultipart',
        'UploadPartCopy' => 'ObjectMultipart',
        'AbortMultipartUpload' => 'ObjectMultipart',
        'ListMultipartUploads' => 'ObjectMultipart',
        'ListParts' => 'ObjectMultipart',
    ];

    const API_MAPS_8_ONLY = [
        // bucket worm
        'InitiateBucketWorm' => 'BucketWorm',
        'AbortBucketWorm' => 'BucketWorm',
        'CompleteBucketWorm' => 'BucketWorm',
        'ExtendBucketWorm' => 'BucketWorm',
        'GetBucketWorm' => 'BucketWorm',
        // bucket lifecycle
        'PutBucketLifecycle' => 'BucketLifecycle',
        'GetBucketLifecycle' => 'BucketLifecycle',
        'DeleteBucketLifecycle' => 'BucketLifecycle',
        // bucket transfer acceleration
        'PutBucketTransferAcceleration' => 'BucketTransferAcceleration',
        'GetBucketTransferAcceleration' => 'BucketTransferAcceleration',
        // bucket meta query
        'GetMetaQueryStatus' => 'BucketMetaQuery',
        'CloseMetaQuery' => 'BucketMetaQuery',
        'DoMetaQuery' => 'BucketMetaQuery',
        'OpenMetaQuery' => 'BucketMetaQuery',
        // bucket access monitor
        'PutBucketAccessMonitor' => 'BucketAccessMonitor',
        'GetBucketAccessMonitor' => 'BucketAccessMonitor',
        // bucket cname
        'PutCname' => 'BucketCname',
        'ListCname' => 'BucketCname',
        'DeleteCname' => 'BucketCname',
        'GetCnameToken' => 'BucketCname',
        'CreateCnameToken' => 'BucketCname',
        // bucket cors
        'PutBucketCors' => 'BucketCors',
        'GetBucketCors' => 'BucketCors',
        'DeleteBucketCors' => 'BucketCors',
        'OptionObject' => 'BucketCors',
        // bucket request payment
        'PutBucketRequestPayment' => 'BucketRequestPayment',
        'GetBucketRequestPayment' => 'BucketRequestPayment',
        // bucket tags
        'PutBucketTags' => 'BucketTags',
        'GetBucketTags' => 'BucketTags',
        'DeleteBucketTags' => 'BucketTags',
        // bucket referer
        'PutBucketReferer' => 'BucketReferer',
        'GetBucketReferer' => 'BucketReferer',
        // bucket website
        'PutBucketWebsite' => 'BucketWebsite',
        'GetBucketWebsite' => 'BucketWebsite',
        'DeleteBucketWebsite' => 'BucketWebsite',
        // bucket transfer acceleration
        'PutBucketLogging' => 'BucketLogging',
        'GetBucketLogging' => 'BucketLogging',
        'DeleteBucketLogging' => 'BucketLogging',
        'PutUserDefinedLogFieldsConfig' => 'BucketLogging',
        'GetUserDefinedLogFieldsConfig' => 'BucketLogging',
        'DeleteUserDefinedLogFieldsConfig' => 'BucketLogging',
        // bucket policy
        'PutBucketPolicy' => 'BucketPolicy',
        'GetBucketPolicy' => 'BucketPolicy',
        'GetBucketPolicyStatus' => 'BucketPolicy',
        'DeleteBucketPolicy' => 'BucketPolicy',
        // bucket encryption
        'PutBucketEncryption' => 'BucketEncryption',
        'GetBucketEncryption' => 'BucketEncryption',
        'DeleteBucketEncryption' => 'BucketEncryption',
        // bucket archive direct read
        'GetBucketArchiveDirectRead' => 'BucketArchiveDirectRead',
        'PutBucketArchiveDirectRead' => 'BucketArchiveDirectRead',
        // public access block
        'GetPublicAccessBlock' => 'PublicAccessBlock',
        'PutPublicAccessBlock' => 'PublicAccessBlock',
        'DeletePublicAccessBlock' => 'PublicAccessBlock',
        // bucket public access block
        'GetBucketPublicAccessBlock' => 'BucketPublicAccessBlock',
        'PutBucketPublicAccessBlock' => 'BucketPublicAccessBlock',
        'DeleteBucketPublicAccessBlock' => 'BucketPublicAccessBlock',
        // access point
        'ListAccessPoints' => 'AccessPoint',
        'GetAccessPoint' => 'AccessPoint',
        'GetAccessPointPolicy' => 'AccessPoint',
        'DeleteAccessPointPolicy' => 'AccessPoint',
        'PutAccessPointPolicy' => 'AccessPoint',
        'DeleteAccessPoint' => 'AccessPoint',
        'CreateAccessPoint' => 'AccessPoint',
        // bucket replication
        'PutBucketRtc' => 'BucketReplication',
        'PutBucketReplication' => 'BucketReplication',
        'GetBucketReplication' => 'BucketReplication',
        'GetBucketReplicationLocation' => 'BucketReplication',
        'GetBucketReplicationProgress' => 'BucketReplication',
        'DeleteBucketReplication' => 'BucketReplication',
        // list cloud boxes
        'ListCloudBoxes' => 'CloudBoxes',
        // bucket redundancy transition
        'ListBucketDataRedundancyTransition' => 'BucketRedundancyTransition',
        'ListUserDataRedundancyTransition' => 'BucketRedundancyTransition',
        'GetBucketDataRedundancyTransition' => 'BucketRedundancyTransition',
        'CreateBucketDataRedundancyTransition' => 'BucketRedundancyTransition',
        'DeleteBucketDataRedundancyTransition' => 'BucketRedundancyTransition',
        // bucket https config
        'GetBucketHttpsConfig' => 'BucketHttpsConfig',
        'PutBucketHttpsConfig' => 'BucketHttpsConfig',
        // bucket resource group
        'GetBucketResourceGroup' => 'BucketResourceGroup',
        'PutBucketResourceGroup' => 'BucketResourceGroup',
        // bucket style
        'PutStyle' => 'BucketStyle',
        'ListStyle' => 'BucketStyle',
        'GetStyle' => 'BucketStyle',
        'DeleteStyle' => 'BucketStyle',
        // bucket inventory
        'PutBucketInventory' => 'BucketInventory',
        'GetBucketInventory' => 'BucketInventory',
        'ListBucketInventory' => 'BucketInventory',
        'DeleteBucketInventory' => 'BucketInventory',
        // bucket overwrite config
        'PutBucketOverwriteConfig' => 'BucketOverwriteConfig',
        'GetBucketOverwriteConfig' => 'BucketOverwriteConfig',
        'DeleteBucketOverwriteConfig' => 'BucketOverwriteConfig',
    ];

    public static function getTransformClass(string $apiName)
    {
        $group = self::API_MAPS_DEFAULT[$apiName] ?? '';
        if ($group == '') {
            $group = self::API_MAPS_8_ONLY[$apiName] ?? '';
            if ($group != '' && version_compare(PHP_VERSION, '8.0.0', '<')) {
                throw new \BadMethodCallException("$apiName is available only on php 8 or higher");
            }
        }
        if ($group != '') {
            $group = str_replace('Functions', $group, self::class);
        }
        return $group;
    }

    public static function serializeInputLite(RequestModel $request, OperationInput $input, array $customSerializer = []): OperationInput
    {
        $ro = new \ReflectionObject($request);

        //headers
        $hp = $ro->getProperty('headers');
        if (PHP_VERSION_ID < 80100){
            $hp->setAccessible(true);
        }
        $h = $hp->getValue($request);
        if (is_array($h)) {
            foreach ($h as $key => $value) {
                $input->setHeader($key, (string)$value);
            }
        }

        //parameters
        $pp = $ro->getProperty('parameters');
        if (PHP_VERSION_ID < 80100){
            $pp->setAccessible(true);
        }
        $p = $pp->getValue($request);
        if (is_array($p)) {
            foreach ($p as $key => $value) {
                $input->setParameter($key, (string)$value);
            }
        }

        //payload
        $pd = $ro->getProperty('payload');
        if (PHP_VERSION_ID < 80100){
            $pd->setAccessible(true);
        }
        $payload = $pd->getValue($request);
        if ($payload instanceof \Psr\Http\Message\StreamInterface) {
            $input->setBody($payload);
        }

        // custom serializer
        foreach ($customSerializer as $serializer) {
            if (\is_callable($serializer)) {
                $serializer($request, $input);
            } else {
                call_user_func($serializer, $request, $input);
            }
        }

        return $input;
    }

    public static function assertFieldRequired(string $filed, $value)
    {
        if (!isset($value)) {
            throw new \InvalidArgumentException("missing required field, $filed.");
        }
    }

    public static function assertXmlRoot(string $xml, string $expect)
    {
        if (!preg_match("/<$expect([^>]*)>/", $xml)) {
            throw new Exception\DeserializationExecption("Not found tag <$expect>");
        }
    }

    public static function assertXmlNodeExist(\SimpleXMLElement $elem, $name)
    {
        if (empty($elem)) {
            throw new Exception\DeserializationExecption("Not found tag <$name>");
        }
    }

    public static function tryToString(\SimpleXMLElement &$elem)
    {
        if ($elem === null || ($elem->count() === 0 && trim((string)$elem) === '')) {
            return null;
        }
        return $elem->__toString();
    }

    public static function tryToDatetime(\SimpleXMLElement &$elem, ?string $format = null)
    {
        if ($elem === null || ($elem->count() === 0 && trim((string)$elem) === '')) {
            return null;
        }

        if ($format == null) {
            $format = 'Y-m-d\TH:i:s\Z';
        }
        $ret = \DateTime::createFromFormat(
            $format,
            $elem->__toString(),
            new \DateTimeZone('UTC')
        );

        if ($ret === false) {
            return null;
        }

        return $ret;
    }

    public static function tryToBool(\SimpleXMLElement &$elem)
    {
        if ($elem === null || ($elem->count() === 0 && trim((string)$elem) === '')) {
            return null;
        }
        return Utils::safetyBool(strtolower($elem->__toString()));
    }

    public static function tryToInt(\SimpleXMLElement &$elem)
    {
        if ($elem === null || ($elem->count() === 0 && trim((string)$elem) === '')) {
            return null;
        }
        return intval($elem->__toString());
    }

    public static function tryUrldecodeString(\SimpleXMLElement &$elem, bool $decode = false)
    {
        if ($elem === null || ($elem->count() === 0 && trim((string)$elem) === '')) {
            return null;
        }
        $val = $elem->__toString();
        return $decode == true ? \rawurldecode($val) : $val;
    }

    public static function tryUserMetadata(?array $headers)
    {
        if (!isset($headers)) {
            return null;
        }
        $result = [];
        foreach ($headers as $k => $v) {
            if (\strncasecmp($k, 'x-oss-meta-', 11) == 0) {
                $result[substr($k, 11)] = $v;
            }
        }
        return empty($result) ? null : $result;
    }

    public static function addContentType(RequestModel $request, OperationInput $input)
    {
        if ($input->hasHeader('Content-Type')) {
            return;
        }
        /*
        $value = Utils::guessContentType($input->getKey());
        if ($value != null) {
            $input->setHeader('Content-Type', $value);
        }
        */
        $input->setOpMetadata('detect_content_type', true);
    }

    public static function addContentMd5(RequestModel $request, OperationInput $input)
    {
        if ($input->hasHeader('Content-MD5')) {
            return;
        }

        $value = '1B2M2Y8AsgTpgAmY7PhCfg==';
        if ($input->getBody() != null) {
            $value = Utils::calcContentMd5($input->getBody());
        }
        $input->setHeader('Content-MD5', $value);
    }
}
