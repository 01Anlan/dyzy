<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetStyle operation.
 * Class GetStyleRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetStyleRequest extends RequestModel
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
     * GetStyleRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $styleName The name of the image style.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $styleName = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->styleName = $styleName;
        parent::__construct($options);
    }
}