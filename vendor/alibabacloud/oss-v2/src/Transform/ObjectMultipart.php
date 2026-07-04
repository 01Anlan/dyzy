<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Utils;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\ProgressStream;

/**
 * Class ObjectMultipart
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class ObjectMultipart
{
    /**
     * @param Models\InitiateMultipartUploadRequest $request
     * @return OperationInput
     */
    public static function fromInitiateMultipartUpload(Models\InitiateMultipartUploadRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'InitiateMultipartUpload',
            'POST',
            [],
            ['uploads' => '', 'encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\InitiateMultipartUploadRequest $request, OperationInput $input) {
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->storageClass)) {
                    $input->setHeader('x-oss-storage-class', $request->storageClass);
                }
                if (isset($request->metadata)) {
                    foreach ($request->metadata as $k => $v) {
                        $input->setHeader("x-oss-meta-$k", (string)$v);
                    }
                }
                if (isset($request->cacheControl)) {
                    $input->setHeader('Cache-Control', $request->cacheControl);
                }
                if (isset($request->contentDisposition)) {
                    $input->setHeader('Content-Disposition', $request->contentDisposition);
                }
                if (isset($request->contentEncoding)) {
                    $input->setHeader('Content-Encoding', $request->contentEncoding);
                }
                if (isset($request->contentLength)) {
                    $input->setHeader('Content-Length', (string)$request->contentLength);
                }
                if (isset($request->contentMd5)) {
                    $input->setHeader('Content-MD5', $request->contentMd5);
                }
                if (isset($request->contentType)) {
                    $input->setHeader('Content-Type', $request->contentType);
                }
                if (isset($request->expires)) {
                    $input->setHeader('Expires', $request->expires);
                }
                if (isset($request->serverSideEncryption)) {
                    $input->setHeader('x-oss-server-side-encryption', $request->serverSideEncryption);
                }
                if (isset($request->serverSideDataEncryption)) {
                    $input->setHeader('x-oss-server-side-data-encryption', $request->serverSideDataEncryption);
                }
                if (isset($request->serverSideEncryptionKeyId)) {
                    $input->setHeader('x-oss-server-side-encryption-key-id', $request->serverSideEncryptionKeyId);
                }
                if (isset($request->tagging)) {
                    $input->setHeader('x-oss-tagging', $request->tagging);
                }
                if (isset($request->forbidOverwrite)) {
                    $input->setHeader('x-oss-forbid-overwrite', $request->forbidOverwrite == true ? 'true' : 'false');
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        if ($request->disableAutoDetectMimeType !== true) {
            $customSerializer[] = [Functions::class, 'addContentType'];
        }
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\InitiateMultipartUploadResult
     */
    public static function toInitiateMultipartUpload(OperationOutput $output): Models\InitiateMultipartUploadResult
    {
        $result = new Models\InitiateMultipartUploadResult();
        $customDeserializer = [
            static function (Models\InitiateMultipartUploadResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'InitiateMultipartUploadResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->bucket = Functions::tryToString($xml->Bucket);
                $result->key = Functions::tryUrldecodeString($xml->Key, $decode);
                $result->uploadId = Functions::tryToString($xml->UploadId);
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\UploadPartRequest $request
     * @return OperationInput
     */
    public static function fromUploadPart(Models\UploadPartRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('partNumber', $request->partNumber);
        Functions::assertFieldRequired('uploadId', $request->uploadId);
        $input = new OperationInput(
            'UploadPart',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\UploadPartRequest $request, OperationInput $input) {
                if (isset($request->partNumber)) {
                    $input->setParameter('partNumber', strval($request->partNumber));
                }
                if (isset($request->uploadId)) {
                    $input->setParameter('uploadId', $request->uploadId);
                }
                if (isset($request->contentLength)) {
                    $input->setHeader('Content-Length', (string)$request->contentLength);
                }
                if (isset($request->contentMd5)) {
                    $input->setHeader('Content-MD5', $request->contentMd5);
                }
                if (isset($request->trafficLimit)) {
                    $input->setHeader('x-oss-traffic-limit', (string)$request->trafficLimit);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                if (isset($request->body)) {
                    $input->setBody($request->body);
                }
                if (isset($request->progressFn) && $input->getBody() != null) {
                    $input->setBody(new ProgressStream(
                        $input->getBody(),
                        $request->progressFn,
                        $input->getBody()->getSize()
                    ));
                }
                if (isset($request->clientSideEncryptionKey)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-key", $request->clientSideEncryptionKey);
                }
                if (isset($request->clientSideEncryptionStart)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-start", $request->clientSideEncryptionStart);
                }
                if (isset($request->clientSideEncryptionCekAlg)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-cek-alg", $request->clientSideEncryptionCekAlg);
                }
                if (isset($request->clientSideEncryptionWrapAlg)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-wrap-alg", $request->clientSideEncryptionWrapAlg);
                }
                if (isset($request->clientSideEncryptionMatdesc)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-Matdesc", $request->clientSideEncryptionMatdesc);
                }
                if (isset($request->clientSideEncryptionUnencryptedContentMd5)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-unencrypted-content-md5", $request->clientSideEncryptionUnencryptedContentMd5);
                }
                if (isset($request->clientSideEncryptionUnencryptedContentLength)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-unencrypted-content-length", (string)$request->clientSideEncryptionUnencryptedContentLength);
                }
                if (isset($request->clientSideEncryptionDataSize)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-data-size", (string)$request->clientSideEncryptionDataSize);
                }
                if (isset($request->clientSideEncryptionPartSize)) {
                    $input->setHeader("x-oss-meta-client-side-encryption-part-size", (string)$request->clientSideEncryptionPartSize);
                }
            },
            [Functions::class, 'addContentType']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\UploadPartResult
     */
    public static function toUploadPart(OperationOutput $output): Models\UploadPartResult
    {
        $result = new Models\UploadPartResult();
        $customDeserializer = [
            static function (Models\UploadPartResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('Content-MD5')) {
                    $result->contentMd5 = $resp->getHeader('Content-MD5')[0];
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CompleteMultipartUploadRequest $request
     * @return OperationInput
     */
    public static function fromCompleteMultipartUpload(Models\CompleteMultipartUploadRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('uploadId', $request->uploadId);
        $input = new OperationInput(
            'CompleteMultipartUpload',
            'POST',
            ['Content-Type' => 'application/xml'],
            ['encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\CompleteMultipartUploadRequest $request, OperationInput $input) {
                if (isset($request->uploadId)) {
                    $input->setParameter('uploadId', $request->uploadId);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
                }
                if (isset($request->callback)) {
                    $input->setHeader('x-oss-callback', $request->callback);
                }
                if (isset($request->callbackVar)) {
                    $input->setHeader('x-oss-callback-var', $request->callbackVar);
                }
                if (isset($request->forbidOverwrite)) {
                    $input->setHeader('x-oss-forbid-overwrite', $request->forbidOverwrite == true ? 'true' : 'false');
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                if (isset($request->completeMultipartUpload)) {
                    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CompleteMultipartUpload></CompleteMultipartUpload>');
                    if (isset($request->completeMultipartUpload->parts)) {
                        foreach ($request->completeMultipartUpload->parts as $part) {
                            $xmlPart = $xml->addChild('Part');
                            $xmlPart->addChild('PartNumber', strval($part->partNumber));
                            $xmlPart->addChild('ETag', $part->etag);
                        }
                    }
                    $input->setBody(Utils::streamFor($xml->asXML()));
                }
                if (isset($request->completeAll)) {
                    $input->setHeader('x-oss-complete-all', $request->completeAll);
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CompleteMultipartUploadResult
     */
    public static function toCompleteMultipartUpload(OperationOutput $output): Models\CompleteMultipartUploadResult
    {
        $result = new Models\CompleteMultipartUploadResult();
        $customDeserializer = [
            static function (Models\CompleteMultipartUploadResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }

                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                // callbackResult
                if ($output->getOpInput()->hasHeader('x-oss-callback')) {
                    $result->callbackResult = \json_decode($body, true);
                } else {
                    Functions::assertXmlRoot($body, 'CompleteMultipartUploadResult');
                    $xml = Utils::parseXml($body);
                    $result->encodingType = Functions::tryToString($xml->EncodingType);
                    $decode = $result->encodingType === 'url';
                    $result->bucket = Functions::tryToString($xml->Bucket);
                    $result->key = Functions::tryUrldecodeString($xml->Key, $decode);
                    $result->location = Functions::tryToString($xml->Location);
                    $result->etag = Functions::tryToString($xml->ETag);
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\UploadPartCopyRequest $request
     * @return OperationInput
     */
    public static function fromUploadPartCopy(Models\UploadPartCopyRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('sourceKey', $request->sourceKey);
        Functions::assertFieldRequired('partNumber', $request->partNumber);
        Functions::assertFieldRequired('uploadId', $request->uploadId);
        $input = new OperationInput(
            'UploadPartCopy',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\UploadPartCopyRequest $request, OperationInput $input) {
                if (isset($request->partNumber)) {
                    $input->setParameter('partNumber', strval($request->partNumber));
                }
                if (isset($request->uploadId)) {
                    $input->setParameter('uploadId', $request->uploadId);
                }
                $srcBucket = $request->sourceBucket ?? $request->bucket;
                $srcKey = Utils::urlEncode($request->sourceKey, true);
                $copySource = "/$srcBucket/$srcKey";
                if (isset($request->sourceVersionId)) {
                    $copySource .= "?versionId=$request->sourceVersionId";
                }
                $input->setHeader('x-oss-copy-source', $copySource);
                if (isset($request->sourceRange)) {
                    $input->setHeader('x-oss-copy-source-range', $request->sourceRange);
                }
                if (isset($request->ifMatch)) {
                    $input->setHeader('x-oss-copy-source-if-match', $request->ifMatch);
                }
                if (isset($request->ifNoneMatch)) {
                    $input->setHeader('x-oss-copy-source-if-none-match', $request->ifNoneMatch);
                }
                if (isset($request->ifModifiedSince)) {
                    $input->setHeader('x-oss-copy-source-if-modified-since', $request->ifModifiedSince);
                }
                if (isset($request->ifUnmodifiedSince)) {
                    $input->setHeader('x-oss-copy-source-if-unmodified-since', $request->ifUnmodifiedSince);
                }
                if (isset($request->trafficLimit)) {
                    $input->setHeader('x-oss-traffic-limit', (string)$request->trafficLimit);
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
     * @return Models\UploadPartCopyResult
     */
    public static function toUploadPartCopy(OperationOutput $output): Models\UploadPartCopyResult
    {
        $result = new Models\UploadPartCopyResult();
        $customDeserializer = [
            static function (Models\UploadPartCopyResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-copy-source-version-id')) {
                    $result->sourceVersionId = $resp->getHeader('x-oss-copy-source-version-id')[0];
                }

                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'CopyPartResult');
                $xml = Utils::parseXml($body);
                $result->etag = Functions::tryToString($xml->ETag);
                $result->lastModified = Functions::tryToDatetime($xml->LastModified, 'Y-m-d\TH:i:s.000\Z');
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\AbortMultipartUploadRequest $request
     * @return OperationInput
     */
    public static function fromAbortMultipartUpload(Models\AbortMultipartUploadRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('uploadId', $request->uploadId);
        $input = new OperationInput(
            'AbortMultipartUpload',
            'DELETE',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\AbortMultipartUploadRequest $request, OperationInput $input) {
                if (isset($request->uploadId)) {
                    $input->setParameter('uploadId', $request->uploadId);
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
     * @return Models\AbortMultipartUploadResult
     */
    public static function toAbortMultipartUpload(OperationOutput $output): Models\AbortMultipartUploadResult
    {
        $result = new Models\AbortMultipartUploadResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\ListMultipartUploadsRequest $request
     * @return OperationInput
     */
    public static function fromListMultipartUploads(Models\ListMultipartUploadsRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        $input = new OperationInput(
            'ListMultipartUploads',
            'GET',
            ['Content-Type' => 'application/octet-stream'],
            ['uploads' => '', 'encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);
        $customSerializer = [
            static function (Models\ListMultipartUploadsRequest $request, OperationInput $input) {
                if (isset($request->delimiter)) {
                    $input->setParameter('delimiter', $request->delimiter);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->keyMarker)) {
                    $input->setParameter('key-marker', $request->keyMarker);
                }
                if (isset($request->uploadIdMarker)) {
                    $input->setParameter('upload-id-marker', $request->uploadIdMarker);
                }
                if (isset($request->maxUploads)) {
                    $input->setParameter('max-uploads', strval($request->maxUploads));
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
     * @return Models\ListMultipartUploadsResult
     */
    public static function toListMultipartUploads(OperationOutput $output): Models\ListMultipartUploadsResult
    {
        $result = new Models\ListMultipartUploadsResult();
        $customDeserializer = [
            static function (Models\ListMultipartUploadsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListMultipartUploadsResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->bucket = Functions::tryToString($xml->Bucket);
                $result->prefix = Functions::tryUrldecodeString($xml->Prefix, $decode);
                $result->keyMarker = Functions::tryUrldecodeString($xml->KeyMarker, $decode);
                $result->uploadIdMarker = Functions::tryToString($xml->UploadIdMarker);
                $result->maxUploads = Functions::tryToInt($xml->MaxUploads);
                $result->delimiter = Functions::tryUrldecodeString($xml->Delimiter, $decode);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextKeyMarker = Functions::tryUrldecodeString($xml->NextKeyMarker, $decode);
                $result->nextUploadIdMarker = Functions::tryToString($xml->NextUploadIdMarker);
                if (isset($xml->Upload)) {
                    $result->uploads = [];
                    foreach ($xml->Upload as $upload) {
                        $o = new Models\Upload();
                        $o->key = Functions::tryUrldecodeString($upload->Key, $decode);
                        $o->uploadId = Functions::tryToString($upload->UploadId);
                        $o->initiated = Functions::tryToDatetime($upload->Initiated, 'Y-m-d\TH:i:s.000\Z');
                        $result->uploads[] = $o;
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListPartsRequest $request
     * @return OperationInput
     */
    public static function fromListParts(Models\ListPartsRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('uploadId', $request->uploadId);
        $input = new OperationInput(
            'ListParts',
            'GET',
            ['Content-Type' => 'application/octet-stream'],
            ['encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\ListPartsRequest $request, OperationInput $input) {
                if (isset($request->uploadId)) {
                    $input->setParameter('uploadId', $request->uploadId);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }
                if (isset($request->maxParts)) {
                    $input->setParameter('max-parts', strval($request->maxParts));
                }
                if (isset($request->partNumberMarker)) {
                    $input->setParameter('part-number-marker', $request->partNumberMarker);
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
     * @return Models\ListPartsResult
     */
    public static function toListParts(OperationOutput $output): Models\ListPartsResult
    {
        $result = new Models\ListPartsResult();
        $customDeserializer = [
            static function (Models\ListPartsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'ListPartsResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                $result->bucket = Functions::tryToString($xml->Bucket);
                $result->key = Functions::tryUrldecodeString($xml->Key, $decode);
                $result->uploadId = Functions::tryToString($xml->UploadId);
                $result->partNumberMarker = Functions::tryToString($xml->PartNumberMarker);
                $result->maxParts = Functions::tryToInt($xml->MaxParts);
                $result->isTruncated = Functions::tryToBool($xml->IsTruncated);
                $result->nextPartNumberMarker = Functions::tryToString($xml->NextPartNumberMarker);
                $result->storageClass = Functions::tryToString($xml->StorageClass);
                if (isset($xml->Part)) {
                    $result->parts = [];
                    foreach ($xml->Part as $part) {
                        $o = new Models\Part();
                        $o->partNumber = Functions::tryToInt($part->PartNumber);
                        $o->lastModified = Functions::tryToDatetime($part->LastModified, 'Y-m-d\TH:i:s.000\Z');
                        $o->etag = Functions::tryToString($part->ETag);
                        $o->size = Functions::tryToInt($part->Size);
                        $o->hashCrc64 = Functions::tryToString($part->HashCrc64ecma);
                        $result->parts[] = $o;
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
