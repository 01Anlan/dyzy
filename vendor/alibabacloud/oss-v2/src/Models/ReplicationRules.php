<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationRules
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationRules')]
final class ReplicationRules extends Model
{
    /**
     * The container that stores the data replication rules.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?array $ids;

    /**
     * ReplicationConfiguration constructor.
     * @param array<string>|null $ids The ID of data replication rules that you want to delete.
     */
    public function __construct(
        ?array $ids = null
    )
    {
        $this->ids = $ids;
    }
}