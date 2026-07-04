<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Types;

use Exception;

trait ModelTrait
{
    public function __call($method, $args)
    {
        #echo "Calling method '$method', args: ". implode(', ', $args). "\n";
        // setter
        if (strncmp($method, "set", 3) === 0) {
            $name = lcfirst(substr($method, 3));
            $this->$name = $args[0];
            return $this;
        } else if (strncmp($method, "get", 3) === 0) {
            $name = lcfirst(substr($method, 3));
            return $this->$name;
        } else {
            throw new Exception($method . "function does not exist");
        }
    }
}
