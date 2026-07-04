<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAudioStreams
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AudioStreams')]
final class MetaQueryAudioStreams extends Model
{
    /**
     * @var array<MetaQueryAudioStream>|null
     */
    #[XmlElement(rename: 'AudioStream', type: MetaQueryAudioStream::class)]
    public ?array $audioStream;

    /**
     * AudioStreams constructor.
     * @param array|null $audioStream
     */
    public function __construct(
        ?array $audioStream = null
    )
    {
        $this->audioStream = $audioStream;
    }
}