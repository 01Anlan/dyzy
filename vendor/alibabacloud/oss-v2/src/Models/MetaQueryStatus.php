<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryStatus
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'MetaQueryStatus')]
final class MetaQueryStatus extends Model
{
    /**
     * The status of the metadata index library. Valid values:- Ready: The metadata index library is being prepared after it is created.In this case, the metadata index library cannot be used to query data.- Stop: The metadata index library is paused.- Running: The metadata index library is running.- Retrying: The metadata index library failed to be created and is being created again.- Failed: The metadata index library failed to be created.- Deleted: The metadata index library is deleted.
     * @var string|null
     */
    #[XmlElement(rename: 'State', type: 'string')]
    public ?string $state;

    /**
     * The scan type. Valid values:- FullScanning: Full scanning is in progress.- IncrementalScanning: Incremental scanning is in progress.
     * @var string|null
     */
    #[XmlElement(rename: 'Phase', type: 'string')]
    public ?string $phase;

    /**
     * The time when the metadata index library was created. The value follows the RFC 3339 standard in the YYYY-MM-DDTHH:mm:ss+TIMEZONE format. YYYY-MM-DD indicates the year, month, and day. T indicates the beginning of the time element. HH:mm:ss indicates the hour, minute, and second. TIMEZONE indicates the time zone.
     * @var string|null
     */
    #[XmlElement(rename: 'CreateTime', type: 'string')]
    public ?string $createTime;

    /**
     * The time when the metadata index library was updated. The value follows the RFC 3339 standard in the YYYY-MM-DDTHH:mm:ss+TIMEZONE format. YYYY-MM-DD indicates the year, month, and day. T indicates the beginning of the time element. HH:mm:ss indicates the hour, minute, and second. TIMEZONE indicates the time zone.
     * @var string|null
     */
    #[XmlElement(rename: 'UpdateTime', type: 'string')]
    public ?string $updateTime;

    /**
     * MetaQueryStatus constructor.
     * @param string|null $state The status of the metadata index library.
     * @param string|null $phase The scan type.
     * @param string|null $createTime The time when the metadata index library was created.
     * @param string|null $updateTime The time when the metadata index library was updated.
     */
    public function __construct(
        ?string $state = null,
        ?string $phase = null,
        ?string $createTime = null,
        ?string $updateTime = null
    )
    {
        $this->state = $state;
        $this->phase = $phase;
        $this->createTime = $createTime;
        $this->updateTime = $updateTime;
    }
}