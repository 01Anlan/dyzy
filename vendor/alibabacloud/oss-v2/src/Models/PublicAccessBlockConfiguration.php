<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class PublicAccessBlockConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'PublicAccessBlockConfiguration')]
final class PublicAccessBlockConfiguration extends Model
{   
    /**
     * Specifies whether to enable Block Public Access.true: enables Block Public Access.false (default): disables Block Public Access.
     * @var bool|null
     */
    #[XmlElement(rename: 'BlockPublicAccess', type: 'bool')]
    public ?bool $blockPublicAccess;

    /**
     * PublicAccessBlockConfiguration constructor.
     * @param bool|null $blockPublicAccess Specifies whether to enable Block Public Access.
     */
    public function __construct(
        ?bool $blockPublicAccess = null
    )
    {   
        $this->blockPublicAccess = $blockPublicAccess;
    }
}