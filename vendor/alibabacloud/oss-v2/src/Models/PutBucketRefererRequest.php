<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketReferer operation.
 * Class PutBucketRefererRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketRefererRequest extends RequestModel
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
     * @var RefererConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'RefererConfiguration', type: 'xml')]
    public ?RefererConfiguration $refererConfiguration;

    /**
     * PutBucketRefererRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param RefererConfiguration|null $refererConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?RefererConfiguration $refererConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->refererConfiguration = $refererConfiguration;
        parent::__construct($options);
    }
}