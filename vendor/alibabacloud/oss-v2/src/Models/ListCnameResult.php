<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * The result for the ListCname operation.
 * Class ListCnameResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class ListCnameResult extends ResultModel
{
    /**
     * The name of the bucket to which the CNAME records you want to query are mapped.
     * @var string|null
     */
    #[XmlElement(rename: 'Bucket', type: 'string')]
    public ?string $bucket;

    /**
     * The name of the bucket owner.
     * @var string|null
     */
    #[XmlElement(rename: 'Owner', type: 'string')]
    public ?string $owner;

    /**
     * The container that is used to store the information about all CNAME records.
     * @var array<CnameInfo>|null
     */
    #[XmlElement(rename: 'Cname', type: CnameInfo::class)]
    public ?array $cnames;

    /**
     * ListCnameRequest constructor.
     * @param string|null $bucket The name of the bucket to which the CNAME records you want to query are mapped.
     * @param string|null $owner The name of the bucket owner.
     * @param array<CnameInfo>|null $cnames The container that is used to store the information about all CNAME records.
     */
    public function __construct(
        ?string $bucket = null,
        ?string $owner = null,
        ?array $cnames = null
    )
    {
        $this->bucket = $bucket;
        $this->owner = $owner;
        $this->cnames = $cnames;
    }
}
