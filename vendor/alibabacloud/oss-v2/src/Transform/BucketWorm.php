<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketWorm
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketWorm
{
    /**
     * @param Models\InitiateBucketWormRequest $request
     * @return OperationInput
     */
    public static function fromInitiateBucketWorm(Models\InitiateBucketWormRequest $request): OperationInput
    {
        $input = new OperationInput(
            'InitiateBucketWorm',
            'POST',
            ['Content-Type' => 'application/xml'],
        );
        $input->setParameter('worm', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['worm']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\InitiateBucketWormResult
     */
    public static function toInitiateBucketWorm(OperationOutput $output): Models\InitiateBucketWormResult
    {
        $result = new Models\InitiateBucketWormResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputHeaders'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\AbortBucketWormRequest $request
     * @return OperationInput
     */
    public static function fromAbortBucketWorm(Models\AbortBucketWormRequest $request): OperationInput
    {
        $input = new OperationInput(
            'AbortBucketWorm',
            'DELETE',
        );
        $input->setParameter('worm', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['worm']);
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\AbortBucketWormResult
     */
    public static function toAbortBucketWorm(OperationOutput $output): Models\AbortBucketWormResult
    {
        $result = new Models\AbortBucketWormResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\CompleteBucketWormRequest $request
     * @return OperationInput
     */
    public static function fromCompleteBucketWorm(Models\CompleteBucketWormRequest $request): OperationInput
    {
        $input = new OperationInput(
            'CompleteBucketWorm',
            'POST',
        );
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['wormId']);
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CompleteBucketWormResult
     */
    public static function toCompleteBucketWorm(OperationOutput $output): Models\CompleteBucketWormResult
    {
        $result = new Models\CompleteBucketWormResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\ExtendBucketWormRequest $request
     * @return OperationInput
     */
    public static function fromExtendBucketWorm(Models\ExtendBucketWormRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ExtendBucketWorm',
            'POST',
            ['Content-Type' => 'application/xml'],
        );
        $input->setParameter('wormExtend', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['wormExtend', 'wormId']);
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ExtendBucketWormResult
     */
    public static function toExtendBucketWorm(OperationOutput $output): Models\ExtendBucketWormResult
    {
        $result = new Models\ExtendBucketWormResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketWormRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketWorm(Models\GetBucketWormRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketWorm',
            'GET',
        );
        $input->setParameter('worm', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['worm']);
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketWormResult
     */
    public static function toGetBucketWorm(OperationOutput $output): Models\GetBucketWormResult
    {
        $result = new Models\GetBucketWormResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
