<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Tag
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Tag')]
final class Tag extends Model
{
    /**
     * The key of a tag. *   A tag key can be up to 64 bytes in length.*   A tag key cannot start with `http://`, `https://`, or `Aliyun`.*   A tag key must be UTF-8 encoded.*   A tag key cannot be left empty.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The value of the tag that you want to add or modify. *   A tag value can be up to 128 bytes in length.*   A tag value must be UTF-8 encoded.*   The tag value can be left empty.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;

    public function __construct(
        ?string $key = null,
        ?string $value = null
    )
    {
        $this->key = $key;
        $this->value = $value;
    }
}
