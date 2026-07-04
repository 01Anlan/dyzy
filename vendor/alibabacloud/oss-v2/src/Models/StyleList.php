<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class StyleList
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'StyleList')]
final class StyleList extends Model
{
    /**
     * The list of styles.
     * @var array<StyleInfo>|null
     */
    #[XmlElement(rename: 'Style', type: StyleInfo::class)]
    public ?array $styles;

    /**
     * StyleList constructor.
     * @param array<StyleInfo>|null $styles The list of styles.
     */
    public function __construct(
        ?array $styles = null
    )
    {
        $this->styles = $styles;
    }
}