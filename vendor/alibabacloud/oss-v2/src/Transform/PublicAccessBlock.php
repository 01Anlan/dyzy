<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class PublicAccessBlock
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class PublicAccessBlock
{
    /**
     * @param Models\GetPublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromGetPublicAccessBlock(Models\GetPublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetPublicAccessBlock',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetPublicAccessBlockResult
     */
    public static function toGetPublicAccessBlock(OperationOutput $output): Models\GetPublicAccessBlockResult
    {
        $result = new Models\GetPublicAccessBlockResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutPublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromPutPublicAccessBlock(Models\PutPublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutPublicAccessBlock',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutPublicAccessBlockResult
     */
    public static function toPutPublicAccessBlock(OperationOutput $output): Models\PutPublicAccessBlockResult
    {
        $result = new Models\PutPublicAccessBlockResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeletePublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromDeletePublicAccessBlock(Models\DeletePublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeletePublicAccessBlock',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeletePublicAccessBlockResult
     */
    public static function toDeletePublicAccessBlock(OperationOutput $output): Models\DeletePublicAccessBlockResult
    {
        $result = new Models\DeletePublicAccessBlockResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}