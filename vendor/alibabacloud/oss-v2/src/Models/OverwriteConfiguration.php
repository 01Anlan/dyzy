<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class OverwriteConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'OverwriteConfiguration')]
final class OverwriteConfiguration extends Model
{
    /**
     * List of overwrite protection rules. A bucket can have a maximum of 100 rules.
     * @var array<OverwriteRule>|null
     */
    #[XmlElement(rename: 'Rule', type: OverwriteRule::class)]
    public ?array $rules;

    /**
     * OverwriteConfiguration constructor.
     * @param array<OverwriteRule>|null $rules List of overwrite protection rules.
     */
    public function __construct(
        ?array $rules = null
    )
    {
        $this->rules = $rules;
    }
}