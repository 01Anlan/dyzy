<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationConfiguration')]
final class ReplicationConfiguration extends Model
{
    /**
     * The container that stores the data replication rules.
     * @var array<ReplicationRule>|null
     */
    #[XmlElement(rename: 'Rule', type: ReplicationRule::class)]
    public ?array $rules;

    /**
     * ReplicationConfiguration constructor.
     * @param array<ReplicationRule>|null $rules The container that stores the data replication rules.
     */
    public function __construct(
        ?array $rules = null
    )
    {
        $this->rules = $rules;
    }
}