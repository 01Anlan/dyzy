<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use Psr\Http\Message\StreamInterface;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\Functions;
use AlibabaCloud\Oss\V2\Exception\SerializationExecption;
use AlibabaCloud\Oss\V2\Utils;

final class Serializer
{
    public static function serializeXml(Model $model, string $root = ''): string
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        self::serializeXmlModel($writer, $root, $model);
        $writer->endDocument();
        return $writer->flush();
    }

    private static function serializeXmlAny(\XMLWriter $writer, string $name, $value, ?string $format)
    {
        if ($value instanceof Model) {
            self::serializeXmlModel($writer, $name, $value);
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            $stamp = $value->getTimestamp();
            if ($format == null) {
                $format = 'iso8601';
            }
            switch ($format) {
                case 'httptime':
                    $tval = gmdate('D, d M Y H:i:s \G\M\T', $stamp);
                    break;
                case 'unixtime':
                    $tval = (string)$stamp;
                    break;
                default:
                    $tval = gmdate('Y-m-d\TH:i:s\Z', $stamp);
            }
            $writer->startElement($name);
            $writer->text($tval);
            $writer->endElement();
            return;
        }

        $type = \gettype($value);

        // bool
        if (\in_array($type, ['bool', 'boolean'])) {
            $writer->startElement($name);
            $writer->text($value === true ? 'true' : 'false');
            $writer->endElement();
            return;
        }

        // other primitive type
        if (\in_array($type, ['int', 'integer', 'float', 'double', 'string'])) {
            $writer->startElement($name);
            $writer->text((string)$value);
            $writer->endElement();
            return;
        }


        // enum, since 8.1
        // TODO

        throw new SerializationExecption("Unsupport type " . \gettype($value));
    }

    private static function serializeXmlModel(\XMLWriter $writer, string $name, Model $value): void
    {
        $ro = new \ReflectionObject($value);

        if (empty($name)) {
            $annotation = Functions::getXmlRootAnnotation($ro);
            if ($annotation != null) {
                $name = $annotation->name;
            }
            if (empty($name)) {
                $name = \get_class($value);
                if ($pos = \strrpos($name, '\\')) {
                    $name = \substr($name, $pos + 1);
                }
            }
        }

        // start element
        $writer->startElement($name);

        // children element
        foreach ($ro->getProperties() as $property) {
            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }
            $val = $property->getValue($value);
            if (!isset($val)) {
                continue;
            }

            $annotation = Functions::getXmlElementAnnotation($property);
            if ($annotation == null) {
                continue;
            }

            if (is_array($val)) {
                foreach ($val as $vval) {
                    self::serializeXmlAny($writer, $annotation->rename, $vval, $annotation->format);
                }
            } else {
                self::serializeXmlAny($writer, $annotation->rename, $val, $annotation->format);
            }
        }

        // end element
        $writer->endElement();
    }

    public static function serializeInput(RequestModel $request, OperationInput $input, array $customSerializer = []): OperationInput
    {
        $ro = new \ReflectionObject($request);

        //headers
        $hp = $ro->getProperty('headers');
        if (PHP_VERSION_ID < 80100) {
            $hp->setAccessible(true);
        }
        $h = $hp->getValue($request);
        if (is_array($h)) {
            foreach ($h as $key => $value) {
                $input->setHeader($key, (string)$value);
            }
        }

        //parameters
        $pp = $ro->getProperty('parameters');
        if (PHP_VERSION_ID < 80100) {
            $pp->setAccessible(true);
        }
        $p = $pp->getValue($request);
        if (is_array($p)) {
            foreach ($p as $key => $value) {
                $input->setParameter($key, (string) $value);
            }
        }

        //payload
        $pd = $ro->getProperty('payload');
        if (PHP_VERSION_ID < 80100) {
            $pd->setAccessible(true);
        }
        $payload = $pd->getValue($request);
        if ($payload instanceof StreamInterface) {
            $input->setBody($payload);
        }

        // all properties in request
        foreach ($ro->getProperties() as $property) {
            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }
            $val = $property->getValue($request);
            if (!isset($val)) {
                if (Functions::isRequiredProperty($property)) {
                    throw new \InvalidArgumentException('missing required field, ' . $property->getName() . '.');
                }
                continue;
            }

            $annotation = Functions::getTagAnnotation($property);
            if ($annotation == null) {
                continue;
            }

            switch ($annotation->position) {
                case 'query':
                    $input->setParameter(
                        $annotation->rename,
                        self::castToString($val, $annotation->format)
                    );
                    break;
                case 'header':
                    if ($annotation->format === 'usermeta' && \is_array($val)) {
                        //user metadata
                        foreach ($val as $k => $v) {
                            $input->setHeader(
                                (string)$annotation->rename . $k,
                                (string)$v
                            );
                        }
                    } else {
                        $input->setHeader(
                            $annotation->rename,
                            self::castToString($val, $annotation->format)
                        );
                    }
                    break;
                case 'body':
                    $body = $val;
                    if ($annotation->type === 'xml') {
                        $body = self::serializeXml($val, $annotation->rename);
                    }
                    $input->setBody(Utils::streamFor($body));
                    break;
            };
        }

        // custom serializer
        foreach ($customSerializer as $serializer) {
            if (\is_callable($serializer)) {
                $serializer($request, $input);
            } else {
                call_user_func($serializer, $request, $input);
            }
        }

        return $input;
    }

    private static function castToString($value, ?string $format): string
    {
        if ($value instanceof \DateTimeInterface) {
            $stamp = $value->getTimestamp();
            if ($format == null) {
                $format = 'iso8601';
            }
            switch ($format) {
                case 'httptime':
                    $tval = gmdate('D, d M Y H:i:s \G\M\T', $stamp);
                    break;
                case 'unixtime':
                    $tval = (string)$stamp;
                    break;
                default:
                    $tval = gmdate('Y-m-d\TH:i:s\Z', $stamp);
            }

            return $tval;
        }

        $type = \gettype($value);

        // bool
        if (\in_array($type, ['bool', 'boolean'])) {
            return $value === true ? 'true' : 'false';
        }

        // other primitive type
        if (\in_array($type, ['int', 'integer', 'float', 'double', 'string'])) {
            return (string)$value;
        }

        throw new SerializationExecption("Unsupport type " . \gettype($value));
    }
}
