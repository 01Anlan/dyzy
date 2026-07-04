<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Rule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Rule')]
final class OverwriteRule extends Model
{
    /**
     * The operation type. Currently, only `forbid` (prohibit overwrites) is supported.
     * @var string|null
     */
    #[XmlElement(rename: 'Action', type: 'string')]
    public ?string $action;

    /**
     * The prefix of object names to filter the objects that you want to process. The maximum length is 1,023 characters. Each rule can have at most one prefix. Prefixes and suffixes do not support regular expressions.
     * @var string|null
     */
    #[XmlElement(rename: 'Prefix', type: 'string')]
    public ?string $prefix;

    /**
     * The suffix of object names to filter the objects that you want to process. The maximum length is 1,023 characters. Each rule can have at most one suffix. Prefixes and suffixes do not support regular expressions.
     * @var string|null
     */
    #[XmlElement(rename: 'Suffix', type: 'string')]
    public ?string $suffix;

    /**
     * A collection of authorized entities. The usage is similar to the `Principal` element in a bucket policy. You can specify an Alibaba Cloud account, a RAM user, or a RAM role. If this element is empty or not configured, overwrites are prohibited for all objects that match the prefix and suffix conditions.
     * @var OverwritePrincipals|null
     */
    #[XmlElement(rename: 'Principals', type: OverwritePrincipals::class)]
    public ?OverwritePrincipals $principals;

    /**
     * The unique identifier of the rule. If you do not specify this element, a UUID is randomly generated. If you specify this element, the value must be unique. Different rules cannot have the same ID.
     * @var string|null
     */
    #[XmlElement(rename: 'ID', type: 'string')]
    public ?string $id;

    /**
     * Rule constructor.
     * @param string|null $action The operation type.
     * @param string|null $prefix The prefix of object names to filter the objects that you want to process.
     * @param string|null $suffix The suffix of object names to filter the objects that you want to process.
     * @param OverwritePrincipals|null $principals A collection of authorized entities.
     * @param string|null $id The unique identifier of the rule.
     */
    public function __construct(
        ?string $action = null,
        ?string $prefix = null,
        ?string $suffix = null,
        ?OverwritePrincipals $principals = null,
        ?string $id = null
    )
    {
        $this->action = $action;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->principals = $principals;
        $this->id = $id;
    }
}