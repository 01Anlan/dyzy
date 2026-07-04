<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketReplication operation.
 * Class PutBucketReplicationRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketReplicationRequest extends RequestModel
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
     * @var ReplicationConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ReplicationConfiguration', type: 'xml')]
    public ?ReplicationConfiguration $replicationConfiguration;

    /**
     * PutBucketReplicationRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param ReplicationConfiguration|null $replicationConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?ReplicationConfiguration $replicationConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->replicationConfiguration = $replicationConfiguration;
        parent::__construct($options);
    }
}