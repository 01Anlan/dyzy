<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketHttpsConfig operation.
 * Class PutBucketHttpsConfigRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketHttpsConfigRequest extends RequestModel
{
    /**
     * This name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var HttpsConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'HttpsConfiguration', type: 'xml')]
    public ?HttpsConfiguration $httpsConfiguration;

    /**
     * PutBucketHttpsConfigRequest constructor.
     * @param string|null $bucket This name of the bucket.
     * @param HttpsConfiguration|null $httpsConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?HttpsConfiguration $httpsConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->httpsConfiguration = $httpsConfiguration;
        parent::__construct($options);
    }
}