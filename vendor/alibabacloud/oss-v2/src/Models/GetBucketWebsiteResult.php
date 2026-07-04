<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketWebsite operation.
 * Class GetBucketWebsiteResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketWebsiteResult extends ResultModel
{
    /**
     * The containers of the website configuration.
     * @var WebsiteConfiguration|null
     */
    #[TagBody(rename: 'WebsiteConfiguration', type: WebsiteConfiguration::class, format: 'xml')]
    public ?WebsiteConfiguration $websiteConfiguration;

    /**
     * GetBucketWebsiteRequest constructor.
     * @param WebsiteConfiguration|null $websiteConfiguration The containers of the website configuration.
     */
    public function __construct(
        ?WebsiteConfiguration $websiteConfiguration = null
    )
    {
        $this->websiteConfiguration = $websiteConfiguration;
    }
}
