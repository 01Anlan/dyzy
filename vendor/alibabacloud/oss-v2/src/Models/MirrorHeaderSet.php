<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorHeaderSet
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Set')]
final class MirrorHeaderSet extends Model
{
    /**
     * The key of the header. The key can be up to 1,024 bytes in length and can contain only letters, digits, and hyphens (-). This parameter takes effect only when the value of RedirectType is Mirror.  This parameter must be specified if Set is specified.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The value of the header. The value can be up to 1,024 bytes in length and cannot contain `\r\n`. This parameter takes effect only when the value of RedirectType is Mirror.  This parameter must be specified if Set is specified.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;


    /**
     * MirrorHeaderSet constructor.
     * @param string|null $key The key of the header.
     * @param string|null $value The value of the header.
     */
    public function __construct(
        ?string $key = null,
        ?string $value = null
    )
    {
        $this->key = $key;
        $this->value = $value;
    }
}