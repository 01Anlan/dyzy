<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LoggingHeaderSet
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'HeaderSet')]
final class LoggingHeaderSet extends Model
{
    /**
     * The list of the custom request headers.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'header', type: 'string')]
    public ?array $headers;

    /**
     * LoggingHeaderSet constructor.
     * @param array<string>|null $headers The list of the custom request headers.
     */
    public function __construct(
        ?array $headers = null
    )
    {
        $this->headers = $headers;
    }
}