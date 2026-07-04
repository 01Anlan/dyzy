<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketArchiveDirectRead operation.
 * Class PutBucketArchiveDirectReadRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketArchiveDirectReadRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body.
     * @var ArchiveDirectReadConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ArchiveDirectReadConfiguration', type: 'xml')]
    public ?ArchiveDirectReadConfiguration $archiveDirectReadConfiguration;

    /**
     * PutBucketArchiveDirectReadRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param ArchiveDirectReadConfiguration|null $archiveDirectReadConfiguration The request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?ArchiveDirectReadConfiguration $archiveDirectReadConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->archiveDirectReadConfiguration = $archiveDirectReadConfiguration;
        parent::__construct($options);
    }
}