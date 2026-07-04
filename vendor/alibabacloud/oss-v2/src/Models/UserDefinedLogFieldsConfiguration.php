<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class UserDefinedLogFieldsConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'UserDefinedLogFieldsConfiguration')]
final class UserDefinedLogFieldsConfiguration extends Model
{
    /**
     * The container that stores the configurations of custom URL parameters.
     * @var LoggingParamSet|null
     */
    #[XmlElement(rename: 'ParamSet', type: LoggingParamSet::class)]
    public ?LoggingParamSet $paramSet;

    /**
     * The container that stores the configurations of custom request headers.
     * @var LoggingHeaderSet|null
     */
    #[XmlElement(rename: 'HeaderSet', type: LoggingHeaderSet::class)]
    public ?LoggingHeaderSet $headerSet;


    /**
     * UserDefinedLogFieldsConfiguration constructor.
     * @param LoggingParamSet|null $paramSet The container that stores the configurations of custom URL parameters.
     * @param LoggingHeaderSet|null $headerSet The container that stores the configurations of custom request headers.
     */
    public function __construct(
        ?LoggingParamSet $paramSet = null,
        ?LoggingHeaderSet $headerSet = null
    )
    {
        $this->paramSet = $paramSet;
        $this->headerSet = $headerSet;
    }
}