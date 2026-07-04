<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class TagSet
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'TagSet')]
final class TagSet extends Model
{
    /**
     * The tags.
     * Summary of Tag
     * @var array<Tag>|null
     */
    #[XmlElement(rename: 'Tag', type: Tag::class)]
    public ?array $tags;

    /**
     * TagSet constructor.
     * @param array<Tag>|null $tags The tags.
     */
    public function __construct(
        ?array $tags = null
    )
    {
        $this->tags = $tags;
    }
}
