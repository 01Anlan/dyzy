<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class XmlRoot implements AnnotationInterface
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
