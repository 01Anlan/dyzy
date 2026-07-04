<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class StyleInfo
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'StyleInfo')]
final class StyleInfo extends Model
{
    /**
     * The content of the style.
     * @var string|null
     */
    #[XmlElement(rename: 'Content', type: 'string')]
    public ?string $content;

    /**
     * The time when the style was created.
     * @var string|null
     */
    #[XmlElement(rename: 'CreateTime', type: 'string')]
    public ?string $createTime;

    /**
     * The time when the style was last modified.
     * @var string|null
     */
    #[XmlElement(rename: 'LastModifyTime', type: 'string')]
    public ?string $lastModifyTime;

    /**
     * The category of this style。  Invalid value：image、document、video。
     * @var string|null
     */
    #[XmlElement(rename: 'Category', type: 'string')]
    public ?string $category;

    /**
     * The style name.
     * @var string|null
     */
    #[XmlElement(rename: 'Name', type: 'string')]
    public ?string $name;

    /**
     * StyleInfo constructor.
     * @param string|null $content The content of the style.
     * @param string|null $createTime The time when the style was created.
     * @param string|null $lastModifyTime The time when the style was last modified.
     * @param string|null $category The category of this style. Invalid value：image、document、video。
     * @param string|null $name The style name.
     */
    public function __construct(
        ?string $content = null,
        ?string $createTime = null,
        ?string $lastModifyTime = null,
        ?string $category = null,
        ?string $name = null
    )
    {
        $this->content = $content;
        $this->createTime = $createTime;
        $this->lastModifyTime = $lastModifyTime;
        $this->category = $category;
        $this->name = $name;
    }
}