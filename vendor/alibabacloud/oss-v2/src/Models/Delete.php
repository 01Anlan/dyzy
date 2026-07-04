<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * Class Delete
 * @package AlibabaCloud\Oss\V2\Models
 */
final class Delete extends RequestModel
{
    /**
     * The container that stores information about you want to delete objects.
     * @var array<ObjectIdentifier>|null
     */
    public ?array $objects;

    /**
     * Specifies whether to enable the Quiet return mode.
     * The DeleteMultipleObjects operation provides the following return modes: Valid value: true,false
     * @var bool|null
     */
    public ?bool $quiet;

    /**
     * DeleteMultipleObjectsRequest constructor.
     * @param array<ObjectIdentifier>|null $objects The container that stores information about you want to delete objects.
     * @param bool|null $quiet Specifies whether to enable the Quiet return mode.
     * @param array|null $options
     */
    public function __construct(
        ?array $objects = null,
        ?bool $quiet = null,
        ?array $options = null
    )
    {
        $this->objects = $objects;
        $this->quiet = $quiet;
        parent::__construct($options);
    }
}
