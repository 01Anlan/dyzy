<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the ListObjectVersions operation.
 * Class ListObjectVersionsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectVersionsResult extends ResultModel
{
    /**
     * The bucket name
     * @var string|null
     */
    public ?string $name;

    /**
     * The prefix contained in the names of the returned objects.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * Indicates the object from which the ListObjectVersions (GetBucketVersions) operation starts.
     * @var string|null
     */
    public ?string $keyMarker;

    /**
     * The version from which the ListObjectVersions (GetBucketVersions) operation starts. This parameter is used together with KeyMarker.
     * @var string|null
     */
    public ?string $versionIdMarker;

    /**
     * The maximum number of objects that can be returned in the response.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * The character that is used to group objects by name. The objects whose names contain the same string from the prefix to the next occurrence of the delimiter are grouped as a single result parameter in CommonPrefixes.
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * Indicates whether the returned results are truncated.- true: indicates that not all results are returned for the request.- false: indicates that all results are returned for the request.
     * @var bool|null
     */
    public ?bool $isTruncated;

    /**
     * If not all results are returned for the request, the NextKeyMarker parameter is included in the response to indicate the key-marker value of the next ListObjectVersions (GetBucketVersions) request.
     * @var string|null
     */
    public ?string $nextKeyMarker;

    /**
     * If not all results are returned for the request, the NextVersionIdMarker parameter is included in the response to indicate the version-id-marker value of the next ListObjectVersions (GetBucketVersions) request.
     * @var string|null
     */
    public ?string $nextVersionIdMarker;

    /**
     * The encoding type of the content in the response. If you specify encoding-type in the request, the values of Delimiter, Marker, Prefix, NextMarker, and Key are encoded in the response.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The container that stores the versions of objects except for delete markers.
     * @var array<ObjectVersionProperties>|null
     */
    public ?array $versions;

    /**
     * The container that stores delete markers.
     * @var array<DeleteMarkerProperties>|null
     */
    public ?array $deleteMarkers;

    /**
     * Objects whose names contain the same string that ranges from the prefix to the next occurrence of the delimiter are grouped as a single result element.
     * @var array<CommonPrefix>|null
     */
    public ?array $commonPrefixes;


    /**
     * ListObjectVersionsResult constructor.
     * @param string|null $name The bucket name.
     * @param string|null $prefix The prefix contained in the names of the returned objects.
     * @param string|null $keyMarker Indicates the object from which the ListObjectVersions (GetBucketVersions) operation starts.
     * @param string|null $versionIdMarker The version from which the ListObjectVersions (GetBucketVersions) operation starts.
     * @param int|null $maxKeys The maximum number of objects that can be returned in the response.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param bool|null $isTruncated Indicates whether the returned results are truncated.
     * @param string|null $encodingType The encoding type of the content in the response.
     * @param string|null $nextKeyMarker If not all results are returned for the request, the NextKeyMarker parameter is included in the response to indicate the key-marker value of the next ListObjectVersions (GetBucketVersions) request.
     * @param string|null $nextVersionIdMarker If not all results are returned for the request, the NextVersionIdMarker parameter is included in the response to indicate the version-id-marker value of the next ListObjectVersions (GetBucketVersions) request.
     * @param array<ObjectVersionProperties>|null $versions The container that stores the versions of objects except for delete markers.
     * @param array<DeleteMarkerProperties>|null $deleteMarkers The container that stores delete markers.
     * @param array<CommonPrefix>|null $commonPrefixes Objects whose names contain the same string that ranges from the prefix to the next occurrence of the delimiter are grouped as a single result element.
     */
    public function __construct(
        ?string $name = null,
        ?string $prefix = null,
        ?string $keyMarker = null,
        ?string $versionIdMarker = null,
        ?int $maxKeys = null,
        ?string $delimiter = null,
        ?bool $isTruncated = null,
        ?string $encodingType = null,
        ?string $nextKeyMarker = null,
        ?string $nextVersionIdMarker = null,
        ?array $versions = null,
        ?array $deleteMarkers = null,
        ?array $commonPrefixes = null
    )
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->keyMarker = $keyMarker;
        $this->versionIdMarker = $versionIdMarker;
        $this->maxKeys = $maxKeys;
        $this->delimiter = $delimiter;
        $this->isTruncated = $isTruncated;
        $this->encodingType = $encodingType;
        $this->nextKeyMarker = $nextKeyMarker;
        $this->nextVersionIdMarker = $nextVersionIdMarker;
        $this->versions = $versions;
        $this->deleteMarkers = $deleteMarkers;
        $this->commonPrefixes = $commonPrefixes;
    }
}
