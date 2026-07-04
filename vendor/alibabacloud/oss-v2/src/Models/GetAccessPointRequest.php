<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetAccessPoint operation.
 * Class GetAccessPointRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetAccessPointRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the access point.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'header', rename: 'x-oss-access-point-name', type: 'string')]
    public ?string $accessPointName;

    /**
     * GetAccessPointRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $accessPointName The name of the access point.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $accessPointName = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->accessPointName = $accessPointName;
        parent::__construct($options);
    }
}