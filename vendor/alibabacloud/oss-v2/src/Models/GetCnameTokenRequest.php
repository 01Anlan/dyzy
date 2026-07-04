<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetCnameToken operation.
 * Class GetCnameTokenRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetCnameTokenRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the CNAME record that is mapped to the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'cname', type: 'string')]
    public ?string $cname;

    /**
     * GetCnameTokenRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $cname The name of the CNAME record that is mapped to the bucket.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $cname = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->cname = $cname;
        parent::__construct($options);
    }
}