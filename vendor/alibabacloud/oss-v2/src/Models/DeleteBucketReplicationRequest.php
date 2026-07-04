<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the DeleteBucketReplication operation.
 * Class DeleteBucketReplicationRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeleteBucketReplicationRequest extends RequestModel
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
     * @var ReplicationRules|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ReplicationRules', type: 'xml')]
    public ?ReplicationRules $replicationRules;

    /**
     * DeleteBucketReplicationRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param ReplicationRules|null $replicationRules The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?ReplicationRules $replicationRules = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->replicationRules = $replicationRules;
        parent::__construct($options);
    }
}