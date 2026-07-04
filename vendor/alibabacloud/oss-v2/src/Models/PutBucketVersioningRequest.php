<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the PutBucketVersioning operation.
 * Class PutBucketVersioningRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketVersioningRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    public ?string $bucket;

    /**
     * The container of the request body.
     * @var VersioningConfiguration|null
     */
    public ?VersioningConfiguration $versioningConfiguration;

    /**
     * PutBucketVersioningRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param VersioningConfiguration|null $versioningConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?VersioningConfiguration $versioningConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->versioningConfiguration = $versioningConfiguration;
        parent::__construct($options);
    }
}
