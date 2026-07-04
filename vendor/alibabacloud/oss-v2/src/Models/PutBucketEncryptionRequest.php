<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketEncryption operation.
 * Class PutBucketEncryptionRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketEncryptionRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var ServerSideEncryptionRule|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ServerSideEncryptionRule', type: 'xml')]
    public ?ServerSideEncryptionRule $serverSideEncryptionRule;

    /**
     * PutBucketEncryptionRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param ServerSideEncryptionRule|null $serverSideEncryptionRule The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?ServerSideEncryptionRule $serverSideEncryptionRule = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->serverSideEncryptionRule = $serverSideEncryptionRule;
        parent::__construct($options);
    }
}