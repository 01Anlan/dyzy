<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketTransferAcceleration operation.
 * Class GetBucketTransferAccelerationResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketTransferAccelerationResult extends ResultModel
{

    /**
     * The container that stores the transfer acceleration configurations.
     * @var TransferAccelerationConfiguration|null
     */
    #[TagBody(rename: 'TransferAccelerationConfiguration', type: TransferAccelerationConfiguration::class, format: 'xml')]
    public ?TransferAccelerationConfiguration $transferAccelerationConfiguration;

    /**
     * GetBucketTransferAccelerationRequest constructor.
     * @param TransferAccelerationConfiguration|null $transferAccelerationConfiguration The container that stores the transfer acceleration configurations.
     */
    public function __construct(
        ?TransferAccelerationConfiguration $transferAccelerationConfiguration = null
    )
    {
        $this->transferAccelerationConfiguration = $transferAccelerationConfiguration;
    }
}
