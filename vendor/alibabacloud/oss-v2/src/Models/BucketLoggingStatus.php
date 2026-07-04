<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class BucketLoggingStatus
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'BucketLoggingStatus')]
final class BucketLoggingStatus extends Model
{
    /**
     * Indicates the container used to store access logging information. This element is returned if it is enabled and is not returned if it is disabled.
     * @var LoggingEnabled|null
     */
    #[XmlElement(rename: 'LoggingEnabled', type: LoggingEnabled::class)]
    public ?LoggingEnabled $loggingEnabled;


    /**
     * BucketLoggingStatus constructor.
     * @param LoggingEnabled|null $loggingEnabled Indicates the container used to store access logging information.
     */
    public function __construct(
        ?LoggingEnabled $loggingEnabled = null
    )
    {
        $this->loggingEnabled = $loggingEnabled;
    }
}