<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class AccessPoints
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'AccessPoints')]
final class AccessPoints extends Model
{   
    /**
     * The container that stores the information about all access point.
     * @var array<AccessPoint>|null
     */
    #[XmlElement(rename: 'AccessPoint', type: AccessPoint::class)]
    public ?array $accessPoints;

    /**
     * AccessPoints constructor.
     * @param array<AccessPoint>|null $accessPoints The container that stores the information about all access point.
     */
    public function __construct(
        ?array $accessPoints = null
    )
    {   
        $this->accessPoints = $accessPoints;
    }
}