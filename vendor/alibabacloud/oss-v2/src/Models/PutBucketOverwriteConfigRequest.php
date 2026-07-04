<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutBucketOverwriteConfig operation.
 * Class PutBucketOverwriteConfigRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutBucketOverwriteConfigRequest extends RequestModel
{
    /**
     * Bucket Name
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * Structure of the API Request Body
     * @var OverwriteConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'OverwriteConfiguration', type: 'xml')]
    public ?OverwriteConfiguration $overwriteConfiguration;

    /**
     * PutBucketOverwriteConfigRequest constructor.
     * @param string|null $bucket Bucket Name
     * @param OverwriteConfiguration|null $overwriteConfiguration Structure of the API Request Body
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?OverwriteConfiguration $overwriteConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->overwriteConfiguration = $overwriteConfiguration;
        parent::__construct($options);
    }
}