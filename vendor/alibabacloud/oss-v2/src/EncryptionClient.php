<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Crypto;

/**
 * The Encryption Client
 * Class EncryptionClient
 * @package AlibabaCloud\Oss\V2
 */
final class EncryptionClient
{
    /**
     * The oss client.
     * @var Client
     */
    private Client $client;
    private Crypto\AesCtrCipherBuilder $defaultCipherBuilder;

    /**
     * @var array<string, Crypto\AesCtrCipherBuilder>
     */
    private array $cipherBuilderMap;

    /**
     * @var int
     */
    private int $alignLen = Crypto\AesCtr::BLOCK_SIZE_LEN;

    /**
     * EncryptionClient constructor
     * @param Client $client
     * @param Crypto\MasterCipherInterface $masterCipher
     * @param array<Crypto\MasterCipherInterface> $decryptMasterCiphers
     */
    public function __construct(
        Client $client,
        Crypto\MasterCipherInterface $masterCipher,
        ?array $decryptMasterCiphers = null
    )
    {
        $this->client = $client;
        $this->defaultCipherBuilder = new Crypto\AesCtrCipherBuilder($masterCipher);
        $this->cipherBuilderMap = [];
        foreach ($decryptMasterCiphers ?? [] as $mc) {
            if ($mc->getMatDesc() === '') {
                continue;
            }
            $this->cipherBuilderMap[$mc->getMatDesc()] = new Crypto\AesCtrCipherBuilder($mc);
        }
    }

    public function unwrap(): Client
    {
        return $this->client;
    }

    /**
     * You can call this operation to upload an object.
     * @param Models\PutObjectRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function putObjectAsync(Models\PutObjectRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->putObjectSecurelyAsync($request, $args);
    }

    /**
     * You can call this operation to upload an object.
     * @param Models\PutObjectRequest $request
     * @param array $args
     * @return Models\PutObjectResult
     */
    public function putObject(Models\PutObjectRequest $request, array $args = []): Models\PutObjectResult
    {
        return $this->putObjectAsync($request, $args)->wait();
    }

    /**
     * @param Models\GetObjectRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function getObjectAsync(Models\GetObjectRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->getObjectSecurelyAsync($request, $args);
    }

    /**
     * You can call this operation to query an object.
     * @param Models\GetObjectRequest $request
     * @param array $args
     * @return Models\GetObjectResult
     */
    public function getObject(Models\GetObjectRequest $request, array $args = []): Models\GetObjectResult
    {
        return $this->getObjectAsync($request, $args)->wait();
    }

    /**
     * You can call this operation to query the metadata of an object.
     * @param Models\HeadObjectRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function headObjectAsync(Models\HeadObjectRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->headObjectAsync($request, $args);
    }

    /**
     * You can call this operation to query the metadata of an object.
     * @param Models\HeadObjectRequest $request
     * @param array $args
     * @return Models\HeadObjectResult
     */
    public function headObject(Models\HeadObjectRequest $request, array $args = []): Models\HeadObjectResult
    {
        return $this->headObjectAsync($request, $args)->wait();
    }

    /**
     * Initiates a multipart upload task.
     * @param Models\InitiateMultipartUploadRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     * @throws \Exception
     */
    public function initiateMultipartUploadAsync(Models\InitiateMultipartUploadRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->initiateMultipartUploadSecurelyAsync($request, $args);
    }

    /**
     * Initiates a multipart upload task.
     * @param Models\InitiateMultipartUploadRequest $request
     * @param array $args
     * @return Models\InitiateMultipartUploadResult
     * @throws \Exception
     */
    public function initiateMultipartUpload(Models\InitiateMultipartUploadRequest $request, array $args = []): Models\InitiateMultipartUploadResult
    {
        return $this->initiateMultipartUploadAsync($request, $args)->wait();
    }

