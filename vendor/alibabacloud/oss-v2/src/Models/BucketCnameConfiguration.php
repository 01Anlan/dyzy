<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class BucketCnameConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'BucketCnameConfiguration')]
final class BucketCnameConfiguration extends Model
{
    /**
     * The container that stores the CNAME information.
     * @var Cname|null
     */
    #[XmlElement(rename: 'Cname', type: Cname::class)]
    public ?Cname $cname;

    /**
     * BucketCnameConfiguration constructor.
     * @param Cname|null $cname The container that stores the CNAME information.
     */
    public function __construct(
        ?Cname $cname = null
    )
    {
        $this->cname = $cname;
    }
}