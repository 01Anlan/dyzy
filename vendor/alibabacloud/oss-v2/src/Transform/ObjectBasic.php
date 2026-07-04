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
 * Object basic api
 * Class ObjectBasic
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class ObjectBasic
{
    /**
     * @param Models\PutObjectRequest $request
     * @return OperationInput
     */
    public static function fromPutObject(Models\PutObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'PutObject',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\PutObjectRequest $request, OperationInput $input) {
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
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
                if (isset($request->callback)) {
                    $input->setHeader('x-oss-callback', $request->callback);
                }
                if (isset($request->callbackVar)) {
                    $input->setHeader('x-oss-callback-var', $request->callbackVar);
                }
                if (isset($request->forbidOverwrite)) {
                    $input->setHeader('x-oss-forbid-overwrite', $request->forbidOverwrite == true ? 'true' : 'false');
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
            },
            [Functions::class, 'addContentType']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutObjectResult
     */
    public static function toPutObject(OperationOutput $output): Models\PutObjectResult
    {
        $result = new Models\PutObjectResult();
        $customDeserializer = [
            static function (Models\PutObjectResult $result, OperationOutput $output) {
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
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                // callbackResult
                if ($output->getOpInput()->hasHeader('x-oss-callback')) {
                    $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                    $result->callbackResult = \json_decode($body, true);
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CopyObjectRequest $request
     * @return OperationInput
     */
    public static function fromCopyObject(Models\CopyObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('sourceKey', $request->sourceKey);
        $input = new OperationInput(
            'CopyObject',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\CopyObjectRequest $request, OperationInput $input) {
                $srcBucket = $request->sourceBucket ?? $request->bucket;
                $srcKey = Utils::urlEncode($request->sourceKey, true);
                $copySource = "/$srcBucket/$srcKey";
                if (isset($request->sourceVersionId)) {
                    $copySource .= "?versionId=$request->sourceVersionId";
                }
                $input->setHeader('x-oss-copy-source', $copySource);

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
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
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
                if (isset($request->metadataDirective)) {
                    $input->setHeader('x-oss-metadata-directive', $request->metadataDirective);
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
                if (isset($request->taggingDirective)) {
                    $input->setHeader('x-oss-tagging-directive', $request->taggingDirective);
                }
                if (isset($request->forbidOverwrite)) {
                    $input->setHeader('x-oss-forbid-overwrite', $request->forbidOverwrite == true ? 'true' : 'false');
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
     * @return Models\CopyObjectResult
     */
    public static function toCopyObject(OperationOutput $output): Models\CopyObjectResult
    {
        $result = new Models\CopyObjectResult();
        $customDeserializer = [
            static function (Models\CopyObjectResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-copy-source-version-id')) {
                    $result->sourceVersionId = $resp->getHeader('x-oss-copy-source-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption')) {
                    $result->serverSideEncryption = $resp->getHeader('x-oss-server-side-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-data-encryption')) {
                    $result->serverSideDataEncryption = $resp->getHeader('x-oss-server-side-data-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption-key-id')) {
                    $result->serverSideEncryptionKeyId = $resp->getHeader('x-oss-server-side-encryption-key-id')[0];
                }
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'CopyObjectResult');
                $xml = Utils::parseXml($body);
                $result->etag = Functions::tryToString($xml->ETag);
                $result->lastModified = Functions::tryToDatetime($xml->LastModified, 'Y-m-d\TH:i:s.000\Z');
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetObjectRequest $request
     * @return OperationInput
     */
    public static function fromGetObject(Models\GetObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'GetObject',
            'GET',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\GetObjectRequest $request, OperationInput $input) {
                if (isset($request->ifMatch)) {
                    $input->setHeader('If-Match', $request->ifMatch);
                }
                if (isset($request->ifNoneMatch)) {
                    $input->setHeader('If-None-Match', $request->ifNoneMatch);
                }
                if (isset($request->ifModifiedSince)) {
                    $input->setHeader('If-Modified-Since', $request->ifModifiedSince);
                }
                if (isset($request->ifUnmodifiedSince)) {
                    $input->setHeader('If-Unmodified-Since', $request->ifUnmodifiedSince);
                }
                if (isset($request->rangeHeader)) {
                    $input->setHeader('Range', $request->rangeHeader);
                }
                if (isset($request->rangeBehavior)) {
                    $input->setHeader('x-oss-range-behavior', $request->rangeBehavior);
                }

                if (isset($request->responseCacheControl)) {
                    $input->setParameter('response-cache-control', $request->responseCacheControl);
                }
                if (isset($request->responseContentDisposition)) {
                    $input->setParameter('response-content-disposition', $request->responseContentDisposition);
                }
                if (isset($request->responseContentEncoding)) {
                    $input->setParameter('response-content-encoding', $request->responseContentEncoding);
                }
                if (isset($request->responseContentLanguage)) {
                    $input->setParameter('response-content-language', $request->responseContentLanguage);
                }
                if (isset($request->responseContentType)) {
                    $input->setParameter('response-content-type', $request->responseContentType);
                }
                if (isset($request->responseExpires)) {
                    $input->setParameter('response-expires', $request->responseExpires);
                }
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
                }
                if (isset($request->process)) {
                    $input->setParameter('x-oss-process', $request->process);
                }
                if (isset($request->trafficLimit)) {
                    $input->setHeader('x-oss-traffic-limit', (string)$request->trafficLimit);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                // TDDO progress_fn
                if (isset($request->progressFn)) {
                }
            }
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetObjectResult
     */
    public static function toGetObject(OperationOutput $output): Models\GetObjectResult
    {
        $result = new Models\GetObjectResult();
        $customDeserializer = [
            static function (Models\GetObjectResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('Content-Length')) {
                    $result->contentLength = intval($resp->getHeader('Content-Length')[0]);
                }
                if ($resp->hasHeader('Content-Range')) {
                    $result->contentRange = $resp->getHeader('Content-Range')[0];
                }
                if ($resp->hasHeader('Content-Type')) {
                    $result->contentType = $resp->getHeader('Content-Type')[0];
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                if ($resp->hasHeader('Last-Modified')) {
                    $result->lastModified = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('Last-Modified')[0],
                        new \DateTimeZone('UTC')
                    );
                }
                if ($resp->hasHeader('Content-MD5')) {
                    $result->contentMd5 = $resp->getHeader('Content-MD5')[0];
                }
                if ($resp->hasHeader('Cache-Control')) {
                    $result->cacheControl = $resp->getHeader('Cache-Control')[0];
                }
                if ($resp->hasHeader('Content-Disposition')) {
                    $result->contentDisposition = $resp->getHeader('Content-Disposition')[0];
                }
                if ($resp->hasHeader('Content-Encoding')) {
                    $result->contentEncoding = $resp->getHeader('Content-Encoding')[0];
                }
                if ($resp->hasHeader('Expires')) {
                    $result->expires = $resp->getHeader('Expires')[0];
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-storage-class')) {
                    $result->storageClass = $resp->getHeader('x-oss-storage-class')[0];
                }
                if ($resp->hasHeader('x-oss-object-type')) {
                    $result->objectType = $resp->getHeader('x-oss-object-type')[0];
                }
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-tagging-count')) {
                    $result->taggingCount = intval($resp->getHeader('x-oss-tagging-count')[0]);
                }
                if ($resp->hasHeader('x-oss-next-append-position')) {
                    $result->nextAppendPosition = intval($resp->getHeader('x-oss-next-append-position')[0]);
                }
                if ($resp->hasHeader('x-oss-expiration')) {
                    $result->expiration = $resp->getHeader('x-oss-expiration')[0];
                }
                if ($resp->hasHeader('x-oss-restore')) {
                    $result->restore = $resp->getHeader('x-oss-restore')[0];
                }
                if ($resp->hasHeader('x-oss-process-status')) {
                    $result->processStatus = $resp->getHeader('x-oss-process-status')[0];
                }
                if ($resp->hasHeader('x-oss-delete-marker')) {
                    $result->deleteMarker = $resp->getHeader('x-oss-delete-marker')[0] === 'true';
                }
                if ($resp->hasHeader('x-oss-server-side-encryption')) {
                    $result->serverSideEncryption = $resp->getHeader('x-oss-server-side-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-data-encryption')) {
                    $result->serverSideDataEncryption = $resp->getHeader('x-oss-server-side-data-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption-key-id')) {
                    $result->serverSideEncryptionKeyId = $resp->getHeader('x-oss-server-side-encryption-key-id')[0];
                }
                // usermetadata
                $result->metadata = Functions::tryUserMetadata($output->getHeaders());

                // body
                $result->body = $output->getBody();
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\AppendObjectRequest $request
     * @return OperationInput
     */
    public static function fromAppendObject(Models\AppendObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('position', $request->position);
        $input = new OperationInput(
            'AppendObject',
            'POST',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('append', '');
        $input->setOpMetadata('sub-resource', ["append",]);
        $customSerializer = [
            static function (Models\AppendObjectRequest $request, OperationInput $input) {
                if (isset($request->position)) {
                    $input->setParameter('position', (string)$request->position);
                }
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
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
            },
            [Functions::class, 'addContentType']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\AppendObjectResult
     */
    public static function toAppendObject(OperationOutput $output): Models\AppendObjectResult
    {
        $result = new Models\AppendObjectResult();
        $customDeserializer = [
            static function (Models\AppendObjectResult $result, OperationOutput $output) {
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-next-append-position')) {
                    $result->nextPosition = intval($resp->getHeader('x-oss-next-append-position')[0]);
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption')) {
                    $result->serverSideEncryption = $resp->getHeader('x-oss-server-side-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-data-encryption')) {
                    $result->serverSideDataEncryption = $resp->getHeader('x-oss-server-side-data-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption-key-id')) {
                    $result->serverSideEncryptionKeyId = $resp->getHeader('x-oss-server-side-encryption-key-id')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\SealAppendObjectRequest $request
     * @return OperationInput
     */
    public static function fromSealAppendObject(Models\SealAppendObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('position', $request->position);
        $input = new OperationInput(
            'SealAppendObject',
            'POST',
            ['Content-Type' => 'application/xml'],
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('seal', '');
        $input->setOpMetadata('sub-resource', ["seal",]);
        $customSerializer = [
            static function (Models\SealAppendObjectRequest $request, OperationInput $input) {
                if (isset($request->position)) {
                    $input->setParameter('position', (string)$request->position);
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\SealAppendObjectResult
     */
    public static function toSealAppendObject(OperationOutput $output): Models\SealAppendObjectResult
    {
        $result = new Models\SealAppendObjectResult();
        $customDeserializer = [
            static function (Models\SealAppendObjectResult $result, OperationOutput $output) {
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-sealed-time')) {
                    $result->sealedTime = $resp->getHeader('x-oss-sealed-time')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteObjectRequest $request
     * @return OperationInput
     */
    public static function fromDeleteObject(Models\DeleteObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'DeleteObject',
            'DELETE',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\DeleteObjectRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\DeleteObjectResult
     */
    public static function toDeleteObject(OperationOutput $output): Models\DeleteObjectResult
    {
        $result = new Models\DeleteObjectResult();
        $customDeserializer = [
            static function (Models\DeleteObjectResult $result, OperationOutput $output) {
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-delete-marker')) {
                    $result->deleteMarker = $resp->getHeader('x-oss-delete-marker')[0] == 'true';
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteMultipleObjectsRequest $request
     * @return OperationInput
     */
    public static function fromDeleteMultipleObjects(Models\DeleteMultipleObjectsRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        if (isset($request->objects) && isset($request->delete)) {
            throw new \InvalidArgumentException('The objects and delete parameters cannot be set simultaneously');
        }
        $input = new OperationInput(
            'DeleteMultipleObjects',
            'POST',
            ['Content-Type' => 'application/xml'],
            ['delete' => '', 'encoding-type' => 'url']
        );
        $input->setBucket($request->bucket);
        $customSerializer = [
            static function (Models\DeleteMultipleObjectsRequest $request, OperationInput $input) {
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                if (isset($request->encodingType)) {
                    $input->setParameter('encoding-type', $request->encodingType);
                }

                if (isset($request->delete)) {
                    /**
                     * @var Models\Delete
                     */
                    $delete = $request->delete;
                    Functions::assertFieldRequired('delete.objects', $delete->objects);
                    $xmlStr = '<?xml version="1.0" encoding="UTF-8"?>';
                    $xmlStr .= "\n<Delete>\n";
                    $val = 'false';
                    if (isset($delete->quiet)) {
                        $val = $delete->quiet === true ? 'true' : 'false';
                    }
                    $xmlStr .= "<Quiet>$val</Quiet>\n";
                    foreach ($delete->objects as $obj) {
                        $xmlStr .= "<Object>\n";
                        if (isset($obj->key)) {
                            $key = Utils::escapeXml($obj->key);
                            $xmlStr .= "<Key>$key</Key>\n";
                        }
                        if (isset($obj->versionId)) {
                            $xmlStr .= "<VersionId>$obj->versionId</VersionId>\n";
                        }
                        $xmlStr .= "</Object>\n";
                    }
                    $xmlStr .= '</Delete>';
                    $input->setBody(Utils::streamFor($xmlStr));

                } else {
                    Functions::assertFieldRequired('objects', $request->objects);
                    $xmlStr = '<?xml version="1.0" encoding="UTF-8"?>';
                    $xmlStr .= "\n<Delete>\n";
                    if (isset($request->quiet)) {
                        $val = $request->quiet === true ? 'true' : 'false';
                        $xmlStr .= "<Quiet>$val</Quiet>\n";
                    }
                    foreach ($request->objects as $obj) {
                        $xmlStr .= "<Object>\n";
                        if (isset($obj->key)) {
                            $key = Utils::escapeXml($obj->key);
                            $xmlStr .= "<Key>$key</Key>\n";
                        }
                        if (isset($obj->versionId)) {
                            $xmlStr .= "<VersionId>$obj->versionId</VersionId>\n";
                        }
                        $xmlStr .= "</Object>\n";
                    }
                    $xmlStr .= '</Delete>';
                    $input->setBody(Utils::streamFor($xmlStr));
                }
            },
            [Functions::class, 'addContentMd5']
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteMultipleObjectsResult
     */
    public static function toDeleteMultipleObjects(OperationOutput $output): Models\DeleteMultipleObjectsResult
    {
        $result = new Models\DeleteMultipleObjectsResult();
        $customDeserializer = [
            static function (Models\DeleteMultipleObjectsResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'DeleteResult');
                $xml = Utils::parseXml($body);
                $result->encodingType = Functions::tryToString($xml->EncodingType);
                $decode = $result->encodingType === 'url';
                if (isset($xml->Deleted)) {
                    $result->deletedObjects = [];
                    foreach ($xml->Deleted as $deleted) {
                        $o = new Models\DeletedInfo();
                        $o->key = Functions::tryUrldecodeString($deleted->Key, $decode);
                        $o->versionId = Functions::tryToString($deleted->VersionId);
                        $o->deleteMarker = Functions::tryToBool($deleted->DeleteMarker);
                        $o->deleteMarkerVersionId = Functions::tryToString($deleted->DeleteMarkerVersionId);
                        $result->deletedObjects[] = $o;
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\HeadObjectRequest $request
     * @return OperationInput
     */
    public static function fromHeadObject(Models\HeadObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'HeadObject',
            'HEAD',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $customSerializer = [
            static function (Models\HeadObjectRequest $request, OperationInput $input) {
                if (isset($request->ifMatch)) {
                    $input->setHeader('If-Match', $request->ifMatch);
                }
                if (isset($request->ifNoneMatch)) {
                    $input->setHeader('If-None-Match', $request->ifNoneMatch);
                }
                if (isset($request->ifModifiedSince)) {
                    $input->setHeader('If-Modified-Since', $request->ifModifiedSince);
                }
                if (isset($request->ifUnmodifiedSince)) {
                    $input->setHeader('If-Unmodified-Since', $request->ifUnmodifiedSince);
                }
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
            }
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\HeadObjectResult
     */
    public static function toHeadObject(OperationOutput $output): Models\HeadObjectResult
    {
        $result = new Models\HeadObjectResult();
        $customDeserializer = [
            static function (Models\HeadObjectResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('Content-Length')) {
                    $result->contentLength = intval($resp->getHeader('Content-Length')[0]);
                }
                if ($resp->hasHeader('Content-Type')) {
                    $result->contentType = $resp->getHeader('Content-Type')[0];
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                if ($resp->hasHeader('Last-Modified')) {
                    $result->lastModified = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('Last-Modified')[0],
                        new \DateTimeZone('UTC')
                    );
                }
                if ($resp->hasHeader('Content-MD5')) {
                    $result->contentMd5 = $resp->getHeader('Content-MD5')[0];
                }
                if ($resp->hasHeader('Cache-Control')) {
                    $result->cacheControl = $resp->getHeader('Cache-Control')[0];
                }
                if ($resp->hasHeader('Content-Disposition')) {
                    $result->contentDisposition = $resp->getHeader('Content-Disposition')[0];
                }
                if ($resp->hasHeader('Content-Encoding')) {
                    $result->contentEncoding = $resp->getHeader('Content-Encoding')[0];
                }
                if ($resp->hasHeader('Expires')) {
                    $result->expires = $resp->getHeader('Expires')[0];
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-storage-class')) {
                    $result->storageClass = $resp->getHeader('x-oss-storage-class')[0];
                }
                if ($resp->hasHeader('x-oss-object-type')) {
                    $result->objectType = $resp->getHeader('x-oss-object-type')[0];
                }
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-tagging-count')) {
                    $result->taggingCount = intval($resp->getHeader('x-oss-tagging-count')[0]);
                }
                if ($resp->hasHeader('x-oss-next-append-position')) {
                    $result->nextAppendPosition = intval($resp->getHeader('x-oss-next-append-position')[0]);
                }
                if ($resp->hasHeader('x-oss-expiration')) {
                    $result->expiration = $resp->getHeader('x-oss-expiration')[0];
                }
                if ($resp->hasHeader('x-oss-restore')) {
                    $result->restore = $resp->getHeader('x-oss-restore')[0];
                }
                if ($resp->hasHeader('x-oss-process-status')) {
                    $result->processStatus = $resp->getHeader('x-oss-process-status')[0];
                }
                if ($resp->hasHeader('x-oss-request-charged')) {
                    $result->requestCharged = $resp->getHeader('x-oss-request-charged')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption')) {
                    $result->serverSideEncryption = $resp->getHeader('x-oss-server-side-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-data-encryption')) {
                    $result->serverSideDataEncryption = $resp->getHeader('x-oss-server-side-data-encryption')[0];
                }
                if ($resp->hasHeader('x-oss-server-side-encryption-key-id')) {
                    $result->serverSideEncryptionKeyId = $resp->getHeader('x-oss-server-side-encryption-key-id')[0];
                }
                if ($resp->hasHeader('Access-Control-Allow-Origin')) {
                    $result->allowOrigin = $resp->getHeader('Access-Control-Allow-Origin')[0];
                }
                if ($resp->hasHeader('Access-Control-Allow-Methods')) {
                    $result->allowMethods = $resp->getHeader('Access-Control-Allow-Methods')[0];
                }
                if ($resp->hasHeader('Access-Control-Allow-Age')) {
                    $result->allowAge = $resp->getHeader('Access-Control-Allow-Age')[0];
                }
                if ($resp->hasHeader('Access-Control-Allow-Headers')) {
                    $result->allowHeaders = $resp->getHeader('Access-Control-Allow-Headers')[0];
                }
                if ($resp->hasHeader('Access-Control-Expose-Headers')) {
                    $result->exposeHeaders = $resp->getHeader('Access-Control-Expose-Headers')[0];
                }
                if ($resp->hasHeader('x-oss-transition-time')) {
                    $result->transitionTime = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('x-oss-transition-time')[0],
                        new \DateTimeZone('UTC')
                    );
                }
                // usermetadata
                $result->metadata = Functions::tryUserMetadata($output->getHeaders());
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetObjectMetaRequest $request
     * @return OperationInput
     */
    public static function fromGetObjectMeta(Models\GetObjectMetaRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'GetObjectMeta',
            'HEAD',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('objectMeta', '');
        $input->setOpMetadata('sub-resource', ["objectMeta",]);
        $customSerializer = [
            static function (Models\GetObjectMetaRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
            }
        ];
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetObjectMetaResult
     */
    public static function toGetObjectMeta(OperationOutput $output): Models\GetObjectMetaResult
    {
        $result = new Models\GetObjectMetaResult();
        $customDeserializer = [
            static function (Models\GetObjectMetaResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('Content-Length')) {
                    $result->contentLength = intval($resp->getHeader('Content-Length')[0]);
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                if ($resp->hasHeader('Last-Modified')) {
                    $result->lastModified = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('Last-Modified')[0],
                        new \DateTimeZone('UTC')
                    );
                }
                if ($resp->hasHeader('x-oss-hash-crc64ecma')) {
                    $result->hashCrc64 = $resp->getHeader('x-oss-hash-crc64ecma')[0];
                }
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-last-access-time')) {
                    $result->lastAccessTime = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('x-oss-last-access-time')[0],
                        new \DateTimeZone('UTC')
                    );
                }
                if ($resp->hasHeader('x-oss-transition-time')) {
                    $result->transitionTime = \DateTime::createFromFormat(
                        'D, d M Y H:i:s \G\M\T',
                        $resp->getHeader('x-oss-transition-time')[0],
                        new \DateTimeZone('UTC')
                    );
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\RestoreObjectRequest $request
     * @return OperationInput
     */
    public static function fromRestoreObject(Models\RestoreObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'RestoreObject',
            'POST',
            ['Content-Type' => 'application/xml'],
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('restore', '');
        $input->setOpMetadata('sub-resource', ["restore",]);
        $customSerializer = [
            static function (Models\RestoreObjectRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
                }
                if (isset($request->requestPayer)) {
                    $input->setHeader('x-oss-request-payer', $request->requestPayer);
                }
                if (isset($request->restoreRequest)) {
                    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><RestoreRequest></RestoreRequest>');
                    if (isset($request->restoreRequest->days)) {
                        $xml->addChild('Days', (string)$request->restoreRequest->days);
                    }
                    if (isset($request->restoreRequest->tier)) {
                        $tier = $xml->addChild('JobParameters');
                        $tier->addChild('Tier', $request->restoreRequest->tier);
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
     * @return Models\RestoreObjectResult
     */
    public static function toRestoreObject(OperationOutput $output): Models\RestoreObjectResult
    {
        $result = new Models\RestoreObjectResult();
        $customDeserializer = [
            static function (Models\RestoreObjectResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-object-restore-priority')) {
                    $result->restorePriority = $resp->getHeader('x-oss-object-restore-priority')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CleanRestoredObjectRequest $request
     * @return OperationInput
     */
    public static function fromCleanRestoredObject(Models\CleanRestoredObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'CleanRestoredObject',
            'POST',
            ['Content-Type' => 'application/xml'],
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('cleanRestoredObject', '');
        $input->setOpMetadata('sub-resource', ["cleanRestoredObject"]);
        $customSerializer = [
            static function (Models\CleanRestoredObjectRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\CleanRestoredObjectResult
     */
    public static function toCleanRestoredObject(OperationOutput $output): Models\CleanRestoredObjectResult
    {
        $result = new Models\CleanRestoredObjectResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\PutObjectAclRequest $request
     * @return OperationInput
     */
    public static function fromPutObjectAcl(Models\PutObjectAclRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('acl', $request->acl);
        $input = new OperationInput(
            'PutObjectAcl',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('acl', '');
        $input->setOpMetadata('sub-resource', ["acl",]);
        $customSerializer = [
            static function (Models\PutObjectAclRequest $request, OperationInput $input) {
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
                }
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\PutObjectAclResult
     */
    public static function toPutObjectAcl(OperationOutput $output): Models\PutObjectAclResult
    {
        $result = new Models\PutObjectAclResult();
        $customDeserializer = [
            static function (Models\PutObjectAclResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetObjectAclRequest $request
     * @return OperationInput
     */
    public static function fromGetObjectAcl(Models\GetObjectAclRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'GetObjectAcl',
            'GET',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('acl', '');
        $input->setOpMetadata('sub-resource', ["acl",]);
        $customSerializer = [
            static function (Models\GetObjectAclRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\GetObjectAclResult
     */
    public static function toGetObjectAcl(OperationOutput $output): Models\GetObjectAclResult
    {
        $result = new Models\GetObjectAclResult();
        $customDeserializer = [
            static function (Models\GetObjectAclResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
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
     * @param Models\PutObjectTaggingRequest $request
     * @return OperationInput
     */
    public static function fromPutObjectTagging(Models\PutObjectTaggingRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('tagging', $request->tagging);
        $input = new OperationInput(
            'PutObjectTagging',
            'PUT',
            ['Content-Type' => 'application/xml'],
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('tagging', '');
        $input->setOpMetadata('sub-resource', ["tagging",]);
        $customSerializer = [
            static function (Models\PutObjectTaggingRequest $request, OperationInput $input) {
                if (isset($request->tagging)) {
                    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Tagging></Tagging>');
                    if (isset($request->tagging->tagSet)) {
                        $xmlTagSet = $xml->addChild('TagSet');
                        foreach ($request->tagging->tagSet->tags as $tag) {
                            $xmlTag = $xmlTagSet->addChild('Tag');
                            $xmlTag->addChild('Key', strval($tag->key));
                            $xmlTag->addChild('Value', strval($tag->value));
                        }
                    }
                    $input->setBody(Utils::streamFor($xml->asXML()));
                }
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\PutObjectTaggingResult
     */
    public static function toPutObjectTagging(OperationOutput $output): Models\PutObjectTaggingResult
    {
        $result = new Models\PutObjectTaggingResult();
        $customDeserializer = [
            static function (Models\PutObjectTaggingResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetObjectTaggingRequest $request
     * @return OperationInput
     */
    public static function fromGetObjectTagging(Models\GetObjectTaggingRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'GetObjectTagging',
            'GET',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('tagging', '');
        $input->setOpMetadata('sub-resource', ["tagging",]);
        $customSerializer = [
            static function (Models\GetObjectTaggingRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\GetObjectTaggingResult
     */
    public static function toGetObjectTagging(OperationOutput $output): Models\GetObjectTaggingResult
    {
        $result = new Models\GetObjectTaggingResult();
        $customDeserializer = [
            static function (Models\GetObjectTaggingResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                Functions::assertXmlRoot($body, 'Tagging');
                $xml = Utils::parseXml($body);
                if (isset($xml->TagSet)) {
                    $tagset = new Models\TagSet();
                    $tagset->tags = [];
                    foreach ($xml->TagSet->Tag as $tag) {
                        $tagset->tags[] = new Models\Tag(
                            Functions::tryToString($tag->Key),
                            Functions::tryToString($tag->Value),
                        );
                    }
                    $result->tagSet = $tagset;
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteObjectTaggingRequest $request
     * @return OperationInput
     */
    public static function fromDeleteObjectTagging(Models\DeleteObjectTaggingRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'DeleteObjectTagging',
            'DELETE',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('tagging', '');
        $input->setOpMetadata('sub-resource', ["tagging",]);
        $customSerializer = [
            static function (Models\DeleteObjectTaggingRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\DeleteObjectTaggingResult
     */
    public static function toDeleteObjectTagging(OperationOutput $output): Models\DeleteObjectTaggingResult
    {
        $result = new Models\DeleteObjectTaggingResult();
        $customDeserializer = [
            static function (Models\DeleteObjectTaggingResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutSymlinkRequest $request
     * @return OperationInput
     */
    public static function fromPutSymlink(Models\PutSymlinkRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('target', $request->target);
        $input = new OperationInput(
            'PutSymlink',
            'PUT',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('symlink', '');
        $input->setOpMetadata('sub-resource', ["symlink",]);
        $customSerializer = [
            static function (Models\PutSymlinkRequest $request, OperationInput $input) {
                if (isset($request->target)) {
                    $input->setHeader('x-oss-symlink-target', $request->target);
                }
                if (isset($request->acl)) {
                    $input->setHeader('x-oss-object-acl', $request->acl);
                }
                if (isset($request->storageClass)) {
                    $input->setHeader('x-oss-storage-class', $request->storageClass);
                }
                if (isset($request->metadata)) {
                    foreach ($request->metadata as $k => $v) {
                        $input->setHeader("x-oss-meta-$k", (string)$v);
                    }
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
        Functions::serializeInputLite($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutSymlinkResult
     */
    public static function toPutSymlink(OperationOutput $output): Models\PutSymlinkResult
    {
        $result = new Models\PutSymlinkResult();
        $customDeserializer = [
            static function (Models\PutSymlinkResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetSymlinkRequest $request
     * @return OperationInput
     */
    public static function fromGetSymlink(Models\GetSymlinkRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        $input = new OperationInput(
            'GetSymlink',
            'GET',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('symlink', '');
        $input->setOpMetadata('sub-resource', ["symlink",]);
        $customSerializer = [
            static function (Models\GetSymlinkRequest $request, OperationInput $input) {
                if (isset($request->versionId)) {
                    $input->setParameter('versionId', $request->versionId);
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
     * @return Models\GetSymlinkResult
     */
    public static function toGetSymlink(OperationOutput $output): Models\GetSymlinkResult
    {
        $result = new Models\GetSymlinkResult();
        $customDeserializer = [
            static function (Models\GetSymlinkResult $result, OperationOutput $output) {
                // ResponseInterface supports case-insensitive header field name
                $resp = $output->getHttpResponse();
                if ($resp->hasHeader('x-oss-version-id')) {
                    $result->versionId = $resp->getHeader('x-oss-version-id')[0];
                }
                if ($resp->hasHeader('x-oss-symlink-target')) {
                    $result->target = $resp->getHeader('x-oss-symlink-target')[0];
                }
                if ($resp->hasHeader('ETag')) {
                    $result->etag = $resp->getHeader('ETag')[0];
                }
                $result->metadata = Functions::tryUserMetadata($output->getHeaders());
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\AsyncProcessObjectRequest $request
     * @return OperationInput
     */
    public static function fromAsyncProcessObject(Models\AsyncProcessObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('process', $request->process);
        $input = new OperationInput(
            'AsyncProcessObject',
            'POST',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('x-oss-async-process', '');
        $customSerializer = [
            static function (Models\AsyncProcessObjectRequest $request, OperationInput $input) {
                if (isset($request->process)) {
                    $input->setBody(Utils::streamFor(sprintf("%s=%s", "x-oss-async-process", $request->process)));
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
     * @return Models\AsyncProcessObjectResult
     */
    public static function toAsyncProcessObject(OperationOutput $output): Models\AsyncProcessObjectResult
    {
        $result = new Models\AsyncProcessObjectResult();
        $customDeserializer = [
            static function (Models\AsyncProcessObjectResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                if (strlen($body) > 0) {
                    $processResult = json_decode($body, true);
                    if (is_array($processResult)) {
                        if (array_key_exists('EventId', $processResult)) {
                            $result->eventId = $processResult['EventId'];
                        }
                        if (array_key_exists('RequestId', $processResult)) {
                            $result->processRequestId = $processResult['RequestId'];
                        }
                        if (array_key_exists('TaskId', $processResult)) {
                            $result->taskId = $processResult['TaskId'];
                        }
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ProcessObjectRequest $request
     * @return OperationInput
     */
    public static function fromProcessObject(Models\ProcessObjectRequest $request): OperationInput
    {
        Functions::assertFieldRequired('bucket', $request->bucket);
        Functions::assertFieldRequired('key', $request->key);
        Functions::assertFieldRequired('process', $request->process);
        $input = new OperationInput(
            'ProcessObject',
            'POST',
        );
        $input->setBucket($request->bucket);
        $input->setKey($request->key);
        $input->setParameter('x-oss-process', '');
        $customSerializer = [
            static function (Models\ProcessObjectRequest $request, OperationInput $input) {
                if (isset($request->process)) {
                    $input->setBody(Utils::streamFor(sprintf("%s=%s", "x-oss-process", $request->process)));
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
     * @return Models\ProcessObjectResult
     */
    public static function toProcessObject(OperationOutput $output): Models\ProcessObjectResult
    {
        $result = new Models\ProcessObjectResult();
        $customDeserializer = [
            static function (Models\ProcessObjectResult $result, OperationOutput $output) {
                $body = $output->getBody() != null ? $output->getBody()->getContents() : '';
                if (strlen($body) > 0) {
                    $processResult = json_decode($body, true);
                    if (is_array($processResult)) {
                        if (array_key_exists('bucket', $processResult)) {
                            $result->bucket = $processResult['bucket'];
                        }
                        if (array_key_exists('fileSize', $processResult)) {
                            $result->fileSize = (int)$processResult['fileSize'];
                        }
                        if (array_key_exists('object', $processResult)) {
                            $result->key = $processResult['object'];
                        }
                        if (array_key_exists('status', $processResult)) {
                            $result->processStatus = $processResult['status'];
                        }
                    }
                }
            },
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
