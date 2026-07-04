<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RefererBlacklist
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RefererBlacklist')]
final class RefererBlacklist extends Model
{
    /**
     * The addresses in the Referer blacklist.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Referer', type: 'string')]
    public ?array $referers;

    /**
     * RefererBlacklist constructor.
     * @param array<string>|null $referers The addresses in the Referer blacklist.
     */
    public function __construct(
        ?array $referers = null
    )
    {
        $this->referers = $referers;
    }
}