<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the UploadPart operation.
 * Class UploadPartRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class UploadPartRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The full path of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The number that identifies a part. Valid values: 1 to 10000.The size of a part ranges from 100 KB to 5 GB.  In multipart upload, each part except the last part must be larger than or equal to 100 KB in size. When you call the UploadPart operation, the size of each part is not verified because not all parts have been uploaded and OSS does not know which part is the last part. The size of each part is verified only when you call CompleteMultipartUpload.
     * @var int|null
     */
    public ?int $partNumber;

    /**
     * The ID that identifies the object to which the part that you want to upload belongs.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The size of the data in the HTTP message body. Unit: bytes.
     * @var int|null
     */
    public ?int $contentLength;

    /**
     * The MD5 hash of the object that you want to upload.
     * @var string|null
     */
    public ?string $contentMd5;

    /**
     * Specify the speed limit value. The speed limit value ranges from  245760 to 838860800, with a unit of bit/s.
     * @var int|null
     */
    public ?int $trafficLimit;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * The request body.
     * @var \Psr\Http\Message\StreamInterface|null
     */
    public ?\Psr\Http\Message\StreamInterface $body;

    /**
     * Progress callback function
     */
    public $progressFn;

    /**
     * The encryption context for multipart upload when using client side encryption, only valid in EncryptionClient
     * @var EncryptionMultipartContext|null
     */
    public ?EncryptionMultipartContext $encryptionMultipartContext;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionKey;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionStart;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionCekAlg;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionWrapAlg;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionMatdesc;

    /**
     * @var int|null
     */
    public ?int $clientSideEncryptionUnencryptedContentLength;

    /**
     * @var string|null
     */
    public ?string $clientSideEncryptionUnencryptedContentMd5;

    /**
     * @var int|null
     */
    public ?int $clientSideEncryptionDataSize;

    /**
     * @var int|null
     */
    public ?int $clientSideEncryptionPartSize;

    /**
     * UploadPartRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param int|null $partNumber The number that identifies a part.
     * @param string|null $uploadId The ID that identifies the object to which the part that you want to upload belongs.
     * @param int|null $contentLength The size of the data in the HTTP message body. Unit: bytes.
     * @param string|null $contentMd5 The MD5 hash of the object that you want to upload.
     * @param int|null $trafficLimit Specify the speed limit value.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param \Psr\Http\Message\StreamInterface|null $body The request body.
     * @param callable|null $progressFn Progress callback function
     * @param EncryptionMultipartContext|null $encryptionMultipartContext The encryption context for multipart upload when using client side encryption, only valid in EncryptionClient.
     * @param string|null $clientSideEncryptionKey
     * @param string|null $clientSideEncryptionStart
     * @param string|null $clientSideEncryptionCekAlg
     * @param string|null $clientSideEncryptionWrapAlg
     * @param string|null $clientSideEncryptionUnencryptedContentMd5
     * @param string|null $clientSideEncryptionUnencryptedContentLength
     * @param int|null $clientSideEncryptionPartSize
     * @param int|null $clientSideEncryptionDataSize
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?int $partNumber = null,
        ?string $uploadId = null,
        ?int $contentLength = null,
        ?string $contentMd5 = null,
        ?int $trafficLimit = null,
        ?string $requestPayer = null,
        ?\Psr\Http\Message\StreamInterface $body = null,
        ?callable $progressFn = null,
        ?EncryptionMultipartContext $encryptionMultipartContext = null,
        ?string $clientSideEncryptionKey = null,
        ?string $clientSideEncryptionStart = null,
        ?string $clientSideEncryptionCekAlg = null,
        ?string $clientSideEncryptionWrapAlg = null,
        ?string $clientSideEncryptionUnencryptedContentMd5 = null,
        ?string $clientSideEncryptionUnencryptedContentLength = null,
        ?int $clientSideEncryptionPartSize = null,
        ?int $clientSideEncryptionDataSize = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->partNumber = $partNumber;
        $this->uploadId = $uploadId;
        $this->contentLength = $contentLength;
        $this->contentMd5 = $contentMd5;
        $this->trafficLimit = $trafficLimit;
        $this->requestPayer = $requestPayer;
        $this->body = $body;
        $this->progressFn = $progressFn;
        $this->encryptionMultipartContext = $encryptionMultipartContext;
        $this->clientSideEncryptionKey = $clientSideEncryptionKey;
        $this->clientSideEncryptionStart = $clientSideEncryptionStart;
        $this->clientSideEncryptionCekAlg = $clientSideEncryptionCekAlg;
        $this->clientSideEncryptionWrapAlg = $clientSideEncryptionWrapAlg;
        $this->clientSideEncryptionUnencryptedContentMd5 = $clientSideEncryptionUnencryptedContentMd5;
        $this->clientSideEncryptionUnencryptedContentLength = $clientSideEncryptionUnencryptedContentLength;
        $this->clientSideEncryptionPartSize = $clientSideEncryptionPartSize;
        $this->clientSideEncryptionDataSize = $clientSideEncryptionDataSize;
        parent::__construct($options);
    }
}
