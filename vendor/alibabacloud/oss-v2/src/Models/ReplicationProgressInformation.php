<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Progress
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Progress')]
final class ReplicationProgressInformation extends Model
{
    /**
     * The percentage of the replicated historical data. This parameter is valid only when HistoricalObjectReplication is set to enabled.
     * @var string|null
     */
    #[XmlElement(rename: 'HistoricalObject', type: 'string')]
    public ?string $historicalObject;
    /**
     * The time used to determine whether data is replicated to the destination bucket. Data that is written to the source bucket before the time is replicated to the destination bucket. The value of this parameter is in the GMT format. Example: Thu, 24 Sep 2015 15:39:18 GMT.
     * @var string|null
     */
    #[XmlElement(rename: 'NewObject', type: 'string')]
    public ?string $newObject;

    /**
     * Progress constructor.
     * @param string|null $historicalObject The percentage of the replicated historical data.
     * @param string|null $newObject The time used to determine whether data is replicated to the destination bucket.
     */
    public function __construct(
        ?string $historicalObject = null,
        ?string $newObject = null
    )
    {
        $this->historicalObject = $historicalObject;
        $this->newObject = $newObject;
    }
}