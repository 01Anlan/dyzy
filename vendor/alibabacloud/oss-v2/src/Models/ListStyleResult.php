<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the ListStyle operation.
 * Class ListStyleResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListStyleResult extends ResultModel
{
    /**
     * The container that was used to query the information about image styles.
     * @var StyleList|null
     */
    #[TagBody(rename: 'StyleList', type: StyleList::class, format: 'xml')]
    public ?StyleList $styleList;

    /**
     * ListStyleRequest constructor.
     * @param StyleList|null $styleList The container that was used to query the information about image styles.
     */
    public function __construct(
        ?StyleList $styleList = null
    )
    {
        $this->styleList = $styleList;
    }
}
