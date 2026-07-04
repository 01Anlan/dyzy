<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class InitiateWormConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'InitiateWormConfiguration')]
final class InitiateWormConfiguration extends Model
{
    /**
     * The number of days for which objects can be retained.
     * @var int|null
     */
    #[XmlElement(rename: 'RetentionPeriodInDays', type: 'int')]
    public ?int $retentionPeriodInDays;

    /**
     * InitiateWormConfiguration constructor.
     * @param int|null $retentionPeriodInDays The number of days for which objects can be retained.
     */
    public function __construct(
        ?int $retentionPeriodInDays = null
    )
    {
        $this->retentionPeriodInDays = $retentionPeriodInDays;
    }
}