<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Annotation;

final class Functions
{
    public static function getXmlRootAnnotation(\ReflectionObject $obj): ?XmlRoot
    {
        $result = array_map(
            static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(),
            $obj->getAttributes(XmlRoot::class, \ReflectionAttribute::IS_INSTANCEOF),
        );

        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function isRequiredProperty(\ReflectionProperty $property): bool
    {
        $result = $property->getAttributes(RequiredProperty::class, \ReflectionAttribute::IS_INSTANCEOF);
        return count($result) > 0;
    }

    public static function getXmlElementAnnotation(\ReflectionProperty $property): ?XmlElement
    {
        $result = self::getPropertyAnnotationsBy($property, XmlElement::class);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function getTagAnnotation(\ReflectionProperty $property): ?TagProperty
    {
        $result = self::getPropertyAnnotationsBy($property, TagProperty::class);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function getHeaderAnnotation(\ReflectionProperty $property): ?TagProperty
    {
        $result = self::getPropertyAnnotationsBy($property, TagHeader::class);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function getQueryAnnotation(\ReflectionProperty $property): ?TagProperty
    {
        $result = self::getPropertyAnnotationsBy($property, TagQuery::class);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function getBodyAnnotation(\ReflectionProperty $property): ?TagProperty
    {
        $result = self::getPropertyAnnotationsBy($property, TagBody::class);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public static function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        return self::getPropertyAnnotationsBy($property, AnnotationInterface::class);
    }

    private static function getPropertyAnnotationsBy(\ReflectionProperty $property, string $name): array
    {
        return array_map(
            static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(),
            $property->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF),
        );
    }
}
