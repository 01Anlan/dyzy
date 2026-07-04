<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;
use AlibabaCloud\Oss\V2\Types\Model;

/**
 * Class VersioningConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'VersioningConfiguration')]
final class VersioningConfiguration extends Model
{
    /**
     * The versioning state of the bucket.
     * Sees BucketVersioningStatusType for supported values.
     * @var string|null
     */
    #[XmlElement(rename: 'Status', type: 'string')]
    public ?string $status;

    /**
     * VersioningConfiguration constructor.
     * @param string|null $status The versioning state of the bucket.
     */
    public function __construct(
        ?string $status = null
    )
    {
        $this->status = $status;
    }
}
