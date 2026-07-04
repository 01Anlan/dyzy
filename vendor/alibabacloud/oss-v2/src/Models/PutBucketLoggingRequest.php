<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketLogging operation.
 * Class PutBucketLoggingRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketLoggingRequest extends RequestModel
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
     * @var BucketLoggingStatus|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'BucketLoggingStatus', type: 'xml')]
    public ?BucketLoggingStatus $bucketLoggingStatus;

    /**
     * PutBucketLoggingRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param BucketLoggingStatus|null $bucketLoggingStatus The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?BucketLoggingStatus $bucketLoggingStatus = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->bucketLoggingStatus = $bucketLoggingStatus;
        parent::__construct($options);
    }
}