<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutAccessPointPolicy operation.
 * Class PutAccessPointPolicyRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutAccessPointPolicyRequest extends RequestModel
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
     * The configurations of the access point policy.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'nop', type: 'string')]
    public ?string $body;

    /**
     * PutAccessPointPolicyRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $accessPointName The name of the access point.
     * @param string|null $body The configurations of the access point policy.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $accessPointName = null,
        ?string $body = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->accessPointName = $accessPointName;
        $this->body = $body;
        parent::__construct($options);
    }
}