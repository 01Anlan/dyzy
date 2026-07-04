<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetMetaQueryStatus operation.
 * Class GetMetaQueryStatusResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetMetaQueryStatusResult extends ResultModel
{
    /**
     * The container that stores the metadata information.
     * @var MetaQueryStatus|null
     */
    #[TagBody(rename: 'MetaQueryStatus', type: MetaQueryStatus::class, format: 'xml')]
    public ?MetaQueryStatus $metaQueryStatus;

    /**
     * GetMetaQueryStatusRequest constructor.
     * @param MetaQueryStatus|null $metaQueryStatus The container that stores the metadata information.
     */
    public function __construct(
        ?MetaQueryStatus $metaQueryStatus = null
    )
    {
        $this->metaQueryStatus = $metaQueryStatus;
    }
}
