<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Types\ResultModel;
use AlibabaCloud\Oss\V2\Annotation\Functions;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Exception\DeserializationExecption;
use AlibabaCloud\Oss\V2\Utils;

final class Deserializer
{
    public static function deserializeXml(string $value, $className, string $expect = ''): Model
    {
        try {
            $xml = Utils::parseXml($value);
        } catch (\Exception $e) {
            throw new DeserializationExecption(
                'Error parsing XML: part data ' . substr($value, 0, 256), $e);
        }

        //check expect root name
        if (
            !empty($xml) &&
            !empty($expect) &&
            !preg_match("/<$expect([^>]*)>/", $value)
        ) {
            throw new DeserializationExecption("Not found tag <$expect>");
        }

        return self::deserializeXmlModel($xml, $className);
    }

    private static function deserializeXmlAny(\SimpleXMLElement $element, string $type, XmlElement $annotation)
    {
        $values = [];
        foreach ($element as $item) {
            switch ($annotation->type) {
                case "bool":
                    $vv = self::castToBool($item->__toString());
                    break;
                case "string":
                    $vv = $item->__toString();
                    break;
                case "int":
                    $vv = self::castToInt($item->__toString());
                    break;
                case "float":
                    $vv = self::castToFloat($item->__toString());
                    break;
                case "DateTime":
                    $vv = self::castToDatetime($item->__toString(), $annotation->format);
                    break;
                default:
                    $vv = self::deserializeXmlModel($item, $annotation->type);
            }
            array_push($values, $vv);
        }

        if (\in_array($type, ['?array', 'array'])) {
            return $values;
        }
        return $values[0];
    }

    private static function deserializeXmlModel(\SimpleXMLElement $element, $class): Model
    {
        if (\is_object($class)) {
            if (!($class instanceof Model)) {
                throw new DeserializationExecption($class . " is not subclass of Model");
            }
            $rc = new \ReflectionObject($class);
            $obj = $class;
        } else {
            $rc = new \ReflectionClass($class);
            $obj = $rc->newInstance();
            if (!($obj instanceof Model)) {
                throw new DeserializationExecption($class . " is not subclass of Model");
            }
        }

        // children element
        foreach ($rc->getProperties() as $property) {
            $annotation = Functions::getXmlElementAnnotation($property);
            if ($annotation == null) {
                continue;
            }

            $name = $annotation->rename;
            if (!isset($element->$name)) {
                continue;
            }

            $type = (string)$property->getType();
            $value = self::deserializeXmlAny($element->$name, $type, $annotation);
            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }
            $property->setValue($obj, $value);
        }

