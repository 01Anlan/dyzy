<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryTagging
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Tagging')]
final class MetaQueryTagging extends Model
{
    /**
     * The tag key.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The tag value.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;

    /**
     * MetaQueryTagging constructor.
     * @param string|null $key The tag key.
     * @param string|null $value The tag value.
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