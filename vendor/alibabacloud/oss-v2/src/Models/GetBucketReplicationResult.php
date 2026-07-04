<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketReplication operation.
 * Class GetBucketReplicationResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketReplicationResult extends ResultModel
{
    /**
     * The container that stores data replication configurations.
     * @var ReplicationConfiguration|null
     */
    #[TagBody(rename: 'ReplicationConfiguration', type: ReplicationConfiguration::class, format: 'xml')]
    public ?ReplicationConfiguration $replicationConfiguration;

    /**
     * GetBucketReplicationRequest constructor.
     * @param ReplicationConfiguration|null $replicationConfiguration The container that stores data replication configurations.
     */
    public function __construct(
        ?ReplicationConfiguration $replicationConfiguration = null
    )
    {
        $this->replicationConfiguration = $replicationConfiguration;
    }
}