    /**
     * You can call this operation to upload an object by part based on the object name and the upload ID that you specify.
     * @param Models\UploadPartRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function uploadPartAsync(Models\UploadPartRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->uploadPartSecurelyAsync($request, $args);
    }

    /**
     * You can call this operation to upload an object by part based on the object name and the upload ID that you specify.
     * @param Models\UploadPartRequest $request
     * @param array $args
     * @return Models\UploadPartResult
     */
    public function uploadPart(Models\UploadPartRequest $request, array $args = []): Models\UploadPartResult
    {
        return $this->uploadPartAsync($request, $args)->wait();
    }

    /**
     * You can call this operation to complete the multipart upload task of an object.
     * @param Models\CompleteMultipartUploadRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function completeMultipartUploadAsync(Models\CompleteMultipartUploadRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->completeMultipartUploadAsync($request, $args);
    }

    /**
     * You can call this operation to complete the multipart upload task of an object.
     * @param Models\CompleteMultipartUploadRequest $request
     * @param array $args
     * @return Models\CompleteMultipartUploadResult
     */
    public function completeMultipartUpload(Models\CompleteMultipartUploadRequest $request, array $args = []): Models\CompleteMultipartUploadResult
    {
        return $this->completeMultipartUploadAsync($request, $args)->wait();
    }

    /**
     * Cancels a multipart upload task and deletes the parts uploaded in the task
     * @param Models\AbortMultipartUploadRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function abortMultipartUploadAsync(Models\AbortMultipartUploadRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->abortMultipartUploadAsync($request, $args);
    }

    /**
     * Cancels a multipart upload task and deletes the parts uploaded in the task
     * @param Models\AbortMultipartUploadRequest $request
     * @param array $args
     * @return Models\AbortMultipartUploadResult
     */
    public function abortMultipartUpload(Models\AbortMultipartUploadRequest $request, array $args = []): Models\AbortMultipartUploadResult
    {
        return $this->abortMultipartUploadAsync($request, $args)->wait();
    }

    /**
     * Lists all parts that are uploaded by using a specified upload ID.
     * @param Models\ListPartsRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function listPartsAsync(Models\ListPartsRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->listPartsAsync($request, $args);
    }

    /**
     * Lists all parts that are uploaded by using a specified upload ID.
     * @param Models\ListPartsRequest $request
     * @param array $args
     * @return Models\ListPartsResult
     */
    public function listParts(Models\ListPartsRequest $request, array $args = []): Models\ListPartsResult
    {
        return $this->listPartsAsync($request, $args)->wait();
    }

    /**
     * You can call this operation to query the metadata of an object, including ETag, Size, and LastModified. The content of the object is not returned.
     * @param Models\GetObjectMetaRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function getObjectMetaAsync(Models\GetObjectMetaRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->getObjectMetaAsync($request, $args);
    }

    /**
     * You can call this operation to query the metadata of an object, including ETag, Size, and LastModified. The content of the object is not returned.
     * @param Models\GetObjectMetaRequest $request
     * @param array $args
     * @return Models\GetObjectMetaResult
     */
    public function getObjectMeta(Models\GetObjectMetaRequest $request, array $args = []): Models\GetObjectMetaResult
    {
        return $this->getObjectMetaAsync($request, $args)->wait();
    }

    /**
     * Creates uploader.
     * The uploader calls the multipart upload operation to split a large local file or
     * stream into multiple smaller parts and upload the parts in parallel to improve upload performance.
     *
     * @param array $args accepts the following:
     * - part_size: (int) The part size. Default value: 6 MiB.
     * - parallel_num: (int) The number of the upload tasks in parallel.
     *   Default value: 3.
     * - leave_parts_on_error: (bool) Specifies whether to retain the uploaded parts when an upload task fails.
     *   By default, the uploaded parts are not retained.
     * @return Uploader
     */
    public function newUploader(array $args = []): Uploader
    {
        return new Uploader($this, $args);
    }

    /**
     * Creates a new Downloader instance to download objects.
     * @param array $args
     * @return Downloader
     */
    public function newDownloader(array $args = []): Downloader
    {
        return new Downloader($this, $args);
    }

