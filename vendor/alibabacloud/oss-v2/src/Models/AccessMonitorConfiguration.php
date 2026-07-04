<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class AccessMonitorConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AccessMonitorConfiguration')]
final class AccessMonitorConfiguration extends Model
{
    /**
     * The access tracking status of the bucket. Valid values:- Enabled: Access tracking is enabled.- Disabled: Access tracking is disabled.
     * Sees AccessMonitorStatusType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * AccessMonitorConfiguration constructor.
     * @param string|null $status The access tracking status of the bucket.
     */
    public function __construct(
        ?string $status = null
    )
    {
        $this->status = $status;
    }
}