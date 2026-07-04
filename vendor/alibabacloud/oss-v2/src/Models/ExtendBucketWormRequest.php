<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the ExtendBucketWorm operation.
 * Class ExtendBucketWormRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ExtendBucketWormRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The ID of the retention policy.  If the ID of the retention policy that specifies the number of days for which objects can be retained does not exist, the HTTP status code 404 is returned.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'query', rename: 'wormId', type: 'string')]
    public ?string $wormId;

    /**
     * The container of the request body.
     * @var ExtendWormConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'ExtendWormConfiguration', type: 'xml')]
    public ?ExtendWormConfiguration $extendWormConfiguration;

    /**
     * ExtendBucketWormRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $wormId The ID of the retention policy.
     * @param ExtendWormConfiguration|null $extendWormConfiguration The container of the request body.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?string $wormId = null,
        ?ExtendWormConfiguration $extendWormConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->wormId = $wormId;
        $this->extendWormConfiguration = $extendWormConfiguration;
        parent::__construct($options);
    }
}