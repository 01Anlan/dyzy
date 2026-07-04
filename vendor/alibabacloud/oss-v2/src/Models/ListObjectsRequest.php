<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListObjects operation.
 * Class ListObjectsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The character that is used to group objects by name. If you specify delimiter in the request, the response contains CommonPrefixes. The objects whose names contain the same string from the prefix to the next occurrence of the delimiter are grouped as a single result element in CommonPrefixes.
     * @var string|null
     */
    public ?string $delimiter;

    /**
     * The encoding format of the content in the response.  The value of Delimiter, Marker, Prefix, NextMarker, and Key are UTF-8 encoded. If the values of Delimiter, Marker, Prefix, NextMarker, and Key contain a control character that is not supported by Extensible Markup Language (XML) 1.0, you can specify encoding-type to encode the value in the response.
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The name of the object after which the GetBucket (ListObjects) operation begins. If this parameter is specified, objects whose names are alphabetically after the value of marker are returned.The objects are returned by page based on marker. The value of marker can be up to 1,024 bytes.If the value of marker does not exist in the list when you perform a conditional query, the GetBucket (ListObjects) operation starts from the object whose name is alphabetically after the value of marker.
     * @var string|null
     */
    public ?string $marker;

    /**
     * The maximum number of objects that can be returned. If the number of objects to be returned exceeds the value of max-keys specified in the request, NextMarker is included in the returned response. The value of NextMarker is used as the value of marker for the next request.Valid values: 1 to 999.Default value: 100.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * The prefix that must be contained in names of the returned objects.*   The value of prefix can be up to 1,024 bytes in length.*   If you specify prefix, the names of the returned objects contain the prefix.If you set prefix to a directory name, the object whose names start with this prefix are listed. The objects consist of all recursive objects and subdirectories in this directory.If you set prefix to a directory name and set delimiter to a forward slash (/), only the objects in the directory are listed. The subdirectories in the directory are listed in CommonPrefixes. Recursive objects and subdirectories in the subdirectories are not listed.For example, a bucket contains the following three objects: fun/test.jpg, fun/movie/001.avi, and fun/movie/007.avi. If prefix is set to fun/, the three objects are returned. If prefix is set to fun/ and delimiter is set to a forward slash (/), fun/test.jpg and fun/movie/ are returned.
     * @var string|null
     */
    public ?string $prefix;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;


    /**
     * ListObjectsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param string|null $encodingType The encoding format of the content in the response.
     * @param string|null $marker The name of the object after which the GetBucket (ListObjects) operation begins.
     * @param int|null $maxKeys The maximum number of objects that can be returned.
     * @param string|null $prefix The prefix that must be contained in names of the returned objects.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $delimiter = null,
        ?string $encodingType = null,
        ?string $marker = null,
        ?int $maxKeys = null,
        ?string $prefix = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->delimiter = $delimiter;
        $this->encodingType = $encodingType;
        $this->marker = $marker;
        $this->maxKeys = $maxKeys;
        $this->prefix = $prefix;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
