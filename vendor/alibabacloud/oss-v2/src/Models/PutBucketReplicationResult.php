<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;

/**
 * The result for the PutBucketReplication operation.
 * Class PutBucketReplicationResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketReplicationResult extends ResultModel
{
    /**
     * @var string|null
     */
    #[TagHeader(rename: 'x-oss-replication-rule-id', type: 'string')]
    public ?string $replicationRuleId;

    /**
     * PutBucketReplicationRequest constructor.
     * @param string|null $replicationRuleId
     */
    public function __construct(
        ?string $replicationRuleId = null
    )
    {
        $this->replicationRuleId = $replicationRuleId;
    }
}
