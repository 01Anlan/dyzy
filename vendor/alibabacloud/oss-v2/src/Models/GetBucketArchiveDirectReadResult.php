<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketArchiveDirectRead operation.
 * Class GetBucketArchiveDirectReadResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketArchiveDirectReadResult extends ResultModel
{

    /**
     * The container that stores the configurations for real-time access of Archive objects.
     * @var ArchiveDirectReadConfiguration|null
     */
    #[TagBody(rename: 'ArchiveDirectReadConfiguration', type: ArchiveDirectReadConfiguration::class, format: 'xml')]
    public ?ArchiveDirectReadConfiguration $archiveDirectReadConfiguration;

    /**
     * GetBucketArchiveDirectReadRequest constructor.
     * @param ArchiveDirectReadConfiguration|null $archiveDirectReadConfiguration The container that stores the configurations for real-time access of Archive objects.
     */
    public function __construct(
        ?ArchiveDirectReadConfiguration $archiveDirectReadConfiguration = null
    )
    {
        $this->archiveDirectReadConfiguration = $archiveDirectReadConfiguration;
    }
}
