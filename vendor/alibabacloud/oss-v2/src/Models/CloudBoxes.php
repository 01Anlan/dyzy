<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CloudBoxes
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CloudBoxes')]
final class CloudBoxes extends Model
{
    /**
     * The container that stores information about cloud box bucket.
     * @var array<CloudBox>|null
     */
    #[XmlElement(rename: 'CloudBox', type: CloudBox::class)]
    public ?array $cloudBox;

    /**
     * CloudBoxes constructor.
     * @param array|null $cloudBox The container that stores information about cloud box bucket.
     */
    public function __construct(
        ?array $cloudBox = null
    )
    {
        $this->cloudBox = $cloudBox;
    }
}
