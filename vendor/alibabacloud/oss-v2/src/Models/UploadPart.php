<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class UploadPart
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Part')]
final class UploadPart extends Model
{
    /**
     * The part number.
     * @var int|null
     */
    #[XmlElement(rename: 'PartNumber', type: 'int')]
    public ?int $partNumber;

    /**
     * The ETag value that is returned by OSS after the part is uploaded.
     * @var string|null
     */
    #[XmlElement(rename: 'ETag', type: 'string')]
    public ?string $etag;

    /**
     * UploadPart constructor.
     * @param int|null $partNumber The part number.
     * @param string|null $etag The ETag value that is returned by OSS after the part is uploaded.
     * @param string|null $eTag The ETag value that is returned by OSS after the part is uploaded. The eTag parameter has the same functionality as the etag parameter. It is the normalized name of etag. If both exist simultaneously, the value of eTag will take precedence. It is the normalized name of ETag.
     */
    public function __construct(
        ?int $partNumber = null,
        ?string $etag = null,
        ?string $eTag = null
    )
    {
        $this->partNumber = $partNumber;
        $this->etag = $eTag ?? $etag;
    }
}
