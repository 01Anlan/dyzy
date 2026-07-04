<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketTransferAcceleration operation.
 * Class PutBucketTransferAccelerationRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketTransferAccelerationRequest extends RequestModel
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
     * @var TransferAccelerationConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'TransferAccelerationConfiguration', type: 'xml')]
    public ?TransferAccelerationConfiguration $transferAccelerationConfiguration;

    /**
     * PutBucketTransferAccelerationRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param TransferAccelerationConfiguration|null $transferAccelerationConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?TransferAccelerationConfiguration $transferAccelerationConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->transferAccelerationConfiguration = $transferAccelerationConfiguration;
        parent::__construct($options);
    }
}