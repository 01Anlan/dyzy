<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketRequestPayment operation.
 * Class PutBucketRequestPaymentRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketRequestPaymentRequest extends RequestModel
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
     * @var RequestPaymentConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'RequestPaymentConfiguration', type: 'xml')]
    public ?RequestPaymentConfiguration $requestPaymentConfiguration;

    /**
     * PutBucketRequestPaymentRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param RequestPaymentConfiguration|null $requestPaymentConfiguration The request body schema.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?RequestPaymentConfiguration $requestPaymentConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->requestPaymentConfiguration = $requestPaymentConfiguration;
        parent::__construct($options);
    }
}