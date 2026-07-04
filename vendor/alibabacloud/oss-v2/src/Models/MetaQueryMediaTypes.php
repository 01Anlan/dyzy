<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MediaTypes
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MediaTypes')]
final class MetaQueryMediaTypes extends Model
{
    /**
     * The type of multimedia that you want to query. Valid values: image, video, audio, document
     * @var string|null
     */
    #[XmlElement(rename: 'MediaType', type: 'string')]
    public ?string $mediaType;

    /**
     * MetaQuery constructor.
     * @param string|null $mediaType The type of multimedia that you want to query.
     */
    public function __construct(
        ?string $mediaType = null
    )
    {
        $this->mediaType = $mediaType;
    }
}