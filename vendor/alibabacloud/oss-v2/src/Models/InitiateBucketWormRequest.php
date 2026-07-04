<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the InitiateBucketWorm operation.
 * Class InitiateBucketWormRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class InitiateBucketWormRequest extends RequestModel
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
     * @var InitiateWormConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'InitiateWormConfiguration', type: 'xml')]
    public ?InitiateWormConfiguration $initiateWormConfiguration;

    /**
     * InitiateBucketWormRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param InitiateWormConfiguration|null $initiateWormConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?InitiateWormConfiguration $initiateWormConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->initiateWormConfiguration = $initiateWormConfiguration;
        parent::__construct($options);
    }
}