<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutCname operation.
 * Class PutCnameRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutCnameRequest extends RequestModel
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
     * @var BucketCnameConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'BucketCnameConfiguration', type: 'xml')]
    public ?BucketCnameConfiguration $bucketCnameConfiguration;

    /**
     * PutCnameRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param BucketCnameConfiguration|null $bucketCnameConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?BucketCnameConfiguration $bucketCnameConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->bucketCnameConfiguration = $bucketCnameConfiguration;
        parent::__construct($options);
    }
}