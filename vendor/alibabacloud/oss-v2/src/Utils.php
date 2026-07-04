<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7;

/**
 * Class Utils
 * @package AlibabaCloud\Oss\V2
 */
final class Utils
{

    /**
     * Create a new stream based on the input type.
     *
     * Options is an associative array that can contain the following keys:
     * - metadata: Array of custom metadata.
     * - size: Size of the stream.
     */
    public static function streamFor($resource = '', array $options = []): StreamInterface
    {
        return Psr7\Utils::streamFor($resource, $options);
    }

    /**
     * Determines the mimetype of a file by looking at its extension.
     */
    public static function guessContentType(string $name, ?string $default = null): ?string
    {
        return Psr7\MimeType::fromFilename($name) ?? $default;
    }

    /**
     * Calculate a content-md5 of a stream.
     */
    public static function calcContentMd5(StreamInterface $stream): string
    {
        return base64_encode(Psr7\Utils::hash($stream, 'md5', true));
    }

    public static function safetyBool($value): bool
    {
        if (($value === true) || ($value === 'true')) {
            return true;
        }
        return false;
    }

    public static function safetyString($value): string
    {
        if ((string)$value === $value) {
            return $value;
        }
        return '';
    }

    public static function safetyFloat($value): float
    {
        if ((float)$value === $value) {
            return $value;
        }
        return 0;
    }

    public static function safetyInt($value): int
    {
        if ((int)$value === $value) {
            return $value;
        }
        return 0;
    }

    public static function addScheme(string $endpoint, bool $disableSsl): string
    {
        $pattern = '/^([^:]+):\/\//';
        if ($endpoint !== '' && !preg_match($pattern, $endpoint)) {
            $scheme = Defaults::ENDPOINT_SCHEME;
            if ($disableSsl === true) {
                $scheme = 'http';
            }
            $endpoint = "$scheme://$endpoint";
        }
        return $endpoint;
    }

    public static function regionToEndpoint(string $region, bool $disableSsl, string $type): string
    {
        $scheme = Defaults::ENDPOINT_SCHEME;
        if ($disableSsl === true) {
            $scheme = 'http';
        }

        switch ($type) {
            case 'internal':
                $endpoint = "oss-$region-internal.aliyuncs.com";
                break;
            case 'dualstack':
                $endpoint = "$region.oss.aliyuncs.com";
                break;
            case 'accelerate':
                $endpoint = 'oss-accelerate.aliyuncs.com';
                break;
            case 'overseas':
                $endpoint = 'oss-accelerate-overseas.aliyuncs.com';
            default:
                $endpoint = "oss-$region.aliyuncs.com";
                break;
        }

        return "$scheme://$endpoint";
    }

    public static function defaultUserAgent(): string
    {
        return 'alibabacloud-php-sdk-v2/' . Version::VERSION . " (" . php_uname('s') . "/" . php_uname('r') . "/" . php_uname('m') . ";" . PHP_VERSION . ")";
    }

    /**
     * URL encode
     */
    public static function urlEncode(string $key, bool $ignore = false): string
    {
        $value = rawurlencode($key);
        if ($ignore) {
            return str_replace('%2F', '/', $value);
        }
        return $value;
    }

