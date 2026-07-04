<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQuerySubtitle
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MetaQuerySubtitle')]
final class MetaQuerySubtitle extends Model
{
    /**
     * The abbreviated name of the codec.
     * @var string|null
     */
    #[XmlElement(rename: 'CodecName', type: 'string')]
    public ?string $codecName;

    /**
     * The language of the subtitle. The value follows the BCP 47 format.
     * @var string|null
     */
    #[XmlElement(rename: 'Language', type: 'string')]
    public ?string $language;

    /**
     * The start time of the subtitle stream in seconds.
     * @var float|null
     */
    #[XmlElement(rename: 'StartTime', type: 'float')]
    public ?float $startTime;

    /**
     * The duration of the subtitle stream in seconds.
     * @var float|null
     */
    #[XmlElement(rename: 'Duration', type: 'float')]
    public ?float $duration;

    /**
     * MetaQuerySubtitle constructor.
     * @param string|null $codecName The abbreviated name of the codec.
     * @param string|null $language The language of the subtitle.
     * @param float|null $startTime The start time of the subtitle stream in seconds.
     * @param float|null $duration The duration of the subtitle stream in seconds.
     */
    public function __construct(
        ?string $codecName = null,
        ?string $language = null,
        ?float $startTime = null,
        ?float $duration = null
    )
    {
        $this->codecName = $codecName;
        $this->language = $language;
        $this->startTime = $startTime;
        $this->duration = $duration;
    }
}