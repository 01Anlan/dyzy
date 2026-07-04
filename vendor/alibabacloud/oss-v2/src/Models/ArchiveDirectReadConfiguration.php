<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ArchiveDirectReadConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ArchiveDirectReadConfiguration')]
final class ArchiveDirectReadConfiguration extends Model
{
    /**
     * Specifies whether to enable real-time access of Archive objects for a bucket. Valid values:- true- false
     * @var bool|null
     */
    #[XmlElement(rename: 'Enabled', type: 'bool')]
    public ?bool $enabled;

    /**
     * ArchiveDirectReadConfiguration constructor.
     * @param bool|null $enabled Specifies whether to enable real-time access of Archive objects for a bucket.
     */
    public function __construct(
        ?bool $enabled = null
    )
    {
        $this->enabled = $enabled;
    }
}