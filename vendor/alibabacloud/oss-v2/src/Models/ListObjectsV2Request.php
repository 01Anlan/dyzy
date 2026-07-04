<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListObjectsV2 operation.
 * Class ListObjectsV2Request
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListObjectsV2Request extends RequestModel
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
     * The encoding format of the returned objects in the response.  The values of Delimiter, StartAfter, Prefix, NextContinuationToken, and Key are UTF-8 encoded. If the value of Delimiter, StartAfter, Prefix, NextContinuationToken, or Key contains a control character that is not supported by Extensible Markup Language (XML) 1.0, you can specify encoding-type to encode the value in the response.
     * Sees EncodeType for supported values.
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The name of the object after which the list operation begins. If this parameter is specified, objects whose names are alphabetically after the value of start-after are returned.The objects are returned by page based on start-after. The value of start-after can be up to 1,024 bytes in length.If the value of start-after does not exist when you perform a conditional query, the list starts from the object whose name is alphabetically after the value of start-after.
     * @var string|null
     */
    public ?string $startAfter;

    /**
     * The token from which the list operation starts. You can obtain the token from NextContinuationToken in the response of the ListObjectsV2 request.
     * @var string|null
     */
    public ?string $continuationToken;

    /**
     * The maximum number of objects to be returned.Valid values: 1 to 999.Default value: 100.  If the number of returned objects exceeds the value of max-keys, the response contains NextContinuationToken.Use the value of NextContinuationToken as the value of continuation-token in the next request.
     * @var int|null
     */
    public ?int $maxKeys;

    /**
     * The prefix that must be contained in names of the returned objects.*   The value of prefix can be up to 1,024 bytes in length.*   If you specify prefix, the names of the returned objects contain the prefix.If you set prefix to a directory name, the objects whose names start with this prefix are listed. The objects consist of all objects and subdirectories in this directory.If you set prefix to a directory name and set delimiter to a forward slash (/), only the objects in the directory are listed. The subdirectories in the directory are returned in CommonPrefixes. Objects and subdirectories in the subdirectories are not listed.For example, a bucket contains the following three objects: fun/test.jpg, fun/movie/001.avi, and fun/movie/007.avi. If prefix is set to fun/, the three objects are returned. If prefix is set to fun/ and delimiter is set to a forward slash (/), fun/test.jpg and fun/movie/ are returned.
     * @var string|null
     */
    public ?string $prefix;


    /**
     * To indicate that the requester is aware that the request and data download will incur costs.
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * Specifies whether to include the information about the bucket owner in the response. Valid values:*   true*   false
     * @var bool|null
     */
    public ?bool $fetchOwner;

    /**
     * ListObjectsV2Request constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $delimiter The character that is used to group objects by name.
     * @param string|null $encodingType The encoding format of the returned objects in the response.
     * @param string|null $startAfter The name of the object after which the list operation begins.
     * @param string|null $continuationToken The token from which the list operation starts.
     * @param int|null $maxKeys The maximum number of objects to be returned.
     * @param string|null $prefix The prefix that must be contained in names of the returned objects.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param bool|null $fetchOwner Specifies whether to include the information about the bucket owner in the response.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $delimiter = null,
        ?string $encodingType = null,
        ?string $startAfter = null,
        ?string $continuationToken = null,
        ?int $maxKeys = null,
        ?string $prefix = null,
        ?string $requestPayer = null,
        ?bool $fetchOwner = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->delimiter = $delimiter;
        $this->encodingType = $encodingType;
        $this->startAfter = $startAfter;
        $this->continuationToken = $continuationToken;
        $this->maxKeys = $maxKeys;
        $this->prefix = $prefix;
        $this->requestPayer = $requestPayer;
        $this->fetchOwner = $fetchOwner;
        parent::__construct($options);
    }
}
