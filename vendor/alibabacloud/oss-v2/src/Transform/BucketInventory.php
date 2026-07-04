<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketInventory
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketInventory
{
    /**
     * @param Models\PutBucketInventoryRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketInventory(Models\PutBucketInventoryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketInventory',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('inventory', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['inventory', 'inventoryId']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketInventoryResult
     */
    public static function toPutBucketInventory(OperationOutput $output): Models\PutBucketInventoryResult
    {
        $result = new Models\PutBucketInventoryResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketInventoryRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketInventory(Models\GetBucketInventoryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketInventory',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('inventory', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['inventory', 'inventoryId']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketInventoryResult
     */
    public static function toGetBucketInventory(OperationOutput $output): Models\GetBucketInventoryResult
    {
        $result = new Models\GetBucketInventoryResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\ListBucketInventoryRequest $request
     * @return OperationInput
     */
    public static function fromListBucketInventory(Models\ListBucketInventoryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListBucketInventory',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('inventory', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['inventory',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListBucketInventoryResult
     */
    public static function toListBucketInventory(OperationOutput $output): Models\ListBucketInventoryResult
    {
        $result = new Models\ListBucketInventoryResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketInventoryRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketInventory(Models\DeleteBucketInventoryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketInventory',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('inventory', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['inventory', 'inventoryId']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketInventoryResult
     */
    public static function toDeleteBucketInventory(OperationOutput $output): Models\DeleteBucketInventoryResult
    {
        $result = new Models\DeleteBucketInventoryResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
