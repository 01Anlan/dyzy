<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class NoncurrentVersionExpiration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'NoncurrentVersionExpiration')]
final class NoncurrentVersionExpiration extends Model
{   
    /**
     * The number of days from when the objects became previous versions to when the lifecycle rule takes effect.
     * @var int|null
     */
    #[XmlElement(rename: 'NoncurrentDays', type: 'int')]
    public ?int $noncurrentDays;
    

    /**
     * NoncurrentVersionExpiration constructor.
     * @param int|null $noncurrentDays The number of days from when the objects became previous versions to when the lifecycle rule takes effect.
     */
    public function __construct(
        ?int $noncurrentDays = null
    )
    {   
        $this->noncurrentDays = $noncurrentDays;
    }
}