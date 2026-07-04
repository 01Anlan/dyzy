<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Taggings
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Taggings')]
final class MirrorTagging extends Model
{
    /**
     * The rule for setting tag value for a specific tag key.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;

    /**
     * The tag key.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * Taggings constructor.
     * @param string|null $value The rule for setting tag value for a specific tag key.
     * @param string|null $key The tag key.
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