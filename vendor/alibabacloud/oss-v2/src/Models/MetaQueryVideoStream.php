<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryVideoStream
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'VideoStream')]
final class MetaQueryVideoStream extends Model
{
    /**
     * The duration of the audio stream in seconds.
     * @var float|null
     */
    #[XmlElement(rename: 'Duration', type: 'float')]
    public ?float $duration;

    /**
     * The number of video frames.
     * @var int|null
     */
    #[XmlElement(rename: 'FrameCount', type: 'int')]
    public ?int $frameCount;

    /**
     * The bit depth.
     * @var int|null
     */
    #[XmlElement(rename: 'BitDepth', type: 'int')]
    public ?int $bitDepth;

    /**
     * The pixel format of the video stream.
     * @var string|null
     */
    #[XmlElement(rename: 'PixelFormat', type: 'string')]
    public ?string $pixelFormat;

    /**
     * The color space.
     * @var string|null
     */
    #[XmlElement(rename: 'ColorSpace', type: 'string')]
    public ?string $colorSpace;

    /**
     * The image height of the video stream. Unit: pixel.
     * @var int|null
     */
    #[XmlElement(rename: 'Height', type: 'int')]
    public ?int $height;

    /**
     * The bitrate. Unit: bit/s.
     * @var int|null
     */
    #[XmlElement(rename: 'Bitrate', type: 'int')]
    public ?int $bitrate;

    /**
     * The frame rate of the video stream.
     * @var string|null
     */
    #[XmlElement(rename: 'FrameRate', type: 'string')]
    public ?string $frameRate;

    /**
     * The start time of the audio stream in seconds.
     * @var float|null
     */
    #[XmlElement(rename: 'StartTime', type: 'float')]
    public ?float $startTime;

    /**
     * The image width of the video stream. Unit: pixels.
     * @var int|null
     */
    #[XmlElement(rename: 'Width', type: 'int')]
    public ?int $width;

    /**
     * The abbreviated name of the codec.
     * @var string|null
     */
    #[XmlElement(rename: 'CodecName', type: 'string')]
    public ?string $codecName;

    /**
     * The language used in the audio stream. The value follows the BCP 47 format.
     * @var string|null
     */
    #[XmlElement(rename: 'Language', type: 'string')]
    public ?string $language;

    /**
     * MetaQueryVideoStream constructor.
     * @param float|null $duration
     * @param int|null $frameCount The number of video frames.
     * @param int|null $bitDepth The bit depth.
     * @param string|null $pixelFormat The pixel format of the video stream.
     * @param string|null $colorSpace The color space.
     * @param int|null $height The image height of the video stream.
     * @param int|null $bitrate  The bitrate.
     * @param string|null $frameRate The frame rate of the video stream.
     * @param float|null $startTime
     * @param int|null $width The image width of the video stream.
     * @param string|null $codecName The abbreviated name of the codec.
     * @param string|null $language The language used in the audio stream.
     */
    public function __construct(
        ?float $duration = null,
        ?int $frameCount = null,
        ?int $bitDepth = null,
        ?string $pixelFormat = null,
        ?string $colorSpace = null,
        ?int $height = null,
        ?int $bitrate = null,
        ?string $frameRate = null,
        ?float $startTime = null,
        ?int $width = null,
        ?string $codecName = null,
        ?string $language = null
    )
    {
        $this->duration = $duration;
        $this->frameCount = $frameCount;
        $this->bitDepth = $bitDepth;
        $this->pixelFormat = $pixelFormat;
        $this->colorSpace = $colorSpace;
        $this->height = $height;
        $this->bitrate = $bitrate;
        $this->frameRate = $frameRate;
        $this->startTime = $startTime;
        $this->width = $width;
        $this->codecName = $codecName;
        $this->language = $language;
    }
}