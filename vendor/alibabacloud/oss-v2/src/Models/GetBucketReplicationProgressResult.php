<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketReplicationProgress operation.
 * Class GetBucketReplicationProgressResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketReplicationProgressResult extends ResultModel
{
    /**
     * The container that is used to store the progress of data replication tasks.
     * @var ReplicationProgress|null
     */
    #[TagBody(rename: 'ReplicationProgress', type: ReplicationProgress::class, format: 'xml')]
    public ?ReplicationProgress $replicationProgress;

    /**
     * GetBucketReplicationProgressRequest constructor.
     * @param ReplicationProgress|null $replicationProgress The container that is used to store the progress of data replication tasks.
     */
    public function __construct(
        ?ReplicationProgress $replicationProgress = null
    )
    {
        $this->replicationProgress = $replicationProgress;
    }
}
