<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Tagging
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Tagging')]
final class Tagging extends Model
{
    /**
     * The tag set of the target object.
     * @var TagSet|null
     */
    #[XmlElement(rename: 'TagSet', type: TagSet::class)]
    public ?TagSet $tagSet;

    /**
     * Tagging constructor.
     * @param TagSet|null $tagSet The tag set of the target object.
     */
    public function __construct(
        ?TagSet $tagSet = null
    )
    {
        $this->tagSet = $tagSet;
    }
}
