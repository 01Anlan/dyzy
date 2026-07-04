<?php

namespace UnitTests\Types;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Types\ModelTrait;

/**
 * 
 * @method static setStrValue(string $arg)
 * @method ?string getStrValue()
 * @method static setIntValue(int $arg)
 * @method ?int getIntValue()
 * @method static setBoolValue(bool $arg)
 * @method ?bool getBoolValue()
 * @method static setFloatValue(float $arg)
 * @method ?float getFloatValue()
 */
class ModelPrivate extends Model
{
    use ModelTrait;
    private ?string $strValue;

    private ?int $intValue;

    private ?bool $boolValue;

    private ?float $floatValue;

    public function __construct(
        ?string $strValue = null,
        ?int $intValue = null,
        ?bool $boolValue = null,
        ?float $floatValue = null
    ) {
        $this->strValue = $strValue;
        $this->intValue = $intValue;
        $this->boolValue = $boolValue;
        $this->floatValue = $floatValue;
    }
}
