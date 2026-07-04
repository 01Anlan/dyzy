<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Part
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Part')]
final class Part extends Model
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
     * The time when the part was uploaded.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'LastModified', type: 'DateTime')]
    public ?\DateTime $lastModified;

    /**
     * The size of the part.
     * @var int|null
     */
    #[XmlElement(rename: 'Size', type: 'int')]
    public ?int $size;

    /**
     * The 64-bit CRC value of the object.
     * This value is calculated based on the ECMA-182 standard.
     * @var string|null
     */
    #[XmlElement(rename: 'HashCrc64ecma', type: 'string')]
    public ?string $hashCrc64;


    /**
     * Part constructor.
     * @param int|null $partNumber The part number.
     * @param string|null $etag The ETag value that is returned by OSS after the part is uploaded.
     * @param \DateTime|null $lastModified The time when the part was uploaded.
     * @param int|null $size The size of the part.
     * @param string|null $hashCrc64 The 64-bit CRC value of the object.
     */
    public function __construct(
        ?int $partNumber = null,
        ?string $etag = null,
        ?\DateTime $lastModified = null,
        ?int $size = null,
        ?string $hashCrc64 = null
    )
    {
        $this->partNumber = $partNumber;
        $this->etag = $etag;
        $this->lastModified = $lastModified;
        $this->size = $size;
        $this->hashCrc64 = $hashCrc64;
    }
}
