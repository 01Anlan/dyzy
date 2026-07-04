<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class PolicyStatus
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'PolicyStatus')]
final class PolicyStatus extends Model
{
    /**
     * Indicates whether the current bucket policy allows public access.truefalse
     * @var bool|null
     */
    #[XmlElement(rename: 'IsPublic', type: 'bool')]
    public ?bool $isPublic;

    /**
     * PolicyStatus constructor.
     * @param bool|null $isPublic Indicates whether the current bucket policy allows public access.
     */
    public function __construct(
        ?bool $isPublic = null
    )
    {
        $this->isPublic = $isPublic;
    }
}