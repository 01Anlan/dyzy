<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationEncryptionConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationEncryptionConfiguration')]
final class ReplicationEncryptionConfiguration extends Model
{
    /**
     * @var string|null
     */
    #[XmlElement(rename: 'ReplicaKmsKeyID', type: 'string')]
    public ?string $replicaKmsKeyID;

    /**
     * ReplicationEncryptionConfiguration constructor.
     * @param string|null $replicaKmsKeyID
     */
    public function __construct(
        ?string $replicaKmsKeyID = null
    )
    {
        $this->replicaKmsKeyID = $replicaKmsKeyID;
    }
}