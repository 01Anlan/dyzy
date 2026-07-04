<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LoggingParamSet
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ParamSet')]
final class LoggingParamSet extends Model
{
    /**
     * The list of the custom URL parameters.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'parameter', type: 'string')]
    public ?array $parameters;

    /**
     * LoggingParamSet constructor.
     * @param array<string>|null $parameters The list of the custom URL parameters.
     */
    public function __construct(
        ?array $parameters = null
    )
    {
        $this->parameters = $parameters;
    }
}