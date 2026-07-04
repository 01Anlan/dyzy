<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RefererConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RefererConfiguration')]
final class RefererConfiguration extends Model
{
    /**
     * Specifies whether to allow a request whose Referer field is empty. Valid values:*   true (default)*   false
     * @var bool|null
     */
    #[XmlElement(rename: 'AllowEmptyReferer', type: 'bool')]
    public ?bool $allowEmptyReferer;

    /**
     * Specifies whether to truncate the query string in the URL when the Referer is matched. Valid values:*   true (default)*   false
     * @var bool|null
     */
    #[XmlElement(rename: 'AllowTruncateQueryString', type: 'bool')]
    public ?bool $allowTruncateQueryString;

    /**
     * Specifies whether to truncate the path and parts that follow the path in the URL when the Referer is matched. Valid values:*   true*   false
     * @var bool|null
     */
    #[XmlElement(rename: 'TruncatePath', type: 'bool')]
    public ?bool $truncatePath;

    /**
     * The container that stores the Referer whitelist.  The PutBucketReferer operation overwrites the existing Referer whitelist with the Referer whitelist specified in RefererList. If RefererList is not specified in the request, which specifies that no Referer elements are included, the operation clears the existing Referer whitelist.
     * @var RefererList|null
     */
    #[XmlElement(rename: 'RefererList', type: RefererList::class)]
    public ?RefererList $refererList;

    /**
     * The container that stores the Referer blacklist.
     * @var RefererBlacklist|null
     */
    #[XmlElement(rename: 'RefererBlacklist', type: RefererBlacklist::class)]
    public ?RefererBlacklist $refererBlacklist;

    /**
     * RefererConfiguration constructor.
     * @param bool|null $allowEmptyReferer Specifies whether to allow a request whose Referer field is empty.
     * @param bool|null $allowTruncateQueryString Specifies whether to truncate the query string in the URL when the Referer is matched.
     * @param bool|null $truncatePath Specifies whether to truncate the path and parts that follow the path in the URL when the Referer is matched.
     * @param RefererList|null $refererList The container that stores the Referer whitelist.
     * @param RefererBlacklist|null $refererBlacklist The container that stores the Referer blacklist.
     */
    public function __construct(
        ?bool $allowEmptyReferer = null,
        ?bool $allowTruncateQueryString = null,
        ?bool $truncatePath = null,
        ?RefererList $refererList = null,
        ?RefererBlacklist $refererBlacklist = null
    )
    {
        $this->allowEmptyReferer = $allowEmptyReferer;
        $this->allowTruncateQueryString = $allowTruncateQueryString;
        $this->truncatePath = $truncatePath;
        $this->refererList = $refererList;
        $this->refererBlacklist = $refererBlacklist;
    }
}