    /**
     * @param Models\PutObjectRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function putObjectSecurelyAsync(Models\PutObjectRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        $eRequest = clone $request;
        $cc = $this->defaultCipherBuilder->fromCipherData();
        if ($request->body != null) {
            $cipher = $cc->getCipher(0);
            $eRequest->body = new Crypto\ReadEncryptStream($request->body, $cipher);
        }
        $this->addCryptoHeaders($eRequest, $cc->getCipherData());
        return $this->client->putObjectAsync($eRequest, $args);
    }

    /**
     * @param Models\GetObjectRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function getObjectSecurelyAsync(Models\GetObjectRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        $rangeHeader = null;
        $discardCount = 0;
        $adjustOffset = 0;
        if ($request->rangeHeader != null) {
            $ranges = Utils::parseHttpRange($request->rangeHeader);
            if ($ranges === false) {
                throw new \InvalidArgumentException("request.rangeHeader is invalid, got $request->rangeHeader");
            }
            $start = $ranges[0] < 0 ? 0 : $ranges[0];
            $adjustOffset = $this->adjustRangeStart($start);
            $discardCount = $start - $adjustOffset;
            if ($discardCount > 0) {
                // bytes=0-1023 or bytes=0-
                $rangeHeader = sprintf('bytes=%d-', $adjustOffset);
                if ($ranges[1] >= 0) {
                    $rangeHeader .= strval($ranges[1]);
                }
            }
        }

        $eRequest = $request;
        if ($rangeHeader != null) {
            $eRequest = clone $request;
            $eRequest->rangeHeader = $rangeHeader;
            $eRequest->rangeBehavior = 'standard';
        }

        // stream mode
        if (
            isset($args['request_options']) &&
            isset($args['request_options']['stream']) &&
            $args['request_options']['stream'] === true
        ) {
            /**
             * @var Crypto\ContentCipherInterface $contentCipher
             */
            $contentCipher = null;
            $args['request_options'] = array_merge($args['request_options'], [
                'on_headers' => function ($response) use (&$contentCipher) {
                    $contentCipher = $this->contentCipherfromHeaders($response);
                }
            ]);
            return $this->client->getObjectAsync($eRequest, $args)->then(
                function (Models\GetObjectResult $result) use (&$adjustOffset, &$discardCount, &$contentCipher) {
                    $body = $result->body;
                    if ($contentCipher !== null) {
                        $cipher = $contentCipher->getCipher($adjustOffset);
                        $body = new Crypto\ReadDecryptStream($body, $cipher);
                    }
                    if ($discardCount > 0) {
                        $body->read($discardCount);
                    }
                    $result = $this->adjustGetObjectResult($result, $discardCount);
                    $result->body = $body;
                    return $result;
                }
            );
        }

        // non stream mode, and save into
        $sink = null;
        if (isset($args['request_options']) && isset($args['request_options']['sink'])) {
            $sink = $args['request_options']['sink'];
        }
        $fromFilepath = \is_string($sink);
        $stream = $fromFilepath ?
            new GuzzleHttp\Psr7\LazyOpenStream($sink, 'w+') : Utils::streamFor($sink);

        $esink = new Crypto\LazyDecryptStream(
            $stream,
            $discardCount,
            function ($response) {
                return $this->contentCipherfromHeaders($response);
            }
        );

        $args['request_options'] = array_merge($args['request_options'] ?? [], [
            'sink' => $esink
        ]);
        return $this->client->getObjectAsync($eRequest, $args)->then(
            function (Models\GetObjectResult $result) use (&$discardCount, &$fromFilepath) {
                $result = $this->adjustGetObjectResult($result, $discardCount);
                // unwrap stream from LazyDecryptStream
                if ($result->body instanceof Crypto\LazyDecryptStream) {
                    $result->body = $fromFilepath ? null : $result->body->unwrap();
                }
                return $result;
            }
        );
    }

    /**
     * @param Models\InitiateMultipartUploadRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     * @throws \Exception
     */
    public function initiateMultipartUploadSecurelyAsync(Models\InitiateMultipartUploadRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        $eRequest = clone $request;
        $this->validEncryption($eRequest);
        $cc = $this->defaultCipherBuilder->fromCipherData();
        $this->addMultiPartCryptoHeaders($eRequest, $cc->getCipherData());
        return $this->client->initiateMultipartUploadAsync($eRequest, $args)->then(function (Models\InitiateMultipartUploadResult $result) use ($eRequest, $cc) {
            $result->encryptionMultipartContext = new Models\EncryptionMultipartContext(
                $cc,
                $eRequest->cseDataSize,
                $eRequest->csePartSize
            );
            return $result;
        });
    }

    /**
     * @param Models\UploadPartRequest $request
     * @param array $args
     * @return GuzzleHttp\Promise\Promise
     */
    public function uploadPartSecurelyAsync(Models\UploadPartRequest $request, array $args = []): GuzzleHttp\Promise\Promise
    {
        $eRequest = clone $request;
        if ($eRequest->encryptionMultipartContext === null) {
            throw new \InvalidArgumentException("request.encryptionMultiPart is null.");
        }

        if (!$eRequest->encryptionMultipartContext->valid()) {
            throw new \InvalidArgumentException("request.encryptionMultiPart is invalid.");
        }

        if ($eRequest->encryptionMultipartContext->partSize % $this->alignLen != 0) {
            throw new \InvalidArgumentException("request.encryptionMultiPart.partSize must be aligned to " . $this->alignLen);
        }

        $cipherData = clone $eRequest->encryptionMultipartContext->contentCipher->getCipherData();
        $offset = 0;
        if ($request->partNumber > 1) {
            $offset = ($request->partNumber - 1) * $eRequest->encryptionMultipartContext->partSize;
        }
        $cc = $this->defaultCipherBuilder->fromCipherData($cipherData);
        if ($request->body != null) {
            $cipher = $cc->getCipher($offset);
            $eRequest->body = new Crypto\ReadEncryptStream($request->body, $cipher);
        }

        $this->addUploadPartCryptoHeaders($eRequest, $cc->getCipherData(), $eRequest->encryptionMultipartContext);

        return $this->client->uploadPartAsync($eRequest, $args);
    }

    /**
     * @param Models\PutObjectRequest $request
     * @param Crypto\CipherData $cipherData
     */
    private function addCryptoHeaders(Models\PutObjectRequest &$request, Crypto\CipherData $cipherData)
    {
        if ($request->metadata == null) {
            $request->metadata = [];
        }

        /*
        "X-Oss-Meta-Client-Side-Encryption-Key"
        "X-Oss-Meta-Client-Side-Encryption-Start"
        "X-Oss-Meta-Client-Side-Encryption-Cek-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Wrap-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Matdesc"
        "X-Oss-Meta-Client-Side-Encryption-Unencrypted-Content-Length"
        "X-Oss-Meta-client-side-encryption-unencrypted-content-md5"
        "X-Oss-Meta-Client-Side-Encryption-Data-Size"
        "X-Oss-Meta-Client-Side-Encryption-Part-Size"
        */

        // convert content-md5
        if ($request->contentMd5 != null) {
            $request->metadata['client-side-encryption-unencrypted-content-md5'] = $request->contentMd5;
            $request->contentMd5 = null;
        }

        // convert content-length
        if ($request->contentLength != null) {
            $request->metadata['client-side-encryption-unencrypted-content-length'] = strval($request->contentLength);
            $request->contentLength = null;
        }

        // matDesc
        if (Utils::safetyString($cipherData->matDesc) != '') {
            $request->metadata['client-side-encryption-matdesc'] = $cipherData->matDesc;
        }

        // encrypted key
        $request->metadata['client-side-encryption-key'] = base64_encode($cipherData->encryptedKey);

        // encrypted iv
        $request->metadata['client-side-encryption-start'] = base64_encode($cipherData->encryptedIv);

        // wrap alg
        $request->metadata['client-side-encryption-wrap-alg'] = $cipherData->wrapAlgorithm;

        // cek alg
        $request->metadata['client-side-encryption-cek-alg'] = $cipherData->cekAlgorithm;
    }

    /**
     * @param Models\InitiateMultipartUploadRequest $request
     * @param Crypto\CipherData $cipherData
     */
    private function addMultiPartCryptoHeaders(Models\InitiateMultipartUploadRequest &$request, Crypto\CipherData $cipherData)
    {
        if ($request->metadata == null) {
            $request->metadata = [];
        }

        /*
        "X-Oss-Meta-Client-Side-Encryption-Key"
        "X-Oss-Meta-Client-Side-Encryption-Start"
        "X-Oss-Meta-Client-Side-Encryption-Cek-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Wrap-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Matdesc"
        "X-Oss-Meta-Client-Side-Encryption-Unencrypted-Content-Length"
        "X-Oss-Meta-client-side-encryption-unencrypted-content-md5"
        "X-Oss-Meta-Client-Side-Encryption-Data-Size"
        "X-Oss-Meta-Client-Side-Encryption-Part-Size"
        */

        // convert content-md5
        if ($request->contentMd5 != null) {
            $request->metadata['client-side-encryption-unencrypted-content-md5'] = $request->contentMd5;
            $request->contentMd5 = null;
        }

        // convert content-length
        if ($request->contentLength != null) {
            $request->metadata['client-side-encryption-unencrypted-content-length'] = strval($request->contentLength);
            $request->contentLength = null;
        }

        // matDesc
        if (Utils::safetyString($cipherData->matDesc) != '') {
            $request->metadata['client-side-encryption-matdesc'] = $cipherData->matDesc;
        }

        // data size
        $request->metadata['client-side-encryption-data-size'] = $request->cseDataSize;

        // part size
        $request->metadata['client-side-encryption-part-size'] = $request->csePartSize;

        // encrypted key
        $request->metadata['client-side-encryption-key'] = base64_encode($cipherData->encryptedKey);

        // encrypted iv
        $request->metadata['client-side-encryption-start'] = base64_encode($cipherData->encryptedIv);

        // wrap alg
        $request->metadata['client-side-encryption-wrap-alg'] = $cipherData->wrapAlgorithm;

        // cek alg
        $request->metadata['client-side-encryption-cek-alg'] = $cipherData->cekAlgorithm;
    }

    /**
     * @param Models\UploadPartRequest $request
     * @param Crypto\CipherData $cipherData
     * @param Models\EncryptionMultiPart $encryptionMultiPart
     */
    private function addUploadPartCryptoHeaders(Models\UploadPartRequest &$request, Crypto\CipherData $cipherData, Models\EncryptionMultipartContext $encryptionMultipart)
    {
        /*
        "X-Oss-Meta-Client-Side-Encryption-Key"
        "X-Oss-Meta-Client-Side-Encryption-Start"
        "X-Oss-Meta-Client-Side-Encryption-Cek-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Wrap-Alg"
        "X-Oss-Meta-Client-Side-Encryption-Matdesc"
        "X-Oss-Meta-Client-Side-Encryption-Unencrypted-Content-Length"
        "X-Oss-Meta-client-side-encryption-unencrypted-content-md5"
        "X-Oss-Meta-Client-Side-Encryption-Data-Size"
        "X-Oss-Meta-Client-Side-Encryption-Part-Size"
        */

        // convert content-md5
        if ($request->contentMd5 != null) {
            $request->clientSideEncryptionUnencryptedContentMd5 = $request->contentMd5;
            $request->contentMd5 = null;
        }

        // convert content-length
        if ($request->contentLength != null) {
            $request->clientSideEncryptionUnencryptedContentLength = $request->contentLength;
            $request->contentLength = null;
        }

        // matDesc
        if (Utils::safetyString($cipherData->matDesc) != '') {
            $request->clientSideEncryptionMatdesc = $cipherData->matDesc;
        }

        // data size
        $request->clientSideEncryptionDataSize = $encryptionMultipart->dataSize;

        // part size
        $request->clientSideEncryptionPartSize = $encryptionMultipart->partSize;

        // encrypted key
        $request->clientSideEncryptionKey = base64_encode($cipherData->encryptedKey);

        // encrypted iv
        $request->clientSideEncryptionStart = base64_encode($cipherData->encryptedIv);

        // wrap alg
        $request->clientSideEncryptionWrapAlg = $cipherData->wrapAlgorithm;

        // cek alg
        $request->clientSideEncryptionCekAlg = $cipherData->cekAlgorithm;
    }

    /**
     * @param int $offset
     * @return int
     */
    private function adjustRangeStart(int $offset): int
    {
        $remians = $offset % $this->alignLen;
        return $remians > 0 ? $offset - $remians : $offset;
    }

    /**
     * @param Models\GetObjectResult $result
     * @param $discardCount
     * @return Models\GetObjectResult
     */
    private function adjustGetObjectResult(Models\GetObjectResult $result, $discardCount)
    {
        if ($discardCount == 0) {
            return $result;
        }

        if ($result->contentLength != null) {
            $result->contentLength -= $discardCount;
            $result->headers['Content-Length'] = strval($result->contentLength);
        }

        if ($result->contentRange != null) {
            //"bytes 0-10239/25723"
            $vals = Utils::parseContentRange($result->contentRange);
            if ($vals === false) {
                throw new \RuntimeException("parse Content-Range error, got $result->contentRange");
            }
            $offset = $vals[0];
            $pos = strpos($result->contentRange, '-');
            $start = $offset + $discardCount;
            $val = "bytes $start" . substr($result->contentRange, $pos);
            $result->headers['Content-Range'] = $val;
            $result->contentRange = $val;
        }

        return $result;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return bool
     */
    private function hasEncryptedHeader(\Psr\Http\Message\ResponseInterface $response): bool
    {
        return $response->hasHeader('x-oss-meta-client-side-encryption-key');
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Crypto\ContentCipherInterface|null
     */
    private function contentCipherfromHeaders(\Psr\Http\Message\ResponseInterface $response)
    {
        if (!$this->hasEncryptedHeader($response)) {
            return null;
        }

        // env from headers
        $envelope = new Crypto\Envelope();
        $envelope->iv = base64_decode($response->getHeader('x-oss-meta-client-side-encryption-start')[0]);
        $envelope->key = base64_decode($response->getHeader('x-oss-meta-client-side-encryption-key')[0]);
        if ($response->hasHeader('x-oss-meta-client-side-encryption-matdesc')) {
            $envelope->matDesc = $response->getHeader('x-oss-meta-client-side-encryption-matdesc')[0];
        }

        $envelope->cekAlg = $response->getHeader('x-oss-meta-client-side-encryption-cek-alg')[0];
        $envelope->wrapAlg = $response->getHeader('x-oss-meta-client-side-encryption-wrap-alg')[0];

        if ($response->hasHeader('x-oss-meta-client-side-encryption-unencrypted-content-length')) {
            $envelope->unencryptedContentLen = $response->getHeader('x-oss-meta-client-side-encryption-unencrypted-content-length')[0];
        }

        if ($response->hasHeader('x-oss-meta-client-side-encryption-unencrypted-content-md5')) {
            $envelope->unencryptedMd5 = $response->getHeader('x-oss-meta-client-side-encryption-unencrypted-content-md5')[0];
        }

        $ccbuilder = $this->getContentCipherBuilder($envelope->matDesc);

        return $ccbuilder->fromEnvelope($envelope);
    }

    /**
     * @param string|null $matDesc
     * @return Crypto\AesCtrCipherBuilder|mixed
     */
    private function getContentCipherBuilder(?string $matDesc)
    {
        if (
            $matDesc != null &&
            $matDesc != '' &&
            \array_key_exists($matDesc, $this->cipherBuilderMap)
        ) {
            return $this->cipherBuilderMap[$matDesc];
        }

        return $this->defaultCipherBuilder;
    }

    /**
     * Valid part size.
     * @throws \InvalidArgumentException
     */
    private function validEncryption(Models\InitiateMultipartUploadRequest $request)
    {
        $partSize = intval($request->csePartSize);
        if ($partSize <= 0) {
            throw new \InvalidArgumentException('request.csePartSize is invalid.');
        }
        $dataSize = intval($request->cseDataSize);
        if ($dataSize <= 0) {
            throw new \InvalidArgumentException('request.cseDataSize is invalid.');
        }

        if ($partSize % $this->alignLen != 0) {
            throw new \InvalidArgumentException("request.csePartSize must be aligned to " . $this->alignLen);
        }
    }
}
