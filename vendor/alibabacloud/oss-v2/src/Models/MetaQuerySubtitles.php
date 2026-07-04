<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQuerySubtitles
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Subtitles')]
final class MetaQuerySubtitles extends Model
{
    /**
     * @var array<MetaQuerySubtitle>|null
     */
    #[XmlElement(rename: 'Subtitle', type: MetaQuerySubtitle::class)]
    public ?array $subtitle;

    /**
     * MetaQuerySubtitles constructor.
     * @param array|null $subtitle
     */
    public function __construct(
        ?array $subtitle = null
    )
    {
        $this->subtitle = $subtitle;
    }
}