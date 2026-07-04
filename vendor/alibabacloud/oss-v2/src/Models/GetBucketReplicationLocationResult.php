<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketReplicationLocation operation.
 * Class GetBucketReplicationLocationResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketReplicationLocationResult extends ResultModel
{
    /**
     * The container that stores the region in which the destination bucket can be located.
     * @var ReplicationLocation|null
     */
    #[TagBody(rename: 'ReplicationLocation', type: ReplicationLocation::class, format: 'xml')]
    public ?ReplicationLocation $replicationLocation;

    /**
     * GetBucketReplicationLocationRequest constructor.
     * @param ReplicationLocation|null $replicationLocation The container that stores the region in which the destination bucket can be located.
     */
    public function __construct(
        ?ReplicationLocation $replicationLocation = null
    )
    {
        $this->replicationLocation = $replicationLocation;
    }
}
