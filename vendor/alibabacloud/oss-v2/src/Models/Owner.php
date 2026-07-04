<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Owner
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Owner')]
final class Owner extends Model
{
    /**
     * The ID of the bucket owner.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * The name of the bucket owner. The name of the bucket owner is the same as the ID of the bucket owner.
     * @var string|null
     */
    #[XmlElement(rename: 'DisplayName', type: 'string')]
    public ?string $displayName;

    /**
     * Owner constructor.
     * @param string|null $id The ID of the bucket owner.
     * @param string|null $displayName The name of the bucket owner.
     */
    public function __construct(
        ?string $id = null,
        ?string $displayName = null
    )
    {
        $this->id = $id;
        $this->displayName = $displayName;
    }
}
