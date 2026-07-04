<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryVideoStreams
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'VideoStreams')]
final class MetaQueryVideoStreams extends Model
{
    /**
     * @var array<MetaQueryVideoStream>|null
     */
    #[XmlElement(rename: 'VideoStream', type: MetaQueryVideoStream::class)]
    public ?array $videoStream;

    /**
     * MetaQueryVideoStreams constructor.
     * @param array|null $videoStream
     */
    public function __construct(
        ?array $videoStream = null
    )
    {
        $this->videoStream = $videoStream;
    }
}