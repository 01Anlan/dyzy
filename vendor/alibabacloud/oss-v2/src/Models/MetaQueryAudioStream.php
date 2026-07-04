<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryAudioStream
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MetaQueryRespAudioStream')]
final class MetaQueryAudioStream extends Model
{
    /**
     * The duration of the video stream.
     * @var float|null
     */
    #[XmlElement(rename: 'Duration', type: 'float')]
    public ?float $duration;

    /**
     * The number of sound channels.
     * @var int|null
     */
    #[XmlElement(rename: 'Channels', type: 'int')]
    public ?int $channels;

    /**
     * The language used in the audio stream. The value follows the BCP 47 format.
     * @var string|null
     */
    #[XmlElement(rename: 'Language', type: 'string')]
    public ?string $language;

    /**
     * The abbreviated name of the codec.
     * @var string|null
     */
    #[XmlElement(rename: 'CodecName', type: 'string')]
    public ?string $codecName;

    /**
     * The bitrate. Unit: bit/s.
     * @var int|null
     */
    #[XmlElement(rename: 'Bitrate', type: 'int')]
    public ?int $bitrate;

    /**
     * The sampling rate.
     * @var int|null
     */
    #[XmlElement(rename: 'SampleRate', type: 'int')]
    public ?int $sampleRate;

    /**
     * The start time of the video stream.
     * @var float|null
     */
    #[XmlElement(rename: 'StartTime', type: 'float')]
    public ?float $startTime;

    /**
     * MetaQueryAudioStream constructor.
     * @param float|null $duration
     * @param int|null $channels
     * @param string|null $language
     * @param string|null $codecName
     * @param int|null $bitrate
     * @param int|null $sampleRate
     * @param float|null $startTime
     */
    public function __construct(
        ?float $duration = null,
        ?int $channels = null,
        ?string $language = null,
        ?string $codecName = null,
        ?int $bitrate = null,
        ?int $sampleRate = null,
        ?float $startTime = null
    )
    {
        $this->duration = $duration;
        $this->channels = $channels;
        $this->language = $language;
        $this->codecName = $codecName;
        $this->bitrate = $bitrate;
        $this->sampleRate = $sampleRate;
        $this->startTime = $startTime;
    }
}