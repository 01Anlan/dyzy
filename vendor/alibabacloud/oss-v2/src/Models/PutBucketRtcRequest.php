<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketRtc operation.
 * Class PutBucketRtcRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketRtcRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The container of the request body.
     * @var RtcConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ReplicationRule', type: 'xml')]
    public ?RtcConfiguration $rtcConfiguration;

    /**
     * PutBucketRtcRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param RtcConfiguration|null $rtcConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?RtcConfiguration $rtcConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->rtcConfiguration = $rtcConfiguration;
        parent::__construct($options);
    }
}