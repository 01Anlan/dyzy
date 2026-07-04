<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RestoreRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RestoreRequest')]
final class RestoreRequest extends Model
{
    /**
     * The duration in which the object can remain in the restored state. Unit: days. Valid values: 1 to 7.
     * @var int|null
     */
    #[XmlElement(rename: 'Days', type: 'int')]
    public ?int $days;

    /**
     * The restoration priority. Valid values:
     * Expedited: The object is restored within 1 hour.
     * Standard: The object is restored within 2 to 5 hours.
     * Bulk: The object is restored within 5 to 12 hours.
     * @var string|null
     */
    #[XmlElement(rename: 'JobParameters.Tier', type: 'string')]
    public ?string $tier;

    public function __construct(
        ?int $days = null,
        ?string $tier = null
    )
    {
        $this->days = $days;
        $this->tier = $tier;
    }
}
