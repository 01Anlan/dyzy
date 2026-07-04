<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListObjectVersions operation.
 * Class ListObjectVersionsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectVersionsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The character that is used to group objects by name. If you specify prefix and delimiter in the request, the response contains CommonPrefixes. The objects whose name contains the same string from the prefix to the next occurrence of the delimiter are grouped as a single result element in CommonPrefixes. If you specify prefix and set delimiter to a forward slash (/), only the objects in the directory are listed. The subdirectories in the directory are returned in CommonPrefixes. Objects and subdirectories in the subdirectories are not listed.By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * The encoding type of the content in the response. By default, this parameter is left empty. Set the value to URL.  The values of Delimiter, Marker, Prefix, NextMarker, and Key are UTF-8 encoded. If the value of Delimiter, Marker, Prefix, NextMarker, or Key contains a control character that is not supported by Extensible Markup Language (XML) 1.0, you can specify encoding-type to encode the value in the response.
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The name of the object after which the GetBucketVersions (ListObjectVersions) operation begins. If this parameter is specified, objects whose name is alphabetically after the value of key-marker are returned. Use key-marker and version-id-marker in combination. The value of key-marker must be less than 1,024 bytes in length.By default, this parameter is left empty.  You must also specify key-marker if you specify version-id-marker.
     * @var string|null
     */
    public ?string $keyMarker;

    /**
     * The version ID of the object specified in key-marker after which the GetBucketVersions (ListObjectVersions) operation begins. The versions are returned from the latest version to the earliest version. If version-id-marker is not specified, the GetBucketVersions (ListObjectVersions) operation starts from the latest version of the object whose name is alphabetically after the value of key-marker by default.By default, this parameter is left empty.Valid values: version IDs.
     * @var string|null
     */
    public ?string $versionIdMarker;

    /**
     * The maximum number of objects to be returned. If the number of returned objects exceeds the value of max-keys, the response contains `NextKeyMarker` and `NextVersionIdMarker`. Specify the values of `NextKeyMarker` and `NextVersionIdMarker` as the markers for the next request. Valid values: 1 to 999. Default value: 100.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * The prefix that the names of returned objects must contain.*   The value of prefix must be less than 1,024 bytes in length.*   If you specify prefix, the names of the returned objects contain the prefix.If you set prefix to a directory name, the objects whose name starts with the prefix are listed. The returned objects consist of all objects and subdirectories in the directory.By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * ListObjectVersionsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param string|null $encodingType The encoding type of the content in the response.
     * @param string|null $keyMarker The name of the object after which the GetBucketVersions (ListObjectVersions) operation begins.
     * @param string|null $versionIdMarker The version ID of the object specified in key-marker after which the GetBucketVersions (ListObjectVersions) operation begins.
     * @param int|null $maxKeys The maximum number of objects to be returned.
     * @param string|null $prefix The prefix that the names of returned objects must contain.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $delimiter = null,
        ?string $encodingType = null,
        ?string $keyMarker = null,
        ?string $versionIdMarker = null,
        ?int $maxKeys = null,
        ?string $prefix = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->delimiter = $delimiter;
        $this->encodingType = $encodingType;
        $this->keyMarker = $keyMarker;
        $this->versionIdMarker = $versionIdMarker;
        $this->maxKeys = $maxKeys;
        $this->prefix = $prefix;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
