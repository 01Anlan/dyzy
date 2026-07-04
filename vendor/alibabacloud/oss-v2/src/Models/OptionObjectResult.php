<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagHeader;

/**
 * The result for the OptionObject operation.
 * Class OptionObjectResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class OptionObjectResult extends ResultModel
{
    /**
     * @var string|null
     */
    #[TagHeader(rename: 'Access-Control-Allow-Origin', type: 'string')]
    public ?string $accessControlAllowOrigin;

    /**
     * @var string|null
     */
    #[TagHeader(rename: 'Access-Control-Allow-Methods', type: 'string')]
    public ?string $accessControlAllowMethods;

    /**
     * @var string|null
     */
    #[TagHeader(rename: 'Access-Control-Allow-Headers', type: 'string')]
    public ?string $accessControlAllowHeaders;
    /**
     * @var string|null
     */
    #[TagHeader(rename: 'Access-Control-Expose-Headers', type: 'string')]
    public ?string $accessControlExposeHeaders;

    /**
     * @var int|null
     */
    #[TagHeader(rename: 'Access-Control-Max-Age', type: 'int')]
    public ?int $accessControlMaxAge;

    /**
     * OptionObjectRequest constructor.
     * @param string|null $accessControlAllowOrigin
     * @param string|null $accessControlAllowMethods
     * @param string|null $accessControlAllowHeaders
     * @param string|null $accessControlExposeHeaders
     * @param int|null $accessControlMaxAge
     */
    public function __construct(
        ?string $accessControlAllowOrigin = null,
        ?string $accessControlAllowMethods = null,
        ?string $accessControlAllowHeaders = null,
        ?string $accessControlExposeHeaders = null,
        ?int $accessControlMaxAge = null
    )
    {
        $this->accessControlAllowOrigin = $accessControlAllowOrigin;
        $this->accessControlAllowMethods = $accessControlAllowMethods;
        $this->accessControlAllowHeaders = $accessControlAllowHeaders;
        $this->accessControlExposeHeaders = $accessControlExposeHeaders;
        $this->accessControlMaxAge = $accessControlMaxAge;
    }
}
