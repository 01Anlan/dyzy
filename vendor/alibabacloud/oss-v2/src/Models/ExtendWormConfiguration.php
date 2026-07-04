<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ExtendWormConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ExtendWormConfiguration')]
final class ExtendWormConfiguration extends Model
{
    /**
     * The number of days for which objects can be retained.
     * @var int|null
     */
    #[XmlElement(rename: 'RetentionPeriodInDays', type: 'int')]
    public ?int $retentionPeriodInDays;

    /**
     * ExtendWormConfiguration constructor.
     * @param int|null $retentionPeriodInDays The number of days for which objects can be retained.
     */
    public function __construct(
        ?int $retentionPeriodInDays = null
    )
    {
        $this->retentionPeriodInDays = $retentionPeriodInDays;
    }
}