<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the ListParts operation.
 * Class ListPartsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListPartsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The name of the object.
     * @var string|null
     */
    public ?string $key;

    /**
     * The ID of the multipart upload task.By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $uploadId;

    /**
     * The encoding type of the content in the response. Valid value: url
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The position from which the list starts. All parts whose part numbers are greater than the value of this parameter are listed.By default, this parameter is left empty.
     * @var string|null
     */
    public ?string $partNumberMarker;

    /**
     * The maximum number of parts that can be returned by OSS.Default value: 1000.Maximum value: 1000.
     * @var int|null
     */
    public ?int $maxParts;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * ListPartsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The name of the object.
     * @param string|null $uploadId The ID of the multipart upload task.
     * @param string|null $encodingType The encoding type of the content in the response.
     * @param string|null $partNumberMarker The position from which the list starts.
     * @param int|null $maxParts The maximum number of parts that can be returned by OSS.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?string $uploadId = null,
        ?string $encodingType = null,
        ?string $partNumberMarker = null,
        ?int $maxParts = null,
        ?string $requestPayer = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->encodingType = $encodingType;
        $this->partNumberMarker = $partNumberMarker;
        $this->maxParts = $maxParts;
        $this->requestPayer = $requestPayer;
        parent::__construct($options);
    }
}
