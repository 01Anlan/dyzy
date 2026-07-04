<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the CreateAccessPoint operation.
 * Class CreateAccessPointRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class CreateAccessPointRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The container of the request body.
     * @var CreateAccessPointConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'CreateAccessPointConfiguration', type: 'xml')]
    public ?CreateAccessPointConfiguration $createAccessPointConfiguration;

    /**
     * CreateAccessPointRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param CreateAccessPointConfiguration|null $createAccessPointConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?CreateAccessPointConfiguration $createAccessPointConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->createAccessPointConfiguration = $createAccessPointConfiguration;
        parent::__construct($options);
    }
}