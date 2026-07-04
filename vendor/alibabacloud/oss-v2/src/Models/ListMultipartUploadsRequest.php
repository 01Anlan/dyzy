<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListMultipartUploads operation.
 * Class ListMultipartUploadsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListMultipartUploadsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The character used to group objects by name. Objects whose names contain the same string that ranges from the specified prefix to the delimiter that appears for the first time are grouped as a CommonPrefixes element.
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * The encoding type of the object name in the response. Values of Delimiter, KeyMarker, Prefix, NextKeyMarker, and Key can be encoded in UTF-8. However, the XML 1.0 standard cannot be used to parse control characters such as characters with an American Standard Code for Information Interchange (ASCII) value from 0 to 10. You can set the encoding-type parameter to encode values of Delimiter, KeyMarker, Prefix, NextKeyMarker, and Key in the response.Default value: null
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * This parameter is used together with the upload-id-marker parameter to specify the position from which the next list begins.- If the upload-id-marker parameter is not set, Object Storage Service (OSS) returns all multipart upload tasks in which object names are alphabetically after the key-marker value.- If the upload-id-marker parameter is set, the response includes the following tasks:  - Multipart upload tasks in which object names are alphabetically after the key-marker value in alphabetical order  - Multipart upload tasks in which object names are the same as the key-marker parameter value but whose upload IDs are greater than the upload-id-marker parameter value
     * @var string|null
     */
    public ?string $keyMarker;

    /**
     * The upload ID of the multipart upload task after which the list begins. This parameter is used together with the key-marker parameter.- If the key-marker parameter is not set, OSS ignores the upload-id-marker parameter.- If the key-marker parameter is configured, the query result includes:  - Multipart upload tasks in which object names are alphabetically after the key-marker value in alphabetical order  - Multipart upload tasks in which object names are the same as the key-marker parameter value but whose upload IDs are greater than the upload-id-marker parameter value
     * @var string|null
     */
    public ?string $uploadIdMarker;

    /**
     * The maximum number of multipart upload tasks that can be returned for the current request. Default value: 1000. Maximum value: 1000.
     * @var int|null
     */
    public ?int $maxUploads;

    /**
     * The prefix that the returned object names must contain. If you specify a prefix in the request, the specified prefix is included in the response.You can use prefixes to group and manage objects in buckets in the same way you manage a folder in a file system.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * ListMultipartUploadsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $delimiter The character used to group objects by name.
     * @param string|null $encodingType The encoding type of the object name in the response.
     * @param string|null $keyMarker This parameter is used together with the upload-id-marker parameter to specify the position from which the next list begins.
     * @param string|null $uploadIdMarker The upload ID of the multipart upload task after which the list begins.
     * @param int|null $maxUploads The maximum number of multipart upload tasks that can be returned for the current request.
     * @param string|null $prefix The prefix that the returned object names must contain.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $delimiter = null,
        ?string $encodingType = null,
        ?string $keyMarker = null,
        ?string $uploadIdMarker = null,
        ?int $maxUploads = null,
        ?string $prefix = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->delimiter = $delimiter;
        $this->encodingType = $encodingType;
        $this->keyMarker = $keyMarker;
        $this->uploadIdMarker = $uploadIdMarker;
        $this->maxUploads = $maxUploads;
        $this->prefix = $prefix;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
