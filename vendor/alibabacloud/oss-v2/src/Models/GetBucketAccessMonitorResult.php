<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketAccessMonitor operation.
 * Class GetBucketAccessMonitorResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketAccessMonitorResult extends ResultModel
{
    /**
     * The container that stores access monitor configuration.
     * @var AccessMonitorConfiguration|null
     */
    #[TagBody(rename: 'AccessMonitorConfiguration', type: AccessMonitorConfiguration::class, format: 'xml')]
    public ?AccessMonitorConfiguration $accessMonitorConfiguration;

    /**
     * GetBucketAccessMonitorRequest constructor.
     * @param AccessMonitorConfiguration|null $accessMonitorConfiguration The container that stores access monitor configuration.
     */
    public function __construct(
        ?AccessMonitorConfiguration $accessMonitorConfiguration = null
    )
    {
        $this->accessMonitorConfiguration = $accessMonitorConfiguration;
    }
}
