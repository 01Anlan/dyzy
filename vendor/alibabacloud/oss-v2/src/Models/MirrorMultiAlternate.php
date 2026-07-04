<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorMultiAlternate
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorMultiAlternate')]
final class MirrorMultiAlternate extends Model
{
    /**
     * The distinct number of a specific origin.
     * @var int|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternateNumber', type: 'int')]
    public ?int $mirrorMultiAlternateNumber;

    /**
     * The URL for a specific origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternateURL', type: 'string')]
    public ?string $mirrorMultiAlternateURL;

    /**
     * The VPC ID for a specific origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternateVpcId', type: 'string')]
    public ?string $mirrorMultiAlternateVpcId;

    /**
     * The region for a specific origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternateDstRegion', type: 'string')]
    public ?string $mirrorMultiAlternateDstRegion;

    /**
     * MirrorMultiAlternate constructor.
     * @param int|null $mirrorMultiAlternateNumber The distinct number of a specific origin.
     * @param string|null $mirrorMultiAlternateURL The URL for a specific origin.
     * @param string|null $mirrorMultiAlternateVpcId The VPC ID for a specific origin.
     * @param string|null $mirrorMultiAlternateDstRegion The region for a specific origin.
     */
    public function __construct(
        ?int $mirrorMultiAlternateNumber = null,
        ?string $mirrorMultiAlternateURL = null,
        ?string $mirrorMultiAlternateVpcId = null,
        ?string $mirrorMultiAlternateDstRegion = null
    )
    {
        $this->mirrorMultiAlternateNumber = $mirrorMultiAlternateNumber;
        $this->mirrorMultiAlternateURL = $mirrorMultiAlternateURL;
        $this->mirrorMultiAlternateVpcId = $mirrorMultiAlternateVpcId;
        $this->mirrorMultiAlternateDstRegion = $mirrorMultiAlternateDstRegion;
    }
}