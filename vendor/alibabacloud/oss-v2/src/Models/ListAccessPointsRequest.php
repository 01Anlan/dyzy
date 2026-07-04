<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the ListAccessPoints operation.
 * Class ListAccessPointsRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListAccessPointsRequest extends RequestModel
{
    /**
     * The maximum number of access points that can be returned. Valid values:*   For user-level access points: (0,1000].*   For bucket-level access points: (0,100].
     * @var int|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'max-keys', type: 'int')]
    public ?int $maxKeys;

    /**
     * The token from which the listing operation starts. You must specify the value of NextContinuationToken that is obtained from the previous query as the value of continuation-token.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'query', rename: 'continuation-token', type: 'string')]
    public ?string $continuationToken;

    /**
     * The name of the bucket.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * ListAccessPointsRequest constructor.
     *
     * @param int|null $maxKeys The maximum number of access points that can be returned.
     * @param string|null $continuationToken The token from which the listing operation starts.
     * @param string|null $bucket The name of the bucket.
     * @param array|null $options
     */
    public function __construct(
        ?int $maxKeys = null,
        ?string $continuationToken = null,
        ?string $bucket = null,
        ?array $options = null
    )
    {
        $this->maxKeys = $maxKeys;
        $this->continuationToken = $continuationToken;
        $this->bucket = $bucket;
        parent::__construct($options);
    }
}