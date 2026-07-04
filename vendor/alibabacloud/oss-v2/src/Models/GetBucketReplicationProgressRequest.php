<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetBucketReplicationProgress operation.
 * Class GetBucketReplicationProgressRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketReplicationProgressRequest extends RequestModel
{
    /**
     * The name of the bucekt.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The ID of the data replication rule. You can call the GetBucketReplication operation to query the ID.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'rule-id', type: 'string')]
    public ?string $ruleId;

    /**
     * GetBucketReplicationProgressRequest constructor.
     * @param string|null $bucket The name of the bucekt.
     * @param string|null $ruleId The ID of the data replication rule.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $ruleId = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->ruleId = $ruleId;
        parent::__construct($options);
    }
}