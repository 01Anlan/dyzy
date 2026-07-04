<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketArchiveDirectRead
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketArchiveDirectRead
{
    /**
     * @param Models\GetBucketArchiveDirectReadRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketArchiveDirectRead(Models\GetBucketArchiveDirectReadRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketArchiveDirectRead',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('bucketArchiveDirectRead', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['bucketArchiveDirectRead',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketArchiveDirectReadResult
     */
    public static function toGetBucketArchiveDirectRead(OperationOutput $output): Models\GetBucketArchiveDirectReadResult
    {
        $result = new Models\GetBucketArchiveDirectReadResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutBucketArchiveDirectReadRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketArchiveDirectRead(Models\PutBucketArchiveDirectReadRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketArchiveDirectRead',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('bucketArchiveDirectRead', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['bucketArchiveDirectRead',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketArchiveDirectReadResult
     */
    public static function toPutBucketArchiveDirectRead(OperationOutput $output): Models\PutBucketArchiveDirectReadResult
    {
        $result = new Models\PutBucketArchiveDirectReadResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
