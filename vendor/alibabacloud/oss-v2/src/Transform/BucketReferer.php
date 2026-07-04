<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketReferer
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketReferer
{
    /**
     * @param Models\PutBucketRefererRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketReferer(Models\PutBucketRefererRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketReferer',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('referer', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['referer',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketRefererResult
     */
    public static function toPutBucketReferer(OperationOutput $output): Models\PutBucketRefererResult
    {
        $result = new Models\PutBucketRefererResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketRefererRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketReferer(Models\GetBucketRefererRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketReferer',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('referer', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['referer',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketRefererResult
     */
    public static function toGetBucketReferer(OperationOutput $output): Models\GetBucketRefererResult
    {
        $result = new Models\GetBucketRefererResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
