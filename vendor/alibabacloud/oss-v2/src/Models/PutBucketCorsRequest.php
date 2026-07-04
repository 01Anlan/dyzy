<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketCors operation.
 * Class PutBucketCorsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketCorsRequest extends RequestModel
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
     * @var CORSConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'CORSConfiguration', type: 'xml')]
    public ?CORSConfiguration $corsConfiguration;

    /**
     * PutBucketCorsRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param CORSConfiguration|null $corsConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?CORSConfiguration $corsConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->corsConfiguration = $corsConfiguration;
        parent::__construct($options);
    }
}