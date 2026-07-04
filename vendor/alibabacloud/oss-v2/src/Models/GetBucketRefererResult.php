<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketReferer operation.
 * Class GetBucketRefererResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketRefererResult extends ResultModel
{

    /**
     * The container that stores the hotlink protection configurations.
     * @var RefererConfiguration|null
     */
    #[TagBody(rename: 'RefererConfiguration', type: RefererConfiguration::class, format: 'xml')]
    public ?RefererConfiguration $refererConfiguration;

    /**
     * GetBucketRefererRequest constructor.
     * @param RefererConfiguration|null $refererConfiguration The container that stores the hotlink protection configurations.
     */
    public function __construct(
        ?RefererConfiguration $refererConfiguration = null
    )
    {
        $this->refererConfiguration = $refererConfiguration;
    }
}
