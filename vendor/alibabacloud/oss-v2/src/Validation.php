<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

/**
 * Class Validation
 * @package AlibabaCloud\Oss\V2
 */
final class Validation
{
    /**
     * Checks if the region is valid
     * region naming rules
     * 1. Can only include lowercase letters, numbers, or dashes
     */
    public static function isValidRegion(string $region): bool
    {
        $pattern = '/^[a-z0-9-]+$/';
        if (!preg_match($pattern, $region)) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the bucket name is valid
     * bucket naming rules
     * 1. Can only include lowercase letters, numbers, or dashes
     * 2. Must start and end with lowercase letters or numbers
     * 3. Must be within a length from 3 to 63 bytes.
     */
    public static function isValidBucketName(string $bucket): bool
    {
        $pattern = '/^[a-z0-9][a-z0-9-]{2,62}$/';
        if (!preg_match($pattern, $bucket)) {
            return false;
        }
        return true;
    }

    /**
     * Checks if object name is valid
     * object naming rules:
     * 1. Must be within a length from 1 to 1023 bytes
     */
    public static function isValidObjectName(string $object): bool
    {
        $pattern = '/^.{1,1023}$/';
        if (!preg_match($pattern, $object)) {
            return false;
        }
        return true;
    }
}
