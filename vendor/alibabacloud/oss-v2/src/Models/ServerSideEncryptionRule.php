<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class ServerSideEncryptionRule
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'ServerSideEncryptionRule')]
final class ServerSideEncryptionRule extends Model
{   
    /**
     * The container that stores the default server-side encryption method.
     * @var ApplyServerSideEncryptionByDefault|null
     */
    #[XmlElement(rename: 'ApplyServerSideEncryptionByDefault', type: ApplyServerSideEncryptionByDefault::class)]
    public ?ApplyServerSideEncryptionByDefault $applyServerSideEncryptionByDefault;
    

    /**
     * ServerSideEncryptionRule constructor.
     * @param ApplyServerSideEncryptionByDefault|null $applyServerSideEncryptionByDefault The container that stores the default server-side encryption method.
     */
    public function __construct(
        ?ApplyServerSideEncryptionByDefault $applyServerSideEncryptionByDefault = null
    )
    {   
        $this->applyServerSideEncryptionByDefault = $applyServerSideEncryptionByDefault;
    }
}