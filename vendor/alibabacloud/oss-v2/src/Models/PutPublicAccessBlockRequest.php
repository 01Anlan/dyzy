<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutPublicAccessBlock operation.
 * Class PutPublicAccessBlockRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutPublicAccessBlockRequest extends RequestModel
{

    /**
     * Request body.
     * @var PublicAccessBlockConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'PublicAccessBlockConfiguration', type: 'xml')]
    public ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration;

    /**
     * PutPublicAccessBlockRequest constructor.
     * @param PublicAccessBlockConfiguration|null $publicAccessBlockConfiguration Request body.
     * @param array|null $options
     */
    public function __construct(
        ?PublicAccessBlockConfiguration $publicAccessBlockConfiguration = null,
        ?array $options = null
    )
    {
        $this->publicAccessBlockConfiguration = $publicAccessBlockConfiguration;
        parent::__construct($options);
    }
}