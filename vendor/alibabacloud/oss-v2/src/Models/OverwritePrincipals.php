<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Principals
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Principals')]
final class OverwritePrincipals extends Model
{
    /**
     * A collection of authorized entities. The usage is similar to the `Principal` element in a bucket policy. You can specify an Alibaba Cloud account, a RAM user, or a RAM role. If this element is empty or not configured, overwrites are prohibited for all objects that match the prefix and suffix conditions.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Principal', type: 'string')]
    public ?array $principals;

    /**
     * Principals constructor.
     * @param array<string>|null $principals A collection of authorized entities.
     */
    public function __construct(
        ?array $principals = null
    )
    {
        $this->principals = $principals;
    }
}