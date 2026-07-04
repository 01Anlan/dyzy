<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetBucketPolicyStatus operation.
 * Class GetBucketPolicyStatusResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketPolicyStatusResult extends ResultModel
{
    /**
     * The container that stores public access information.
     * @var PolicyStatus|null
     */
    #[TagBody(rename: 'PolicyStatus', type: PolicyStatus::class, format: 'xml')]
    public ?PolicyStatus $policyStatus;

    /**
     * GetBucketPolicyStatusRequest constructor.
     * @param PolicyStatus|null $policyStatus The container that stores public access information.
     */
    public function __construct(
        ?PolicyStatus $policyStatus = null
    )
    {
        $this->policyStatus = $policyStatus;
    }
}
