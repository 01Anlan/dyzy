<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryUserMeta
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'UserMeta')]
final class MetaQueryUserMeta extends Model
{
    /**
     * The value of the user metadata item.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;
    /**
     * The key of the user metadata item.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * MetaQueryUserMeta constructor.
     * @param string|null $value The value of the user metadata item.
     * @param string|null $key The key of the user metadata item.
     */
    public function __construct(
        ?string $value = null,
        ?string $key = null
    )
    {
        $this->value = $value;
        $this->key = $key;
    }
}