<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketTransferAcceleration
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketTransferAcceleration
{
    /**
     * @param Models\PutBucketTransferAccelerationRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketTransferAcceleration(Models\PutBucketTransferAccelerationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketTransferAcceleration',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('transferAcceleration', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['transferAcceleration',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketTransferAccelerationResult
     */
    public static function toPutBucketTransferAcceleration(OperationOutput $output): Models\PutBucketTransferAccelerationResult
    {
        $result = new Models\PutBucketTransferAccelerationResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketTransferAccelerationRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketTransferAcceleration(Models\GetBucketTransferAccelerationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketTransferAcceleration',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('transferAcceleration', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['transferAcceleration',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketTransferAccelerationResult
     */
    public static function toGetBucketTransferAcceleration(OperationOutput $output): Models\GetBucketTransferAccelerationResult
    {
        $result = new Models\GetBucketTransferAccelerationResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
