<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class TransferTypes
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'TransferTypes')]
final class TransferTypes extends Model
{
    /**
     * The data transfer type that is used to transfer data in data replication. Valid values:*   internal (default): the default data transfer link used in OSS.*   oss_acc: the link in which data transmission is accelerated. You can set TransferType to oss_acc only when you create CRR rules.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Type', type: 'string')]
    public ?array $types;

    /**
     * TransferTypes constructor.
     * @param array<string>|null $types The data transfer type that is used to transfer data in data replication.
     */
    public function __construct(
        ?array $types = null
    )
    {
        $this->types = $types;
    }
}