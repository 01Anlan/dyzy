<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the GetPublicAccessBlock operation.
 * Class GetPublicAccessBlockRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetPublicAccessBlockRequest extends RequestModel
{   
    /**
     * GetPublicAccessBlockRequest constructor.
     * 
     * @param array|null $options
     */
    public function __construct( 
        ?array $options = null
    )
    {   
        parent::__construct($options);
    }
}