<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketAccessMonitor operation.
 * Class PutBucketAccessMonitorRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketAccessMonitorRequest extends RequestModel
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
     * @var AccessMonitorConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'AccessMonitorConfiguration', type: 'xml')]
    public ?AccessMonitorConfiguration $accessMonitorConfiguration;

    /**
     * PutBucketAccessMonitorRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param AccessMonitorConfiguration|null $accessMonitorConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?AccessMonitorConfiguration $accessMonitorConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->accessMonitorConfiguration = $accessMonitorConfiguration;
        parent::__construct($options);
    }
}