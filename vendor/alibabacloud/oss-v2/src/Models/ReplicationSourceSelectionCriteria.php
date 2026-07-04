<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ReplicationSourceSelectionCriteria
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ReplicationSourceSelectionCriteria')]
final class ReplicationSourceSelectionCriteria extends Model
{
    /**
     * The container that is used to filter the source objects that are encrypted by using SSE-KMS. This parameter must be specified if the SourceSelectionCriteria parameter is specified in the data replication rule.
     * @var SseKmsEncryptedObjects|null
     */
    #[XmlElement(rename: 'SseKmsEncryptedObjects', type: SseKmsEncryptedObjects::class)]
    public ?SseKmsEncryptedObjects $sseKmsEncryptedObjects;

    /**
     * ReplicationSourceSelectionCriteria constructor.
     * @param SseKmsEncryptedObjects|null $sseKmsEncryptedObjects The container that is used to filter the source objects that are encrypted by using SSE-KMS.
     */
    public function __construct(
        ?SseKmsEncryptedObjects $sseKmsEncryptedObjects = null
    )
    {
        $this->sseKmsEncryptedObjects = $sseKmsEncryptedObjects;
    }
}