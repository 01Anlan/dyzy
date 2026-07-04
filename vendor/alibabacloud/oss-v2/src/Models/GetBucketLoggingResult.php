<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketLogging operation.
 * Class GetBucketLoggingResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketLoggingResult extends ResultModel
{
    /**
     * Indicates the container used to store access logging configuration of a bucket.
     * @var BucketLoggingStatus|null
     */
    #[TagBody(rename: 'BucketLoggingStatus', type: BucketLoggingStatus::class, format: 'xml')]
    public ?BucketLoggingStatus $bucketLoggingStatus;

    /**
     * GetBucketLoggingRequest constructor.
     * @param BucketLoggingStatus|null $bucketLoggingStatus Indicates the container used to store access logging configuration of a bucket.
     */
    public function __construct(
        ?BucketLoggingStatus $bucketLoggingStatus = null
    )
    {
        $this->bucketLoggingStatus = $bucketLoggingStatus;
    }
}
