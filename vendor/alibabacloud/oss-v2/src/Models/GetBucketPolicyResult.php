<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketPolicy operation.
 * Class GetBucketPolicyResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketPolicyResult extends ResultModel
{
    /**
     * @var string|null
     */
    public ?string $body;

    /**
     * GetBucketPolicyRequest constructor.
     * @param string|null $body
     */
    public function __construct(
        ?string $body = null
    )
    {
        $this->body = $body;
    }
}
