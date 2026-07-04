<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketHttpsConfig
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketHttpsConfig
{
    /**
     * @param Models\GetBucketHttpsConfigRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketHttpsConfig(Models\GetBucketHttpsConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketHttpsConfig',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('httpsConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['httpsConfig',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketHttpsConfigResult
     */
    public static function toGetBucketHttpsConfig(OperationOutput $output): Models\GetBucketHttpsConfigResult
    {
        $result = new Models\GetBucketHttpsConfigResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutBucketHttpsConfigRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketHttpsConfig(Models\PutBucketHttpsConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketHttpsConfig',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('httpsConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['httpsConfig',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketHttpsConfigResult
     */
    public static function toPutBucketHttpsConfig(OperationOutput $output): Models\PutBucketHttpsConfigResult
    {
        $result = new Models\PutBucketHttpsConfigResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
