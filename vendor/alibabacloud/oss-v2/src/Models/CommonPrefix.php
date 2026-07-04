<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CommonPrefix
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CommonPrefix')]
final class CommonPrefix extends Model
{
    /**
     * The prefix that must be included in the names of objects you want to list.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * CommonPrefix constructor.
     * @param string|null $prefix The prefix that must be included in the names of objects you want to list.
     */
    public function __construct(
        ?string $prefix = null
    )
    {
        $this->prefix = $prefix;
    }
}
