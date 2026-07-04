<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the InitiateMultipartUpload operation.
 * Class InitiateMultipartUploadResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class InitiateMultipartUploadResult extends ResultModel
{
    /**
     * The name of the bucket to which the object is uploaded by the multipart upload task.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object that is uploaded by the multipart upload task.
     * @var string|null
     */
    public ?string $key;

    /**
     * The upload ID that uniquely identifies the multipart upload task. The upload ID is used to call UploadPart and CompleteMultipartUpload later.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The encoding type of the object name in the response. If the encoding-type parameter is specified in the request, the object name in the response is encoded.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * Save encryption or decryption information
     * @var EncryptionMultipartContext|null
     */
    public ?EncryptionMultipartContext $encryptionMultipartContext;

    /**
     * InitiateMultipartUploadResult constructor.
     * @param string|null $bucket The name of the bucket to which the object is uploaded by the multipart upload task.
     * @param string|null $key The name of the object that is uploaded by the multipart upload task.
     * @param string|null $uploadId The upload ID that uniquely identifies the multipart upload task.
     * @param string|null $encodingType The encoding type of the object name in the response.
     * @param EncryptionMultipartContext|null $encryptionMultipartContext Save encryption or decryption information.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $uploadId = null,
        ?string $encodingType = null,
        ?EncryptionMultipartContext $encryptionMultipartContext = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->encodingType = $encodingType;
        $this->encryptionMultipartContext = $encryptionMultipartContext;
    }
}
