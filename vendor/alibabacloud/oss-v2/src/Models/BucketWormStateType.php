<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

/**
 * The status of the retention policy
 * Class BucketWormStateType
 * @package AlibabaCloud\Oss\V2\Models
 */
class BucketWormStateType
{
    /**
     * Indicates that the retention policy is in the InProgress state. By default, a retention policy is in the InProgress state after it is created. The state remains valid for 24 hours.
     */
    const IN_PROGRESS = 'InProgress';

    /**
     * Indicates that the retention policy is in the Locked state.
     */
    const LOCKED = 'Locked';
}

