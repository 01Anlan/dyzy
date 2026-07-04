<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryOssUserMeta
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'OSSUserMeta')]
final class MetaQueryOssUserMeta extends Model
{
    /**
     * @var array<MetaQueryUserMeta>|null
     */
    #[XmlElement(rename: 'UserMeta', type: MetaQueryUserMeta::class)]
    public ?array $userMetas;

    /**
     * MetaQueryOssUserMeta constructor.
     * @param array<MetaQueryUserMeta>|null $userMetas
     */
    public function __construct(
        ?array $userMetas = null
    )
    {
        $this->userMetas = $userMetas;
    }
}