<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutStyle operation.
 * Class PutStyleRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutStyleRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the image style.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'styleName', type: 'string')]
    public ?string $styleName;

    /**
     * The category of the style.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'category', type: 'string')]
    public ?string $category;

    /**
     * The container that stores the content information about the image style.
     * @var StyleContent|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'Style', type: 'xml')]
    public ?StyleContent $style;

    /**
     * PutStyleRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $styleName The name of the image style.
     * @param string|null $category The category of the style.
     * @param StyleContent|null $style The container that stores the content information about the image style.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $styleName = null,
        ?string $category = null,
        ?StyleContent $style = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->styleName = $styleName;
        $this->category = $category;
        $this->style = $style;
        parent::__construct($options);
    }
}