<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * The storage class of a bucket or object.
 * Class StorageClassType
 * @package AlibabaCloud\Oss\V2\Models
 */
class StorageClassType
{
    /**
     * Standard provides highly reliable, highly available,
     * and high-performance object storage for data that is frequently accessed.
     */
    const STANDARD = 'Standard';

    /**
     * IA provides highly durable storage at lower prices compared with Standard.
     * IA has a minimum billable size of 64 KB and a minimum billable storage duration of 30 days.
     */
    const IA = 'IA';

    /**
     * Archive provides high-durability storage at lower prices compared with Standard and IA.
     * Archive has a minimum billable size of 64 KB and a minimum billable storage duration of 60 days.
     */
    const ARCHIVE = 'Archive';

    /**
     * Cold Archive provides highly durable storage at lower prices compared with Archive.
     * Cold Archive has a minimum billable size of 64 KB and a minimum billable storage duration of 180 days.
     */
    const COLD_ARCHIVE = 'ColdArchive';

    /**
     * Deep Cold Archive provides highly durable storage at lower prices compared with Cold Archive.
     * Deep Cold Archive has a minimum billable size of 64 KB and a minimum billable storage duration of 180 days.
     */
    const DEEP_COLD_ARCHIVE = 'DeepColdArchive';
}

