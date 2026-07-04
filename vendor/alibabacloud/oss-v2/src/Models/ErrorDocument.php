<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ErrorDocument
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ErrorDocument')]
final class ErrorDocument extends Model
{
    /**
     * The error page.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The HTTP status code returned with the error page.
     * @var int|null
     */
    #[XmlElement(rename: 'HttpStatus', type: 'int')]
    public ?int $httpStatus;


    /**
     * ErrorDocument constructor.
     * @param string|null $key The error page.
     * @param int|null $httpStatus The HTTP status code returned with the error page.
     */
    public function __construct(
        ?string $key = null,
        ?int $httpStatus = null
    )
    {
        $this->key = $key;
        $this->httpStatus = $httpStatus;
    }
}