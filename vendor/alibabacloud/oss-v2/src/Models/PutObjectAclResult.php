<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;
/**
 * The result for the PutObjectAcl operation.
 * Class PutObjectAclResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class PutObjectAclResult extends ResultModel 
{
    /**
     * Version of the object.
     * @var string|null
     */
    public ?string $versionId;

    /**
     * PutObjectAclResult constructor.
     * @param string|null $versionId Version of the object.
     */
    public function __construct(
        ?string $versionId = null
    ) {
        $this->versionId = $versionId;
    }
}