        return $obj;
    }

    private static function castToBool(string $value): bool
    {
        $v = strtolower($value);
        if ('true' === $v || '1' === $v) {
            return true;
        } elseif ('false' === $v || '0' === $v) {
            return false;
        } else {
            throw new DeserializationExecption(
                sprintf('Could not convert data to boolean. Expected "true", "false", "1" or "0", but got %s.', $value)
            );
        }
    }

    private static function castToInt(string $value): int
    {
        return (int)$value;
    }

    private static function castToFloat(string $value): float
    {
        return (float)$value;
    }

    private static function castToAny(string $value, string $type, ?string $format)
    {
        switch ($type) {
            case "bool":
                $vv = self::castToBool($value);
                break;
            case "string":
                $vv = $value;
                break;
            case "int":
                $vv = self::castToInt($value);
                break;
            case "float":
                $vv = self::castToFloat($value);
                break;
            case "DateTime":
                $vv = self::castToDatetime($value, $format);
                break;
            default:
                throw new DeserializationExecption("Unsupport type:$type");
        }
        return $vv;
    }

    private static function castToDatetime(string $value, ?string $format): \DateTime
    {
        switch ($format) {
            case 'httptime':
                return \DateTime::createFromFormat(
                    'D, d M Y H:i:s \G\M\T',
                    $value,
                    new \DateTimeZone('UTC')
                );
            case 'unixtime':
                $datetime = new \DateTime();
                $datetime->setTimestamp((int)$value);
                return $datetime;
        }
        return \DateTime::createFromFormat(
            'Y-m-d\TH:i:s\Z',
            $value,
            new \DateTimeZone('UTC')
        );
    }

    public static function deserializeOutput(ResultModel $result, OperationOutput $output, array $customDeserializer = []): void
    {
        $ro = new \ReflectionObject($result);

        //common part
        $p = $ro->getProperty('status');
        if (PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }
        $p->setValue($result, $output->getStatus());

        $p = $ro->getProperty('statusCode');
        if (PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }
        $p->setValue($result, $output->GetStatusCode());

        $headers = $output->getHeaders() ?? [];
        $p = $ro->getProperty('headers');
        if (PHP_VERSION_ID < 80100) {
            $p->setAccessible(true);
        }
        $p->setValue($result, $headers);

        if (isset($headers['x-oss-request-id'])) {
            $p = $ro->getProperty('requestId');
            if (PHP_VERSION_ID < 80100) {
                $p->setAccessible(true);
            }
            $p->setValue($result, $headers['x-oss-request-id']);
        }

        // custom deserializer
        foreach ($customDeserializer as $deserializer) {
            if (\is_callable($deserializer)) {
                $deserializer($result, $output);
            } else {
                call_user_func($deserializer, $result, $output);
            }
        }
    }

    public static function deserializeOutputHeaders(ResultModel $result, OperationOutput $output)
    {
        $headers = $output->getHeaders();
        if (empty($headers)) {
            return;
        }

        $usermetas = [];
        $ro = new \ReflectionObject($result);
        foreach ($ro->getProperties() as $property) {
            $annotation = Functions::getHeaderAnnotation($property);
            if ($annotation == null) {
                continue;
            }

            #usermeta
            if ($annotation->format === 'usermeta') {
                \array_push($usermetas, ['property' => $property, 'annotation' => $annotation]);
                continue;
            }

            $name = $annotation->rename;
            if (!\array_key_exists($name, $headers)) {
                continue;
            }

            $value = self::castToAny($headers[$name], $annotation->type, $annotation->format);
            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }
            $property->setValue($result, $value);
        }

        foreach ($usermetas as $item) {
            $annotation = $item['annotation'];
            $property = $item['property'];
            $prefix = strtolower($annotation->rename);
            $len = strlen($prefix);
            $meta = [];
            foreach ($headers as $key => $value) {
                if (strncasecmp($key, $prefix, $len) == 0) {
                    $meta[strtolower(substr($key, $len))] = $value;
                }
            }

            if (count($meta) > 0) {
                if (PHP_VERSION_ID < 80100) {
                    $property->setAccessible(true);
                }
                $property->setValue($result, $meta);
            }
        }
    }

    public static function deserializeOutputBody(ResultModel $result, OperationOutput $output)
    {
        //#[TagProperty(tag: 'output', position: 'body', rename: '...', type: 'xml')]
        $body = $output->getBody();
        if ($body == null) {
            return;
        }

        $content = $body->getContents();

        if ($content === '') {
            return;
        }

        $ro = new \ReflectionObject($result);
        foreach ($ro->getProperties() as $property) {
            $annotation = Functions::getBodyAnnotation($property);
            if ($annotation == null) {
                continue;
            }

            if ('xml' === $annotation->format) {
                $value = self::deserializeXml($content, $annotation->type, $annotation->rename);
                if (PHP_VERSION_ID < 80100) {
                    $property->setAccessible(true);
                }
                $property->setValue($result, $value);
            } else {
                throw new DeserializationExecption("Unsupport body format:$annotation->format");
            }

            // only one body tag
            break;
        }
    }

    public static function deserializeOutputInnerBody(ResultModel $result, OperationOutput $output)
    {
        $body = $output->getBody();
        if ($body == null) {
            return;
        }

        $content = $body->getContents();

        if ($content === '') {
            return;
        }

        self::deserializeXml($content, $result);
    }
}
