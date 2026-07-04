<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LoggingEnabled
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LoggingEnabled')]
final class LoggingEnabled extends Model
{
    /**
     * The bucket that stores access logs.
     * @var string|null
     */
    #[XmlElement(rename: 'TargetBucket', type: 'string')]
    public ?string $targetBucket;

    /**
     * The prefix of the log objects. This parameter can be left empty.
     * @var string|null
     */
    #[XmlElement(rename: 'TargetPrefix', type: 'string')]
    public ?string $targetPrefix;

    /**
     * Log transfer authorization role.
     * @var string|null
     */
    #[XmlElement(rename: 'LoggingRole', type: 'string')]
    public ?string $loggingRole;


    /**
     * LoggingEnabled constructor.
     * @param string|null $targetBucket The bucket that stores access logs.
     * @param string|null $targetPrefix The prefix of the log objects.
     * @param string|null $loggingRole Log transfer authorization role.
     */
    public function __construct(
        ?string $targetBucket = null,
        ?string $targetPrefix = null,
        ?string $loggingRole = null
    )
    {
        $this->targetBucket = $targetBucket;
        $this->targetPrefix = $targetPrefix;
        $this->loggingRole = $loggingRole;
    }
}