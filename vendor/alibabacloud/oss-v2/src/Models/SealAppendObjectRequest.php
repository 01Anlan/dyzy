<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the SealAppendObject operation.
 * Class SealAppendObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class SealAppendObjectRequest extends RequestModel
{
    /**
     * Bucket name
     * @var string|null
     */
    public ?string $bucket;

    /**
     * Name of the Appendable Object
     * @var string|null
     */
    public ?string $key;

    /**
     * Used to specify the expected length of the file when the user wants to seal it.
     * @var int|null
     */
    public ?int $position;

    /**
     * SealAppendObjectRequest constructor.
     * @param string|null $bucket Bucket name
     * @param string|null $key Name of the Appendable Object
     * @param int|null $position Used to specify the expected length of the file when the user wants to seal it.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $key = null,
        ?int $position = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->position = $position;
        parent::__construct($options);
    }
}