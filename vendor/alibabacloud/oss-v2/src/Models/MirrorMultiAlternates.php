<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorMultiAlternates
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorMultiAlternates')]
final class MirrorMultiAlternates extends Model
{   
    /**
     * The configuration list for multiple origins.
     * @var array<MirrorMultiAlternate>|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternate', type: MirrorMultiAlternate::class)]
    public ?array $mirrorMultiAlternates;

    /**
     * MirrorMultiAlternates constructor.
     * @param array<MirrorMultiAlternate>|null $mirrorMultiAlternates The configuration list for multiple origins.
     */
    public function __construct(
        ?array $mirrorMultiAlternates = null
    )
    {   
        $this->mirrorMultiAlternates = $mirrorMultiAlternates;
    }
}