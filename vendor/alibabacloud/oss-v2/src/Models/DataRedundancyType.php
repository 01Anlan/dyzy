<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * The type of disaster recovery for a bucket.
 * Class DataRedundancyType
 * @package AlibabaCloud\Oss\V2\Models
 */
class DataRedundancyType
{
    /**
     * Locally redundant storage (LRS) stores copies of each object across different devices in the same zone.
     * This ensures data reliability and availability even if two storage devices are damaged at the same time.
     */
    const LRS = 'LRS';

    /**
     * Zone-redundant storage (ZRS) uses the multi-zone mechanism to distribute user data across multiple zones in the same region.
     * If one zone becomes unavailable, you can continue to access the data that is stored in other zones.
     */
    const ZRS = 'ZRS';
}

