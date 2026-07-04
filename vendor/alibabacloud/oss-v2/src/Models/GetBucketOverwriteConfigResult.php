<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketOverwriteConfig operation.
 * Class GetBucketOverwriteConfigResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketOverwriteConfigResult extends ResultModel
{
    /**
     * Container for Saving Bucket Overwrite Rules
     * @var OverwriteConfiguration|null
     */
    #[TagBody(rename: 'OverwriteConfiguration', type: OverwriteConfiguration::class, format: 'xml')]
    public ?OverwriteConfiguration $overwriteConfiguration;

    /**
     * GetBucketOverwriteConfigRequest constructor.
     * @param OverwriteConfiguration|null $overwriteConfiguration Container for Saving Bucket Overwrite Rules
     */
    public function __construct(
        ?OverwriteConfiguration $overwriteConfiguration = null
    )
    {
        $this->overwriteConfiguration = $overwriteConfiguration;
    }
}