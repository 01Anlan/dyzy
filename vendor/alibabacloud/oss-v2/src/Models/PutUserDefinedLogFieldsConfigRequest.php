<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the PutUserDefinedLogFieldsConfig operation.
 * Class PutUserDefinedLogFieldsConfigRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutUserDefinedLogFieldsConfigRequest extends RequestModel
{
    /**
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The container that stores the specified log configurations.
     * @var UserDefinedLogFieldsConfiguration|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'body', rename: 'UserDefinedLogFieldsConfiguration', type: 'xml')]
    public ?UserDefinedLogFieldsConfiguration $userDefinedLogFieldsConfiguration;

    /**
     * PutUserDefinedLogFieldsConfigRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param UserDefinedLogFieldsConfiguration|null $userDefinedLogFieldsConfiguration The container that stores the specified log configurations.
     * @param array|null $options
     */
    public function __construct(
        ?string $bucket = null,
        ?UserDefinedLogFieldsConfiguration $userDefinedLogFieldsConfiguration = null,
        ?array $options = null
    )
    {
        $this->bucket = $bucket;
        $this->userDefinedLogFieldsConfiguration = $userDefinedLogFieldsConfiguration;
        parent::__construct($options);
    }
}