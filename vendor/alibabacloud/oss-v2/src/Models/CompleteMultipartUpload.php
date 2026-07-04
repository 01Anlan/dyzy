<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class CompleteMultipartUpload
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'CompleteMultipartUpload')]
final class CompleteMultipartUpload extends Model
{
    /**
     * The container that stores the uploaded parts.
     * @var array<UploadPart>|null
     */
    #[XmlElement(rename: 'Part', type: UploadPart::class)]
    public ?array $parts;

    /**
     * CompleteMultipartUpload constructor.
     *
     * @param array<UploadPart>|null $parts The container that stores the uploaded parts.
     */
    public function __construct(
        ?array $parts = null
    )
    {
        $this->parts = $parts;
    }
}
