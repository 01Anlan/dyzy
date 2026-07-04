<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CORSConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CORSConfiguration')]
final class CORSConfiguration extends Model
{
    /**
     * The container that stores CORS rules. Up to 10 rules can be configured for a bucket.
     * @var array<CORSRule>|null
     */
    #[XmlElement(rename: 'CORSRule', type: CORSRule::class)]
    public ?array $corsRules;

    /**
     * Indicates whether the Vary: Origin header was returned. Default value: false.- true: The Vary: Origin header is returned regardless whether the request is a cross-origin request or whether the cross-origin request succeeds.- false: The Vary: Origin header is not returned.
     * @var bool|null
     */
    #[XmlElement(rename: 'ResponseVary', type: 'bool')]
    public ?bool $responseVary;


    /**
     * CORSConfiguration constructor.
     * @param array<CORSRule>|null $corsRules The container that stores CORS rules.
     * @param bool|null $responseVary Indicates whether the Vary: Origin header was returned.
     */
    public function __construct(
        ?array $corsRules = null,
        ?bool $responseVary = null
    )
    {
        $this->corsRules = $corsRules;
        $this->responseVary = $responseVary;
    }
}