<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketPublicAccessBlock operation.
 * Class GetBucketPublicAccessBlockResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketPublicAccessBlockResult extends ResultModel
{   

    /** 
     * The container in which the Block Public Access configurations are stored.
     * @var PublicAccessBlockConfiguration|null
     */
    #[TagBody(rename: 'PublicAccessBlockConfiguration', type: PublicAccessBlockConfiguration::class, format: 'xml')]
    public ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration;

    /**
     * GetBucketPublicAccessBlockRequest constructor.
     * @param PublicAccessBlockConfiguration|null $publicAccessBlockConfiguration The container in which the Block Public Access configurations are stored.
     */
    public function __construct(
        ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration = null
    )
    {   
        $this->publicAccessBlockConfiguration = $publicAccessBlockConfiguration;
    }
}
