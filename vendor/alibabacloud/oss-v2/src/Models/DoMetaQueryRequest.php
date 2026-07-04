<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the DoMetaQuery operation.
 * Class DoMetaQueryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DoMetaQueryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var MetaQuery|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'MetaQuery', type: 'xml')]
    public ?MetaQuery $metaQuery;

    /**
     * Specifies that MetaSearch is used to query objects.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'mode', type: 'string')]
    public ?string $mode;

    /**
     * DoMetaQueryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param MetaQuery|null $metaQuery The request body schema.
     * @param string|null $mode Specifies that MetaSearch is used to query objects.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?MetaQuery $metaQuery = null,
        ?string $mode = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->metaQuery = $metaQuery;
        $this->mode = $mode;
        parent::__construct($options);
    }
}