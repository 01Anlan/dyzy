<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetUserDefinedLogFieldsConfig operation.
 * Class GetUserDefinedLogFieldsConfigResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetUserDefinedLogFieldsConfigResult extends ResultModel
{
    /**
     * The container for the user-defined logging configuration.
     * @var UserDefinedLogFieldsConfiguration|null
     */
    #[TagBody(rename: 'UserDefinedLogFieldsConfiguration', type: UserDefinedLogFieldsConfiguration::class, format: 'xml')]
    public ?UserDefinedLogFieldsConfiguration $userDefinedLogFieldsConfiguration;

    /**
     * GetUserDefinedLogFieldsConfigRequest constructor.
     * @param UserDefinedLogFieldsConfiguration|null $userDefinedLogFieldsConfiguration The container for the user-defined logging configuration.
     */
    public function __construct(
        ?UserDefinedLogFieldsConfiguration $userDefinedLogFieldsConfiguration = null
    )
    {
        $this->userDefinedLogFieldsConfiguration = $userDefinedLogFieldsConfiguration;
    }
}
