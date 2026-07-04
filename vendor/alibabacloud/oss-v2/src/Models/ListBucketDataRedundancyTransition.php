<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ListBucketDataRedundancyTransition
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ListBucketDataRedundancyTransition')]
final class ListBucketDataRedundancyTransition extends Model
{
    /**
     * @var array<BucketDataRedundancyTransition>|null
     */
    #[XmlElement(rename: 'BucketDataRedundancyTransition', type: BucketDataRedundancyTransition::class)]
    public ?array $bucketDataRedundancyTransitions;

    /**
     * ListBucketDataRedundancyTransition constructor.
     * @param array<BucketDataRedundancyTransition>|null $bucketDataRedundancyTransitions
     */
    public function __construct(
        ?array $bucketDataRedundancyTransitions = null
    )
    {
        $this->bucketDataRedundancyTransitions = $bucketDataRedundancyTransitions;
    }
}