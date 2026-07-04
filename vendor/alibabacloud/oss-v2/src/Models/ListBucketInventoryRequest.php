<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the ListBucketInventory operation.
 * Class ListBucketInventoryRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListBucketInventoryRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * Specify the start position of the list operation. You can obtain this token from the NextContinuationToken field of last ListBucketInventory's result.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'continuation-token', type: 'string')]
    public ?string $continuationToken;

    /**
     * ListBucketInventoryRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $continuationToken Specify the start position of the list operation.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $continuationToken = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->continuationToken = $continuationToken;
        parent::__construct($options);
    }
}