<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketCors operation.
 * Class GetBucketCorsResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketCorsResult extends ResultModel
{

    /**
     * The container that stores CORS configuration.
     * @var CORSConfiguration|null
     */
    #[TagBody(rename: 'CORSConfiguration', type: CORSConfiguration::class, format: 'xml')]
    public ?CORSConfiguration $corsConfiguration;

    /**
     * GetBucketCorsRequest constructor.
     * @param CORSConfiguration|null $corsConfiguration The container that stores CORS configuration.
     */
    public function __construct(
        ?CORSConfiguration $corsConfiguration = null
    )
    {
        $this->corsConfiguration = $corsConfiguration;
    }
}
