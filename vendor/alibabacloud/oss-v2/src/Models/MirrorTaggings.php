<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorTaggings
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorTaggings')]
final class MirrorTaggings extends Model
{
    /**
     * The rule list for setting tags.
     * @var array<MirrorTagging>|null
     */
    #[XmlElement(rename: 'Taggings', type: MirrorTagging::class)]
    public ?array $taggings;

    /**
     * MirrorTaggings constructor.
     * @param array<MirrorTagging>|null $taggings The rule list for setting tags.
     */
    public function __construct(
        ?array $taggings = null
    )
    {
        $this->taggings = $taggings;
    }
}