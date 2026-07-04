<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReturnHeader
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReturnHeader')]
final class ReturnHeader extends Model
{   
    /**
     * The response header.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The rule for setting response header value for a specific header.
     * @var string|null
     */
    #[XmlElement(rename: 'Value', type: 'string')]
    public ?string $value;

    /**
     * ReturnHeader constructor.
     * @param string|null $key The response header.
     * @param string|null $value The rule for setting response header value for a specific header.
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