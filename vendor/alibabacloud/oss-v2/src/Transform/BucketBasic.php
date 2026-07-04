<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Utils;
use AlibabaCloud\Oss\V2\Deserializer;

/**
 * Class BucketBasic
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketBasic
{
    /**
     * @param Models\PutBucketRequest $request
     * @return OperationInput
     */
    public static function fromPutBucket(Models\PutBucketRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'PutBucket',
            'PUT',
            ['Content-Type' => 'application/xml'],
        );

        $input->setBucket($request->bucket);

        $customSerializer = [
            static function (Models\PutBucketRequest $request, OperationInput $input) {
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-acl', $request->acl);
                }

                if (isset($request->resourceGroupId)) {
                    $input->setHeader('x-oss-resource-group-id', $request->resourceGroupId);
                }

                if (isset($request->createBucketConfiguration)) {
                    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CreateBucketConfiguration></CreateBucketConfiguration>');
                    if (isset($request->createBucketConfiguration->storageClass)) {
                        $xml->addChild('StorageClass', $request->createBucketConfiguration->storageClass);
                    }
                    if (isset($request->createBucketConfiguration->dataRedundancyType)) {
                        $xml->addChild('DataRedundancyType', $request->createBucketConfiguration->dataRedundancyType);
                    }
                    $input->setBody(Utils::streamFor($xml->asXML()));
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketResult
     */
    public static function toPutBucket(OperationOutput $output): Models\PutBucketResult
    {
        $result = new Models\PutBucketResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeleteBucketRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucket(Models\DeleteBucketRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'DeleteBucket',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setBucket($request->bucket);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketResult
     */
    public static function toDeleteBucket(OperationOutput $output): Models\DeleteBucketResult
    {
        $result = new Models\DeleteBucketResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\PutBucketAclRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketAcl(Models\PutBucketAclRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('acl', $request->acl);

        $input = new OperationInput(
            'PutBucketAcl',
            'PUT',
            ['Content-Type' => 'application/xml'],
            ['acl' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['acl']);

        $customSerializer = [
            static function (Models\PutBucketAclRequest $request, OperationInput $input) {
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-acl', $request->acl);
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketAclResult
     */
    public static function toPutBucketAcl(OperationOutput $output): Models\PutBucketAclResult
    {
        $result = new Models\PutBucketAclResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketAclRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketAcl(Models\GetBucketAclRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'GetBucketAcl',
            'GET',
            ['Content-Type' => 'application/xml'],
            ['acl' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['acl']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketAclResult
     */
    public static function toGetBucketAcl(OperationOutput $output): Models\GetBucketAclResult
    {
        $result = new Models\GetBucketAclResult();
        $customDeserializer = [
            static function (Models\GetBucketAclResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'AccessControlPolicy');
                $xml = Utils::parseXml($body);
                if (isset($xml->Owner)) {
                    $result->owner = new Models\Owner(
                        Functions::tryToString($xml->Owner->ID),
                        Functions::tryToString($xml->Owner->DisplayName)
                    );
                }
                if (isset($xml->AccessControlList)) {
                    $result->accessControlList = new Models\AccessControlList(
                        Functions::tryToString($xml->AccessControlList->Grant)
                    );
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketStatRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketStat(Models\GetBucketStatRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'GetBucketStat',
            'GET',
            ['Content-Type' => 'application/xml'],
            ['stat' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['stat']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketStatResult
     */
    public static function toGetBucketStat(OperationOutput $output): Models\GetBucketStatResult
    {
        $result = new Models\GetBucketStatResult();
        $customDeserializer = [
            static function (Models\GetBucketStatResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'BucketStat');
                $xml = Utils::parseXml($body);
                $result->storage = Functions::tryToInt($xml->Storage);
                $result->objectCount = Functions::tryToInt($xml->ObjectCount);
                $result->multipartUploadCount = Functions::tryToInt($xml->MultipartUploadCount);
                $result->liveChannelCount = Functions::tryToInt($xml->LiveChannelCount);
                $result->lastModifiedTime = Functions::tryToInt($xml->LastModifiedTime);
                $result->standardStorage = Functions::tryToInt($xml->StandardStorage);
                $result->standardObjectCount = Functions::tryToInt($xml->StandardObjectCount);
                $result->infrequentAccessStorage = Functions::tryToInt($xml->InfrequentAccessStorage);
                $result->infrequentAccessRealStorage = Functions::tryToInt($xml->InfrequentAccessRealStorage);
                $result->infrequentAccessObjectCount = Functions::tryToInt($xml->InfrequentAccessObjectCount);
                $result->archiveStorage = Functions::tryToInt($xml->ArchiveStorage);
                $result->archiveRealStorage = Functions::tryToInt($xml->ArchiveRealStorage);
                $result->archiveObjectCount = Functions::tryToInt($xml->ArchiveObjectCount);
                $result->coldArchiveStorage = Functions::tryToInt($xml->ColdArchiveStorage);
                $result->coldArchiveRealStorage = Functions::tryToInt($xml->ColdArchiveRealStorage);
                $result->coldArchiveObjectCount = Functions::tryToInt($xml->ColdArchiveObjectCount);
                $result->deepColdArchiveStorage = Functions::tryToInt($xml->DeepColdArchiveStorage);
                $result->deepColdArchiveRealStorage = Functions::tryToInt($xml->DeepColdArchiveRealStorage);
                $result->deepColdArchiveObjectCount = Functions::tryToInt($xml->DeepColdArchiveObjectCount);
                $result->deleteMarkerCount = Functions::tryToInt($xml->DeleteMarkerCount);
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketLocationRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketLocation(Models\GetBucketLocationRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'GetBucketLocation',
            'GET',
            ['Content-Type' => 'application/xml'],
            ['location' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['location']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketLocationResult
     */
    public static function toGetBucketLocation(OperationOutput $output): Models\GetBucketLocationResult
    {
        $result = new Models\GetBucketLocationResult();
        $customDeserializer = [
            static function (Models\GetBucketLocationResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'LocationConstraint');
                $xml = Utils::parseXml($body);
                $result->location = Functions::tryToString($xml);
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketInfoRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketInfo(Models\GetBucketInfoRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'GetBucketInfo',
            'GET',
            ['Content-Type' => 'application/xml'],
            ['bucketInfo' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['bucketInfo']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketInfoResult
     */
    public static function toGetBucketInfo(OperationOutput $output): Models\GetBucketInfoResult
    {
        $result = new Models\GetBucketInfoResult();
        $customDeserializer = [
            static function (Models\GetBucketInfoResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'BucketInfo');
                $xml = Utils::parseXml($body);
                Functions::assertXmlNodeExist($xml->Bucket, 'Bucket');
                $info = new Models\BucketInfo();
                $xml = $xml->Bucket;
                $info->name = Functions::tryToString($xml->Name);
                $info->accessMonitor = Functions::tryToString($xml->AccessMonitor);
                $info->location = Functions::tryToString($xml->Location);
                $info->creationDate = Functions::tryToDatetime($xml->CreationDate, 'Y-m-d\TH:i:s.000\Z');
                $info->extranetEndpoint = Functions::tryToString($xml->ExtranetEndpoint);
                $info->intranetEndpoint = Functions::tryToString($xml->IntranetEndpoint);
                $info->acl = isset($xml->AccessControlList) ? Functions::tryToString($xml->AccessControlList->Grant) : null;
                $info->dataRedundancyType = Functions::tryToString($xml->DataRedundancyType);
                $info->owner = isset($xml->Owner) ? new Models\Owner(
                    Functions::tryToString($xml->Owner->ID),
                    Functions::tryToString($xml->Owner->DisplayName)
                ) : null;
                $info->storageClass = Functions::tryToString($xml->StorageClass);
                $info->resourceGroupId = Functions::tryToString($xml->ResourceGroupId);
                $info->sseRule = isset($xml->ServerSideEncryptionRule) ? new Models\ServerSideEncryptionRuleInfo(
                    Functions::tryToString($xml->ServerSideEncryptionRule->KMSMasterKeyID),
                    Functions::tryToString($xml->ServerSideEncryptionRule->SSEAlgorithm),
                    Functions::tryToString($xml->ServerSideEncryptionRule->KMSDataEncryption),
                ) : null;
                $info->versioning = Functions::tryToString($xml->Versioning);
                $info->transferAcceleration = Functions::tryToString($xml->TransferAcceleration);
                $info->crossRegionReplication = Functions::tryToString($xml->CrossRegionReplication);
                $info->bucketPolicy = isset($xml->BucketPolicy) ? new Models\BucketPolicy(
                    Functions::tryToString($xml->BucketPolicy->LogBucket),
                    Functions::tryToString($xml->BucketPolicy->LogPrefix)
                ) : null;
                $info->comment = Functions::tryToString($xml->Comment);
                $info->blockPublicAccess = Functions::tryToBool($xml->BlockPublicAccess);
                $result->bucketInfo = $info;
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutBucketVersioningRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketVersioning(Models\PutBucketVersioningRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'PutBucketVersioning',
            'PUT',
            ['Content-Type' => 'application/xml'],
            ['versioning' => '']
        );

        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['versioning']);

        $customSerializer = [
            static function (Models\PutBucketVersioningRequest $request, OperationInput $input) {
                if (isset($request->versioningConfiguration)) {
                    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><VersioningConfiguration></VersioningConfiguration>');
                    if (isset($request->versioningConfiguration->status)) {
                        $xml->addChild('Status', $request->versioningConfiguration->status);
                    }
                    $input->setBody(Utils::streamFor($xml->asXML()));
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketVersioningResult
     */
    public static function toPutBucketVersioning(OperationOutput $output): Models\PutBucketVersioningResult
    {
        $result = new Models\PutBucketVersioningResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketVersioningRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketVersioning(Models\GetBucketVersioningRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'GetBucketVersioning',
            'GET',
            ['Content-Type' => 'application/xml'],
            ['versioning' => '']
        );
        $input->setBucket($request->bucket);
        $input->setOpMetadata('sub-resource', ['versioning']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketVersioningResult
     */
    public static function toGetBucketVersioning(OperationOutput $output): Models\GetBucketVersioningResult
    {
        $result = new Models\GetBucketVersioningResult();
        $customDeserializer = [
            static function (Models\GetBucketVersioningResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'VersioningConfiguration');
                $xml = Utils::parseXml($body);
                $result->versioningConfiguration = new Models\VersioningConfiguration(
                    Functions::tryToString($xml->Status)
                );
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListObjectsRequest $request
     * @return OperationInput
     */
    public static function fromListObjects(Models\ListObjectsRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'ListObjects',
            'GET',
            ['Content-Type' => 'application/octet-stream'],
            ['encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);

        $customSerializer = [
            static function (Models\ListObjectsRequest $request, OperationInput $input) {
                if (isset($request->delimiter)) {
                    $input->setParameter('delimiter', $request->delimiter);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->marker)) {
                    $input->setParameter('marker', $request->marker);
                }
                if (isset($request->maxKeys)) {
                    $input->setParameter('max-keys', strval($request->maxKeys));
                }
                if (isset($request->prefix)) {
                    $input->setParameter('prefix', $request->prefix);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListObjectsResult
     */
    public static function toListObjects(OperationOutput $output): Models\ListObjectsResult
    {
        $result = new Models\ListObjectsResult();
        $customDeserializer = [
            static function (Models\ListObjectsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListBucketResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->name = Functions::tryToString($xml->Name);
                $result->prefix = Functions::tryUrldecodeString($xml->Prefix, $decode);
                $result->marker = Functions::tryUrldecodeString($xml->Marker, $decode);
                $result->maxKeys = Functions::tryToInt($xml->MaxKeys);
                $result->delimiter = Functions::tryUrldecodeString($xml->Delimiter, $decode);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextMarker = Functions::tryUrldecodeString($xml->NextMarker, $decode);
                if (isset($xml->Contents)) {
                    $result->contents = [];
                    foreach ($xml->Contents as $content) {
                        $o = new Models\ObjectProperties();
                        $o->key = Functions::tryUrldecodeString($content->Key, $decode);
                        $o->type = Functions::tryToString($content->Type);
                        $o->size = Functions::tryToInt($content->Size);
                        $o->etag = Functions::tryToString($content->ETag);
                        $o->lastModified = Functions::tryToDatetime($content->LastModified, 'Y-m-d\TH:i:s.000\Z');
                        $o->storageClass = Functions::tryToString($content->StorageClass);
                        if (isset($content->Owner)) {
                            $o->owner = new Models\Owner(
                                Functions::tryToString($content->Owner->ID),
                                Functions::tryToString($content->Owner->DisplayName)
                            );
                        }
                        $o->restoreInfo = Functions::tryToString($content->RestoreInfo);
                        $o->transitionTime = Functions::tryToDatetime($content->TransitionTime, 'Y-m-d\TH:i:s.000\Z');
                        $result->contents[] = $o;
                    }
                }
                if (isset($xml->CommonPrefixes)) {
                    $result->commonPrefixes = [];
                    foreach ($xml->CommonPrefixes as $commonPrefix) {
                        $result->commonPrefixes[] = new Models\CommonPrefix(
                            Functions::tryUrldecodeString($commonPrefix->Prefix, $decode)
                        );
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListObjectsV2Request $request
     * @return OperationInput
     */
    public static function fromListObjectsV2(Models\ListObjectsV2Request $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'ListObjectsV2',
            'GET',
            ['Content-Type' => 'application/octet-stream'],
            ['encoding-type' => 'url', 'list-type' => '2']
        );
        $input->setBucket($request->bucket);

        $customSerializer = [
            static function (Models\ListObjectsV2Request $request, OperationInput $input) {
                if (isset($request->delimiter)) {
                    $input->setParameter('delimiter', $request->delimiter);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->startAfter)) {
                    $input->setParameter('start-after', $request->startAfter);
                }
                if (isset($request->continuationToken)) {
                    $input->setParameter('continuation-token', $request->continuationToken);
                }
                if (isset($request->maxKeys)) {
                    $input->setParameter('max-keys', strval($request->maxKeys));
                }
                if (isset($request->prefix)) {
                    $input->setParameter('prefix', $request->prefix);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                if (isset($request->fetchOwner)) {
                    $input->setParameter('fetch-owner', $request->fetchOwner === true ? 'true' : 'false');
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListObjectsV2Result
     */
    public static function toListObjectsV2(OperationOutput $output): Models\ListObjectsV2Result
    {
        $result = new Models\ListObjectsV2Result();
        $customDeserializer = [
            static function (Models\ListObjectsV2Result $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListBucketResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->name = Functions::tryToString($xml->Name);
                $result->prefix = Functions::tryUrldecodeString($xml->Prefix, $decode);
                $result->startAfter = Functions::tryUrldecodeString($xml->StartAfter, $decode);
                $result->continuationToken = Functions::tryUrldecodeString($xml->ContinuationToken, $decode);
                $result->maxKeys = Functions::tryToInt($xml->MaxKeys);
                $result->delimiter = Functions::tryUrldecodeString($xml->Delimiter, $decode);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextContinuationToken = Functions::tryUrldecodeString($xml->NextContinuationToken, $decode);
                $result->keyCount = Functions::tryToInt($xml->KeyCount);
                if (isset($xml->Contents)) {
                    $result->contents = [];
                    foreach ($xml->Contents as $content) {
                        $o = new Models\ObjectProperties();
                        $o->key = Functions::tryUrldecodeString($content->Key, $decode);
                        $o->type = Functions::tryToString($content->Type);
                        $o->size = Functions::tryToInt($content->Size);
                        $o->etag = Functions::tryToString($content->ETag);
                        $o->lastModified = Functions::tryToDatetime($content->LastModified, 'Y-m-d\TH:i:s.000\Z');
                        $o->storageClass = Functions::tryToString($content->StorageClass);
                        if (isset($content->Owner)) {
                            $o->owner = new Models\Owner(
                                Functions::tryToString($content->Owner->ID),
                                Functions::tryToString($content->Owner->DisplayName)
                            );
                        }
                        $o->restoreInfo = Functions::tryToString($content->RestoreInfo);
                        $o->transitionTime = Functions::tryToDatetime($content->TransitionTime, 'Y-m-d\TH:i:s.000\Z');
                        $result->contents[] = $o;
                    }
                }
                if (isset($xml->CommonPrefixes)) {
                    $result->commonPrefixes = [];
                    foreach ($xml->CommonPrefixes as $commonPrefix) {
                        $result->commonPrefixes[] = new Models\CommonPrefix(
                            Functions::tryUrldecodeString($commonPrefix->Prefix, $decode)
                        );
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListObjectVersionsRequest $request
     * @return OperationInput
     */
    public static function fromListObjectVersions(Models\ListObjectVersionsRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);

        $input = new OperationInput(
            'ListObjectVersions',
            'GET',
            ['Content-Type' => 'application/octet-stream'],
            ['encoding-type' => 'url', 'versions' => '']
        );
        $input->setBucket($request->bucket);

        $customSerializer = [
            static function (Models\ListObjectVersionsRequest $request, OperationInput $input) {
                if (isset($request->delimiter)) {
                    $input->setParameter('delimiter', $request->delimiter);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->keyMarker)) {
                    $input->setParameter('key-marker', $request->keyMarker);
                }
                if (isset($request->versionIdMarker)) {
                    $input->setParameter('version-id-marker', $request->versionIdMarker);
                }
                if (isset($request->maxKeys)) {
                    $input->setParameter('max-keys', strval($request->maxKeys));
                }
                if (isset($request->prefix)) {
                    $input->setParameter('prefix', $request->prefix);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
            },
            [Functions::class, 'addContentMd5']
        ];

        Functions::serializeInputLite($request, $input, $customSerializer);

        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListObjectVersionsResult
     */
    public static function toListObjectVersions(OperationOutput $output): Models\ListObjectVersionsResult
    {
        $result = new Models\ListObjectVersionsResult();
        $customDeserializer = [
            static function (Models\ListObjectVersionsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListVersionsResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->name = Functions::tryToString($xml->Name);
                $result->prefix = Functions::tryUrldecodeString($xml->Prefix, $decode);
                $result->keyMarker = Functions::tryUrldecodeString($xml->KeyMarker, $decode);
                $result->versionIdMarker = Functions::tryToString($xml->VersionIdMarker);
                $result->maxKeys = Functions::tryToInt($xml->MaxKeys);
                $result->delimiter = Functions::tryUrldecodeString($xml->Delimiter, $decode);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextKeyMarker = Functions::tryUrldecodeString($xml->NextKeyMarker, $decode);
                $result->nextVersionIdMarker = Functions::tryToString($xml->NextVersionIdMarker);
                if (isset($xml->Version)) {
                    $result->versions = [];
                    foreach ($xml->Version as $version) {
                        $o = new Models\ObjectVersionProperties();
                        $o->key = Functions::tryUrldecodeString($version->Key, $decode);
                        $o->versionId = Functions::tryToString($version->VersionId);
                        $o->isLatest = Functions::tryToBool($version->IsLatest);
                        $o->type = Functions::tryToString($version->Type);
                        $o->size = Functions::tryToInt($version->Size);
                        $o->etag = Functions::tryToString($version->ETag);
                        $o->lastModified = Functions::tryToDatetime($version->LastModified, 'Y-m-d\TH:i:s.000\Z');
                        $o->storageClass = Functions::tryToString($version->StorageClass);
                        if (isset($version->Owner)) {
                            $o->owner = new Models\Owner(
                                Functions::tryToString($version->Owner->ID),
                                Functions::tryToString($version->Owner->DisplayName)
                            );
                        }
                        $o->restoreInfo = Functions::tryToString($version->RestoreInfo);
                        $o->transitionTime = Functions::tryToDatetime($version->TransitionTime, 'Y-m-d\TH:i:s.000\Z');
                        $result->versions[] = $o;
                    }
                }
                if (isset($xml->DeleteMarker)) {
                    $result->deleteMarkers = [];
                    foreach ($xml->DeleteMarker as $deleteMarker) {
                        $o = new Models\DeleteMarkerProperties();
                        $o->key = Functions::tryUrldecodeString($deleteMarker->Key, $decode);
                        $o->versionId = Functions::tryToString($deleteMarker->VersionId);
                        $o->isLatest = Functions::tryToBool($deleteMarker->IsLatest);
                        $o->lastModified = Functions::tryToDatetime($deleteMarker->LastModified, 'Y-m-d\TH:i:s.000\Z');
                        if (isset($deleteMarker->Owner)) {
                            $o->owner = new Models\Owner(
                                Functions::tryToString($deleteMarker->Owner->ID),
                                Functions::tryToString($deleteMarker->Owner->DisplayName)
                            );
                        }
                        $result->deleteMarkers[] = $o;
                    }
                }
                if (isset($xml->CommonPrefixes)) {
                    $result->commonPrefixes = [];
                    foreach ($xml->CommonPrefixes as $commonPrefix) {
                        $result->commonPrefixes[] = new Models\CommonPrefix(
                            Functions::tryUrldecodeString($commonPrefix->Prefix, $decode)
                        );
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListBucketsRequest $request
     * @return OperationInput
     */
    public static function fromListBuckets(Models\ListBucketsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListBuckets',
            'GET',
            ['Content-Type' => 'application/xml'],
        );
        $customSerializer = [
            static function (Models\ListBucketsRequest $request, OperationInput $input) {
                if (isset($request->marker)) {
                    $input->setParameter('marker', $request->marker);
                }
                if (isset($request->maxKeys)) {
                    $input->setParameter('max-keys', strval($request->maxKeys));
                }
                if (isset($request->prefix)) {
                    $input->setParameter('prefix', $request->prefix);
                }
                if (isset($request->resourceGroupId)) {
                    $input->setHeader('x-oss-resource-group-id', $request->resourceGroupId);
                }
                if (isset($request->tagKey)) {
                    $input->setParameter('tag-key', $request->tagKey);
                }
                if (isset($request->tagValue)) {
                    $input->setParameter('tag-value', $request->tagValue);
                }
                if (isset($request->tagging)) {
                    $input->setParameter('tagging', $request->tagging);
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListBucketsResult
     */
    public static function toListBuckets(OperationOutput $output): Models\ListBucketsResult
    {
        $result = new Models\ListBucketsResult();
        $customDeserializer = [
            static function (Models\ListBucketsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListAllMyBucketsResult');
                $xml = Utils::parseXml($body);
                $result->prefix = Functions::tryToString($xml->Prefix);
                $result->marker = Functions::tryToString($xml->Marker);
                $result->maxKeys = Functions::tryToInt($xml->MaxKeys);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextMarker = Functions::tryToString($xml->NextMarker);
                if (isset($xml->Owner)) {
                    $result->owner = new Models\Owner(
                        Functions::tryToString($xml->Owner->ID),
                        Functions::tryToString($xml->Owner->DisplayName)
                    );
                }
                if (isset($xml->Buckets->Bucket)) {
                    foreach ($xml->Buckets->Bucket as $bucket) {
                        $o = new Models\Bucket();
                        $o->creationDate = Functions::tryToDatetime($bucket->CreationDate, 'Y-m-d\TH:i:s.000\Z');
                        $o->extranetEndpoint = Functions::tryToString($bucket->ExtranetEndpoint);
                        $o->intranetEndpoint = Functions::tryToString($bucket->IntranetEndpoint);
                        $o->location = Functions::tryToString($bucket->Location);
                        $o->name = Functions::tryToString($bucket->Name);
                        $o->region = Functions::tryToString($bucket->Region);
                        $o->storageClass = Functions::tryToString($bucket->StorageClass);
                        $o->resourceGroupId = Functions::tryToString($bucket->ResourceGroupId);
                        $result->buckets[] = $o;
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DescribeRegionsRequest $request
     * @return OperationInput
     */
    public static function fromDescribeRegions(Models\DescribeRegionsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DescribeRegions',
            'GET',
            ['Content-Type' => 'application/xml'],
        );
        $customSerializer = [
            static function (Models\DescribeRegionsRequest $request, OperationInput $input) {
                if (isset($request->regions)) {
                    $input->setParameter('regions', $request->regions);
                } else {
                    $input->setParameter('regions', '');
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DescribeRegionsResult
     */
    public static function toDescribeRegions(OperationOutput $output): Models\DescribeRegionsResult
    {
        $result = new Models\DescribeRegionsResult();
        $customDeserializer = [
            static function (Models\DescribeRegionsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'RegionInfoList');
                $xml = Utils::parseXml($body);
                if (isset($xml->RegionInfo)) {
                    foreach ($xml->RegionInfo as $regionInfo) {
                        $o = new Models\RegionInfo();
                        $o->region = Functions::tryToString($regionInfo->Region);
                        $o->internalEndpoint = Functions::tryToString($regionInfo->InternalEndpoint);
                        $o->internetEndpoint = Functions::tryToString($regionInfo->InternetEndpoint);
                        $o->accelerateEndpoint = Functions::tryToString($regionInfo->AccelerateEndpoint);
                        $result->regionInfos[] = $o;
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
