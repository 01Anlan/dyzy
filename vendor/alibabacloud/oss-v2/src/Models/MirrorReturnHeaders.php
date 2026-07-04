<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorReturnHeaders
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorReturnHeaders')]
final class MirrorReturnHeaders extends Model
{   
    /**
     * The rule list for setting response headers in mirror-based back-to-origin.
     * @var array<ReturnHeader>|null
     */
    #[XmlElement(rename: 'ReturnHeader', type: ReturnHeader::class)]
    public ?array $returnHeaders;

    /**
     * MirrorReturnHeaders constructor.
     * @param array<ReturnHeader>|null $returnHeaders The rule list for setting response headers in mirror-based back-to-origin.
     */
    public function __construct(
        ?array $returnHeaders = null
    )
    {   
        $this->returnHeaders = $returnHeaders;
    }
}