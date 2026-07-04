<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the DeleteMultipleObjects operation.
 * Class DeleteMultipleObjectsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteMultipleObjectsRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The encoding type of the object names in the response. Valid value: url
     * @var string|null
     */
    public ?string $encodingType;

    /**
     * The container that stores information about you want to delete objects.
     * @var array<DeleteObject>|null
     */
    public ?array $objects;

    /**
     * Specifies whether to enable the Quiet return mode.
     * The DeleteMultipleObjects operation provides the following return modes: Valid value: true,false
     * @var bool|null
     */
    public ?bool $quiet;

    /**
     * To indicate that the requester is aware that the request and data download will incur costs
     * @var string|null
     */
    public ?string $requestPayer;

    /**
     * The container that stores information about you want to delete objects.
     * @var Delete|null
     */
    public ?Delete $delete;

    /**
     * DeleteMultipleObjectsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param array<DeleteObject>|null $objects The container that stores information about you want to delete objects.
     * @param string|null $encodingType The encoding type of the object names in the response. Valid value: url
     * @param bool|null $quiet Specifies whether to enable the Quiet return mode.
     * @param string|null $requestPayer To indicate that the requester is aware that the request and data download will incur costs.
     * @param Delete|null $delete The container that stores information about you want to delete objects.
     *                            The delete parameter is functionally equivalent to specifying objects and quiet parameters separately.
     *                            It is recommended to use the delete parameter for unified configuration.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?array $objects = null,
        ?string $encodingType = null,
        ?bool $quiet = null,
        ?string $requestPayer = null,
        ?array $options = null,
        ?Delete $delete = null
    )
    {
        $this->bucket = $bucket;
        $this->objects = $objects;
        $this->encodingType = $encodingType;
        $this->quiet = $quiet;
        $this->requestPayer = $requestPayer;
        $this->delete = $delete;
        parent::__construct($options);
    }
}
