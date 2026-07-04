<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\TagBody;
use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the list cloud boxes operation.
 * Class ListCloudBoxedResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListCloudBoxesResult extends ResultModel
{
    /**
     * The container that stores information about cloud box bucket.
     * @var ListCloudBoxes|null
     */
    #[TagBody(rename: 'ListCloudBoxResult', type: ListCloudBoxes::class, format: 'xml')]
    public ?ListCloudBoxes $listCloudBoxes;

    /**
     * ListCloudBoxesResult constructor.
     * @param ListCloudBoxes|null $listCloudBoxes The container that stores information about cloud box bucket.
     */
    public function __construct(
        ?ListCloudBoxes $listCloudBoxes = null
    )
    {
        $this->listCloudBoxes = $listCloudBoxes;
    }
}