    /**
     * Check if the endpoint is in the IPv4 format, such as xxx.xxx.xxx.xxx:port or xxx.xxx.xxx.xxx.
     */
    public static function isIPFormat($endpoint): bool
    {
        $ip_array = explode(":", $endpoint);
        $hostname = $ip_array[0];
        $ret = filter_var($hostname, \FILTER_VALIDATE_IP);
        if (!$ret) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * change array[string => string[]] to array[string => string]
     */
    public static function toSimpleArray(array $value): array
    {
        $result = [];
        foreach ($value as $k => $vv) {
            if (\is_array($vv)) {
                $result[$k] = $vv[0];
            } else {
                $result[$k] = $vv;
            }
        }
        return $result;
    }

    /**
     * change array[string => string[]] to array[string => string]
     */
    public static function findXmlElementText(string $xml, string $tag): string
    {
        $start = \strpos($xml, "<$tag>");
        $len = strlen($tag) + 2;
        if ($start === false) {
            return '';
        }

        $end = \strpos($xml, "</$tag>", $start + $len);
        if ($end === false) {
            return '';
        }

        return \substr($xml, $start + $len, $end - $start - $len);
    }

    public static function parseXml(string $value): \SimpleXMLElement
    {
        $priorSetting = libxml_use_internal_errors(true);
        try {
            libxml_clear_errors();
            $xml = new \SimpleXMLElement($value);
            if ($error = libxml_get_last_error()) {
                throw new \RuntimeException($error->message);
            }
        } catch (\Exception $e) {
            throw new Exception\ParserException(
                "Error parsing XML: {$e->getMessage()}",
                $e,
            );
        } finally {
            libxml_use_internal_errors($priorSetting);
        }

        return $xml;
    }

    /**
     * escape to the properly escaped XML equivalent of the plain text data s.
     * @param string $s
     * @return string
     */
    public static function escapeXml(string $s): string
    {
        $result = '';
        $length = strlen($s);
        for ($i = 0; $i < $length; $i++) {
            $d = $s[$i];
            switch ($d) {
                case '"':
                    $result .= '&quot;';
                    break;
                case '&':
                    $result .= '&amp;';
                    break;
                case '<':
                    $result .= '&lt;';
                    break;
                case '>':
                    $result .= '&gt;';
                    break;
                case "\t":
                    $result .= '&#09;';
                    break;
                case "\n":
                    $result .= '&#10;';
                    break;
                case "\r":
                    $result .= '&#13;';
                    break;
                default:
                    $n = ord($d);
                    if ($n < 0x20) {
                        $result .= sprintf('&#%02d;', $n);
                    } else {
                        $result .= $d;
                    }
            }
        }
        return $result;
    }

    public static function copyRequest($dst, $src): void
    {
        if (
            !($dst instanceof Types\RequestModel) ||
            !($dst instanceof Types\RequestModel)
        ) {
            throw new \InvalidArgumentException('dst or src is not subclass of RequestModel');
        }

        $dstRo = new \ReflectionObject($dst);
        $srcRo = new \ReflectionObject($src);
        foreach ($dstRo->getProperties() as $property) {
            if ($srcRo->hasProperty($property->getName())) {
                $pp = $srcRo->getProperty($property->getName());
                if (PHP_VERSION_ID < 80100) {
                    $pp->setAccessible(true);
                }
                $v = $pp->getValue($src);
                if (isset($v)) {
                    if (PHP_VERSION_ID < 80100) {
                        $property->setAccessible(true);
                    }
                    $property->setValue($dst, $v);
                }
            }
        }
    }

    public static function copyResult($dst, $src): void
    {
        if (
            !($dst instanceof Types\ResultModel) ||
            !($dst instanceof Types\ResultModel)
        ) {
            throw new \InvalidArgumentException('dst or src is not subclass of ResultModel');
        }

        $dstRo = new \ReflectionObject($dst);
        $srcRo = new \ReflectionObject($src);
        foreach ($dstRo->getProperties() as $property) {
            if ($srcRo->hasProperty($property->getName())) {
                $pp = $srcRo->getProperty($property->getName());
                if (PHP_VERSION_ID < 80100) {
                    $pp->setAccessible(true);
                }
                $v = $pp->getValue($src);
                if (isset($v)) {
                    if (PHP_VERSION_ID < 80100) {
                        $property->setAccessible(true);
                    }
                    $property->setValue($dst, $v);
                }
            }
        }
    }

    /**
     * Parses a http range string into array.
     *
     * @param string $range Http range string (e.g., "bytes=0-1023")
     *
     * @return array | false
     */
    static function parseHttpRange($range)
    {
        // doesn't start with bytes=. or empty
        if (strncmp($range, 'bytes=', 6) != 0) {
            return false;
        }
        $range = substr($range, 6);

        // contains multiple ranges which isn't supported.
        if (strpos($range, ',') !== false) {
            return false;
        }

        // contains no '-'
        $vals = explode('-', $range);
        if (count($vals) != 2) {
            return false;
        }

        $start = -1;
        if (strlen($vals[0]) > 0) {
            $val = intval($vals[0]);
            if ($val == 0 && $vals[0] !== '0') {
                return false;
            }
            $start = $val;
        }

        $end = -1;
        if (strlen($vals[1]) > 0) {
            $val = intval($vals[1]);
            if ($val == 0 && $vals[1] !== '0') {
                return false;
            }
            $end = $val;
        }

        return [$start, $end];
    }

    /**
     * Parses a content range string into array.
     *
     * @param string $range Http range string (e.g., "bytes 0-10239/25723")
     *
     * @return array | false
     */
    static function parseContentRange($range)
    {
        // doesn't start with 'bytes ' or empty
        if (strncmp($range, 'bytes ', 6) != 0) {
            return false;
        }
        $range = substr($range, 6);

        // contains no '-'
        $vals = explode('-', $range);
        if (count($vals) != 2) {
            return false;
        }

        // start
        $vals[0] = trim($vals[0]);
        if ($vals[0] == '') {
            return false;
        }
        $start = intval($vals[0]);
        if ($start < 0 || ($start == 0 && $vals[0] != '0')) {
            return false;
        }

        $vals = explode('/', $vals[1]);

        // end
        $vals[0] = trim($vals[0]);
        if ($vals[0] == '') {
            return false;
        }
        $end = intval($vals[0]);
        if ($end < 0 || ($end == 0 && $vals[0] != '0')) {
            return false;
        }

        // total
        $vals[1] = trim($vals[1]);
        if ($vals[1] == '*') {
            $total = -1;
        } else {
            $total = intval($vals[1]);
            if ($total < 0 || ($total == 0 && $vals[1] != '0')) {
                return false;
            }
        }

        return [$start, $end, $total];
    }
}
