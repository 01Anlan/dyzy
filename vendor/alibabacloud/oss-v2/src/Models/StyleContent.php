<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class StyleContent
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'StyleInfo')]
final class StyleContent extends Model
{
    /**
     * The content of the style.
     * @var string|null
     */
    #[XmlElement(rename: 'Content', type: 'string')]
    public ?string $content;

    /**
     * StyleInfo constructor.
     * @param string|null $content The content of the style.
     */
    public function __construct(
        ?string $content = null
    )
    {
        $this->content = $content;
    }
}