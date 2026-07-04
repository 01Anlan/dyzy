<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the CompleteMultipartUpload operation.
 * Class CompleteMultipartUploadRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CompleteMultipartUploadRequest extends RequestModel
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
     * The identifier of the multipart upload task.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The access control list (ACL) of the object.
     * @var string|null
     */
    public ?string $acl;

    /**
     * The request body schema.
     * @var CompleteMultipartUpload|null
     */
    public ?CompleteMultipartUpload $completeMultipartUpload;

    /**
     * Specifies whether to list all parts that are uploaded by using the current upload ID.Valid value: yes.- If x-oss-complete-all is set to yes in the request, OSS lists all parts that are uploaded by using the current upload ID, sorts the parts by part number, and then performs the CompleteMultipartUpload operation. When OSS performs the CompleteMultipartUpload operation, OSS cannot detect the parts that are not uploaded or currently being uploaded. Before you call the CompleteMultipartUpload operation, make sure that all parts are uploaded.- If x-oss-complete-all is specified in the request, the request body cannot be specified. Otherwise, an error occurs.- If x-oss-complete-all is specified in the request, the format of the response remains unchanged.
     * @var string|null
     */
    public ?string $completeAll;

    /**
     * A callback parameter is a Base64-encoded string that contains multiple fields in the JSON format.
     * @var string|null
     */
    public ?string $callback;

    /**
     * Configure custom parameters by using the callback-var parameter.
     * @var string|null
     */
    public ?string $callbackVar;

    /**
     * Specifies whether the object with the same object name is overwritten when you call the CompleteMultipartUpload operation.- If the value of x-oss-forbid-overwrite is not specified or set to false, the existing object can be overwritten by the object that has the same name. - If the value of x-oss-forbid-overwrite is set to true, the existing object cannot be overwritten by the object that has the same name. - The x-oss-forbid-overwrite request header is invalid if versioning is enabled or suspended for the bucket. In this case, the existing object can be overwritten by the object that has the same name when you call the CompleteMultipartUpload operation. - If you specify the x-oss-forbid-overwrite request header, the queries per second (QPS) performance of OSS may be degraded. If you want to configure the x-oss-forbid-overwrite header in a large number of requests (QPS  1,000), submit a ticket.
     * @var bool|null
     */
    public ?bool $forbidOverwrite;

    /**
     * The encoding type of the object name in the response. Only URL encoding is supported.The object name can contain characters that are encoded in UTF-8. However, the XML 1.0 standard cannot be used to parse control characters, such as characters with an ASCII value from 0 to 10. You can configure this parameter to encode the object name in the response.
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * CompleteMultipartUploadRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $uploadId The identifier of the multipart upload task.
     * @param string|null $acl The access control list (ACL) of the object.
     * @param CompleteMultipartUpload|null $completeMultipartUpload The request body schema.
     * @param string|null $completeAll Specifies whether to list all parts that are uploaded by using the current upload ID.
     * @param string|null $callback A callback parameter is a Base64-encoded string that contains multiple fields in the JSON format.
     * @param string|null $callbackVar Configure custom parameters by using the callback-var parameter.
     * @param bool|null $forbidOverwrite Specifies whether the object with the same object name is overwritten when you call the CompleteMultipartUpload operation.
     * @param string|null $encodingType The encoding type of the object name in the response.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param array|null $options
     * @param string|null $objectAcl The access control list (ACL) of the object. The object acl parameter has the same functionality as the acl parameter. it is the standardized name for acl. If both exist simultaneously, the value of objectAcl will take precedence.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $uploadId = null,
        ?string $acl = null,
        ?CompleteMultipartUpload $completeMultipartUpload = null,
        ?string $completeAll = null,
        ?string $callback = null,
        ?string $callbackVar = null,
        ?bool $forbidOverwrite = null,
        ?string $encodingType = null,
        ?string $requestPayer = null,
        ?array $options = null,
        ?string $objectAcl = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->acl = $objectAcl ?? $acl;
        $this->completeMultipartUpload = $completeMultipartUpload;
        $this->completeAll = $completeAll;
        $this->callback = $callback;
        $this->callbackVar = $callbackVar;
        $this->forbidOverwrite = $forbidOverwrite;
        $this->encodingType = $encodingType;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
