<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketWebsite operation.
 * Class PutBucketWebsiteRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketWebsiteRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The request body schema.
     * @var WebsiteConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'WebsiteConfiguration', type: 'xml')]
    public ?WebsiteConfiguration $websiteConfiguration;

    /**
     * PutBucketWebsiteRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param WebsiteConfiguration|null $websiteConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?WebsiteConfiguration $websiteConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->websiteConfiguration = $websiteConfiguration;
        parent::__construct($options);
    }
}