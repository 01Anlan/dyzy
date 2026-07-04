<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketResourceGroup
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketResourceGroup
{
    /**
     * @param Models\GetBucketResourceGroupRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketResourceGroup(Models\GetBucketResourceGroupRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketResourceGroup',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('resourceGroup', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['resourceGroup',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketResourceGroupResult
     */
    public static function toGetBucketResourceGroup(OperationOutput $output): Models\GetBucketResourceGroupResult
    {
        $result = new Models\GetBucketResourceGroupResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutBucketResourceGroupRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketResourceGroup(Models\PutBucketResourceGroupRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketResourceGroup',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('resourceGroup', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['resourceGroup',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketResourceGroupResult
     */
    public static function toPutBucketResourceGroup(OperationOutput $output): Models\PutBucketResourceGroupResult
    {
        $result = new Models\PutBucketResourceGroupResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
