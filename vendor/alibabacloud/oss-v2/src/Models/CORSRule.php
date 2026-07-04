<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CORSRule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CORSRule')]
final class CORSRule extends Model
{
    /**
     * Specifies whether the headers specified by Access-Control-Request-Headers in the OPTIONS preflight request are allowed. Each header specified by Access-Control-Request-Headers must match the value of an AllowedHeader element.  You can use only one asterisk (\*) as the wildcard character.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'AllowedHeader', type: 'string')]
    public ?array $allowedHeaders;

    /**
     * The response headers for allowed access requests from applications, such as an XMLHttpRequest object in JavaScript.  The asterisk (\*) wildcard character is not supported.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'ExposeHeader', type: 'string')]
    public ?array $exposeHeaders;

    /**
     * The period of time within which the browser can cache the response to an OPTIONS preflight request for the specified resource. Unit: seconds.You can specify only one MaxAgeSeconds element in a CORS rule.
     * @var int|null
     */
    #[XmlElement(rename: 'MaxAgeSeconds', type: 'int')]
    public ?int $maxAgeSeconds;

    /**
     * The origins from which cross-origin requests are allowed.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'AllowedOrigin', type: 'string')]
    public ?array $allowedOrigins;

    /**
     * The methods that you can use in cross-origin requests.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'AllowedMethod', type: 'string')]
    public ?array $allowedMethods;


    /**
     * CORSRule constructor.
     * @param array<string>|null $allowedHeaders Specifies whether the headers specified by Access-Control-Request-Headers in the OPTIONS preflight request are allowed.
     * @param array<string>|null $exposeHeaders The response headers for allowed access requests from applications, such as an XMLHttpRequest object in JavaScript.
     * @param int|null $maxAgeSeconds The period of time within which the browser can cache the response to an OPTIONS preflight request for the specified resource.
     * @param array<string>|null $allowedOrigins The origins from which cross-origin requests are allowed.
     * @param array<string>|null $allowedMethods The methods that you can use in cross-origin requests.
     */
    public function __construct(
        ?array $allowedHeaders = null,
        ?array $exposeHeaders = null,
        ?int $maxAgeSeconds = null,
        ?array $allowedOrigins = null,
        ?array $allowedMethods = null
    )
    {
        $this->allowedHeaders = $allowedHeaders;
        $this->exposeHeaders = $exposeHeaders;
        $this->maxAgeSeconds = $maxAgeSeconds;
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
    }
}