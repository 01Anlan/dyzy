<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationTimeControl
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RTC')]
final class ReplicationTimeControl extends Model
{
    /**
     * Specifies whether to enable RTC.Valid values:*   disabled            *   enabled
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * ReplicationTimeControl constructor.
     * @param string|null $status Specifies whether to enable RTC.
     */
    public function __construct(
        ?string $status = null
    )
    {
        $this->status = $status;
    }
}