<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\TagBody;

/**
 * The result for the GetCnameToken operation.
 * Class GetCnameTokenResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetCnameTokenResult extends ResultModel
{
    /**
     * The container in which the CNAME token is stored.
     * @var CnameToken|null
     */
    #[TagBody(rename: 'CnameToken', type: CnameToken::class, format: 'xml')]
    public ?CnameToken $cnameToken;

    /**
     * GetCnameTokenRequest constructor.
     * @param CnameToken|null $cnameToken The container in which the CNAME token is stored.
     */
    public function __construct(
        ?CnameToken $cnameToken = null
    )
    {
        $this->cnameToken = $cnameToken;
    }
}
