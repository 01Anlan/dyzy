<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlElement implements AnnotationInterface
{
    public string $rename;

    public string $type;

    public ?string $format;

    public function __construct(string $rename, string $type, ?string $format = null)
    {
        $this->rename = $rename;
        $this->type = $type;
        $this->format = $format;
    }
}
