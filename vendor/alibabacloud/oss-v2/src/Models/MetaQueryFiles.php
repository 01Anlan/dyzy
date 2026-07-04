<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryFiles
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Files')]
final class MetaQueryFiles extends Model
{
    /**
     * @var array<MetaQueryFile>|null
     */
    #[XmlElement(rename: 'File', type: MetaQueryFile::class)]
    public ?array $file;

    /**
     * MetaQueryFiles constructor.
     * @param array<MetaQueryFile>|null $file
     */
    public function __construct(
        ?array $file = null
    )
    {
        $this->file = $file;
    }
}