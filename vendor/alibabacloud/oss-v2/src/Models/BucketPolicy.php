<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class BucketPolicy
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'BucketPolicy')]
final class BucketPolicy extends Model
{
    /**
     * The name of the bucket that stores the logs.
     * @var string|null
     */
    #[XmlElement(rename: 'LogBucket', type: 'string')]
    public ?string $logBucket;

    /**
     * The directory in which logs are stored.
     * @var string|null
     */
    #[XmlElement(rename: 'LogPrefix', type: 'string')]
    public ?string $logPrefix;

    /**
     * BucketPolicy constructor.
     * @param string|null $logBucket The name of the bucket that stores the logs.
     * @param string|null $logPrefix The directory in which logs are stored.
     */
    public function __construct(
        ?string $logBucket = null,
        ?string $logPrefix = null
    )
    {
        $this->logBucket = $logBucket;
        $this->logPrefix = $logPrefix;
    }
}