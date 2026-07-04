<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;

/**
 * The request for the DeletePublicAccessBlock operation.
 * Class DeletePublicAccessBlockRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class DeletePublicAccessBlockRequest extends RequestModel
{
    /**
     * DeletePublicAccessBlockRequest constructor.
     * @param array|null $options
     */
    public function __construct(
        ?array $options = null
    )
    {
        parent::__construct($options);
    }
}