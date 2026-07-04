<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class LifecycleConfiguration
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'LifecycleConfiguration')]
final class LifecycleConfiguration extends Model
{
    /**
     * The container that stores the lifecycle rules. The period of time after which objects expire must be longer than the period of time after which the storage class of the same objects is converted to Infrequent Access (IA) or Archive.
     * @var array<LifecycleRule>|null
     */
    #[XmlElement(rename: 'Rule', type: LifecycleRule::class)]
    public ?array $rules;


    /**
     * LifecycleConfiguration constructor.
     * @param array<LifecycleRule>|null $rules The container that stores the lifecycle rules.
     */
    public function __construct(
        ?array $rules = null
    )
    {
        $this->rules = $rules;
    }
}