<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class AccessControlList
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AccessControlList')]
final class AccessControlList extends Model
{
    /**
     * The ACL of the bucket or object.
     * Sees ObjectACLType or BucketACLType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Grant', type: 'string')]
    public ?string $grant;

    /**
     * AccessControlList constructor.
     * @param string|null $grant The ACL of the bucket or object.
     */
    public function __construct(
        ?string $grant = null
    )
    {
        $this->grant = $grant;
    }
}
