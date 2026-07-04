<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketRedundancyTransition
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketRedundancyTransition
{
    /**
     * @param Models\ListBucketDataRedundancyTransitionRequest $request
     * @return OperationInput
     */
    public static function fromListBucketDataRedundancyTransition(Models\ListBucketDataRedundancyTransitionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListBucketDataRedundancyTransition',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('redundancyTransition', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['redundancyTransition',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListBucketDataRedundancyTransitionResult
     */
    public static function toListBucketDataRedundancyTransition(OperationOutput $output): Models\ListBucketDataRedundancyTransitionResult
    {
        $result = new Models\ListBucketDataRedundancyTransitionResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketDataRedundancyTransitionRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketDataRedundancyTransition(Models\GetBucketDataRedundancyTransitionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketDataRedundancyTransition',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('redundancyTransition', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['redundancyTransition',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketDataRedundancyTransitionResult
     */
    public static function toGetBucketDataRedundancyTransition(OperationOutput $output): Models\GetBucketDataRedundancyTransitionResult
    {
        $result = new Models\GetBucketDataRedundancyTransitionResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CreateBucketDataRedundancyTransitionRequest $request
     * @return OperationInput
     */
    public static function fromCreateBucketDataRedundancyTransition(Models\CreateBucketDataRedundancyTransitionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'CreateBucketDataRedundancyTransition',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('redundancyTransition', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['redundancyTransition',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CreateBucketDataRedundancyTransitionResult
     */
    public static function toCreateBucketDataRedundancyTransition(OperationOutput $output): Models\CreateBucketDataRedundancyTransitionResult
    {
        $result = new Models\CreateBucketDataRedundancyTransitionResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketDataRedundancyTransitionRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketDataRedundancyTransition(Models\DeleteBucketDataRedundancyTransitionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketDataRedundancyTransition',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('redundancyTransition', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['redundancyTransition',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketDataRedundancyTransitionResult
     */
    public static function toDeleteBucketDataRedundancyTransition(OperationOutput $output): Models\DeleteBucketDataRedundancyTransitionResult
    {
        $result = new Models\DeleteBucketDataRedundancyTransitionResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\ListUserDataRedundancyTransitionRequest $request
     * @return OperationInput
     */
    public static function fromListUserDataRedundancyTransition(Models\ListUserDataRedundancyTransitionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListUserDataRedundancyTransition',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('redundancyTransition', '');
        $input->setOpMetadata('sub-resource', ['redundancyTransition',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListUserDataRedundancyTransitionResult
     */
    public static function toListUserDataRedundancyTransition(OperationOutput $output): Models\ListUserDataRedundancyTransitionResult
    {
        $result = new Models\ListUserDataRedundancyTransitionResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
