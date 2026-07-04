<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketOverwriteConfig
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketOverwriteConfig
{
    /**
     * @param Models\PutBucketOverwriteConfigRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketOverwriteConfig(Models\PutBucketOverwriteConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketOverwriteConfig',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('overwriteConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['overwriteConfig',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketOverwriteConfigResult
     */
    public static function toPutBucketOverwriteConfig(OperationOutput $output): Models\PutBucketOverwriteConfigResult
    {
        $result = new Models\PutBucketOverwriteConfigResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketOverwriteConfigRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketOverwriteConfig(Models\GetBucketOverwriteConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketOverwriteConfig',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('overwriteConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['overwriteConfig',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketOverwriteConfigResult
     */
    public static function toGetBucketOverwriteConfig(OperationOutput $output): Models\GetBucketOverwriteConfigResult
    {
        $result = new Models\GetBucketOverwriteConfigResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketOverwriteConfigRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketOverwriteConfig(Models\DeleteBucketOverwriteConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketOverwriteConfig',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('overwriteConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['overwriteConfig',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketOverwriteConfigResult
     */
    public static function toDeleteBucketOverwriteConfig(OperationOutput $output): Models\DeleteBucketOverwriteConfigResult
    {
        $result = new Models\DeleteBucketOverwriteConfigResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
