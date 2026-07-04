<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationPrefixSet
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationPrefixSet')]
final class ReplicationPrefixSet extends Model
{
    /**
     * The prefix that is used to specify the object that you want to replicate. Only objects whose names contain the specified prefix are replicated to the destination bucket.*   The value of the Prefix parameter can be up to 1,023 characters in length.*   If you specify the Prefix parameter in a data replication rule, OSS synchronizes new data and historical data based on the value of the Prefix parameter.
     * @var array<string>|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?array $prefixs;

    /**
     * ReplicationPrefixSet constructor.
     * @param array<string>|null $prefixs The prefix that is used to specify the object that you want to replicate.
     */
    public function __construct(
        ?array $prefixs = null
    )
    {
        $this->prefixs = $prefixs;
    }
}