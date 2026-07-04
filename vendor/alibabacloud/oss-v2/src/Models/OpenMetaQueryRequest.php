<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the OpenMetaQuery operation.
 * Class OpenMetaQueryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class OpenMetaQueryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * Specifies that MetaSearch is used to query objects.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'mode', type: 'string')]
    public ?string $mode;

    /**
     * OpenMetaQueryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $mode Specifies that MetaSearch is used to query objects.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $mode = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->mode = $mode;
        parent::__construct($options);
    }
}