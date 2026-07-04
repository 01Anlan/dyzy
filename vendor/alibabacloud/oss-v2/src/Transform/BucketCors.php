<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketCors
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketCors
{
    /**
     * @param Models\PutBucketCorsRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketCors(Models\PutBucketCorsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketCors',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cors', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cors',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketCorsResult
     */
    public static function toPutBucketCors(OperationOutput $output): Models\PutBucketCorsResult
    {
        $result = new Models\PutBucketCorsResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketCorsRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketCors(Models\GetBucketCorsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketCors',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cors', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cors',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketCorsResult
     */
    public static function toGetBucketCors(OperationOutput $output): Models\GetBucketCorsResult
    {
        $result = new Models\GetBucketCorsResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketCorsRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketCors(Models\DeleteBucketCorsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketCors',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cors', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cors',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketCorsResult
     */
    public static function toDeleteBucketCors(OperationOutput $output): Models\DeleteBucketCorsResult
    {
        $result = new Models\DeleteBucketCorsResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\OptionObjectRequest $request
     * @return OperationInput
     */
    public static function fromOptionObject(Models\OptionObjectRequest $request): OperationInput
    {
        $input = new OperationInput(
            'OptionObject',
            'OPTIONS',
            ['Content-Type' => 'application/xml']
        );
        $input->setBucket($request->bucket ?? '');
        $input->setKey($request->key ?? '');

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\OptionObjectResult
     */
    public static function toOptionObject(OperationOutput $output): Models\OptionObjectResult
    {
        $result = new Models\OptionObjectResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputHeaders'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
