<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetStyle operation.
 * Class GetStyleResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetStyleResult extends ResultModel
{
    /**
     * The container that stores the information about the image style.
     * @var StyleInfo|null
     */
    #[TagBody(rename: 'Style', type: StyleInfo::class, format: 'xml')]
    public ?StyleInfo $style;

    /**
     * GetStyleRequest constructor.
     * @param StyleInfo|null $style The container that stores the information about the image style.
     */
    public function __construct(
        ?StyleInfo $style = null
    )
    {
        $this->style = $style;
    }
}
