<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MirrorAuth
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MirrorAuth')]
final class MirrorAuth extends Model
{
    /**
     * The authentication type.
     * @var string|null
     */
    #[XmlElement(rename: 'AuthType', type: 'string')]
    public ?string $authType;

    /**
     * The sign region for signature.
     * @var string|null
     */
    #[XmlElement(rename: 'Region', type: 'string')]
    public ?string $region;

    /**
     * The access key id for signature.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessKeyId', type: 'string')]
    public ?string $accessKeyId;

    /**
     * The access key secret for signature.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessKeySecret', type: 'string')]
    public ?string $accessKeySecret;

    /**
     * MirrorAuth constructor.
     * @param string|null $authType The authentication type.
     * @param string|null $region The sign region for signature.
     * @param string|null $accessKeyId The access key id for signature.
     * @param string|null $accessKeySecret The access key secret for signature.
     */
    public function __construct(
        ?string $authType = null,
        ?string $region = null,
        ?string $accessKeyId = null,
        ?string $accessKeySecret = null
    )
    {
        $this->authType = $authType;
        $this->region = $region;
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
    }
}