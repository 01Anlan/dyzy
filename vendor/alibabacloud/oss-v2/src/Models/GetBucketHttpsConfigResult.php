<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketHttpsConfig operation.
 * Class GetBucketHttpsConfigResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketHttpsConfigResult extends ResultModel
{
    /**
     * The container that stores HTTPS configurations.
     * @var HttpsConfiguration|null
     */
    #[TagBody(rename: 'HttpsConfiguration', type: HttpsConfiguration::class, format: 'xml')]
    public ?HttpsConfiguration $httpsConfiguration;

    /**
     * GetBucketHttpsConfigRequest constructor.
     * @param HttpsConfiguration|null $httpsConfiguration The container that stores HTTPS configurations.
     */
    public function __construct(
        ?HttpsConfiguration $httpsConfiguration = null
    )
    {
        $this->httpsConfiguration = $httpsConfiguration;
    }
}
