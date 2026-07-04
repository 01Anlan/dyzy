<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationProgress
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationProgress')]
final class ReplicationProgress extends Model
{
    /**
     * The container that stores the progress of the data replication task corresponding to each data replication rule.
     * @var array<ReplicationProgressRule>|null
     */
    #[XmlElement(rename: 'Rule', type: ReplicationProgressRule::class)]
    public ?array $rules;

    /**
     * ReplicationProgress constructor.
     * @param array<ReplicationProgressRule>|null $rules The container that stores the progress of the data replication task corresponding to each data replication rule.
     */
    public function __construct(
        ?array $rules = null
    )
    {
        $this->rules = $rules;
    }
}