<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketRequestPayment operation.
 * Class GetBucketRequestPaymentResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketRequestPaymentResult extends ResultModel
{
    /**
     * Indicates the container for the payer.
     * @var RequestPaymentConfiguration|null
     */
    #[TagBody(rename: 'RequestPaymentConfiguration', type: RequestPaymentConfiguration::class, format: 'xml')]
    public ?RequestPaymentConfiguration $requestPaymentConfiguration;

    /**
     * GetBucketRequestPaymentRequest constructor.
     * @param RequestPaymentConfiguration|null $requestPaymentConfiguration Indicates the container for the payer.
     */
    public function __construct(
        ?RequestPaymentConfiguration $requestPaymentConfiguration = null
    )
    {
        $this->requestPaymentConfiguration = $requestPaymentConfiguration;
    }
}
