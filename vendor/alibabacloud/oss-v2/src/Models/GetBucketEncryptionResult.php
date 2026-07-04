<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketEncryption operation.
 * Class GetBucketEncryptionResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketEncryptionResult extends ResultModel
{
    /**
     * The container that stores server-side encryption rules.
     * @var ServerSideEncryptionRule|null
     */
    #[TagBody(rename: 'ServerSideEncryptionRule', type: ServerSideEncryptionRule::class, format: 'xml')]
    public ?ServerSideEncryptionRule $serverSideEncryptionRule;

    /**
     * GetBucketEncryptionRequest constructor.
     * @param ServerSideEncryptionRule|null $serverSideEncryptionRule The container that stores server-side encryption rules.
     */
    public function __construct(
        ?ServerSideEncryptionRule $serverSideEncryptionRule = null
    )
    {
        $this->serverSideEncryptionRule = $serverSideEncryptionRule;
    }
}
