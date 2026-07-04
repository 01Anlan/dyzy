<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationDestination
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationDestination')]
final class ReplicationDestination extends Model
{
    /**
     * The destination bucket to which data is replicated.
     * @var string|null
     */
    #[XmlElement(rename: 'Bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The region in which the destination bucket is located.
     * @var string|null
     */
    #[XmlElement(rename: 'Location', type: 'string')]
    public ?string $location;

    /**
     * The link that is used to transfer data during data replication. Valid values:*   internal (default): the default data transfer link used in OSS.*   oss_acc: the transfer acceleration link. You can set TransferType to oss_acc only when you create CRR rules.
     * Sees TransferType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'TransferType', type: 'string')]
    public ?string $transferType;

    /**
     * ReplicationDestination constructor.
     * @param string|null $bucket The destination bucket to which data is replicated.
     * @param string|null $location The region in which the destination bucket is located.
     * @param string|null $transferType The link that is used to transfer data during data replication.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $location = null,
        ?string $transferType = null
    )
    {
        $this->bucket = $bucket;
        $this->location = $location;
        $this->transferType = $transferType;
    }
}