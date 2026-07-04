<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketPublicAccessBlock
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketPublicAccessBlock
{
    /**
     * @param Models\GetBucketPublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketPublicAccessBlock(Models\GetBucketPublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketPublicAccessBlock',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketPublicAccessBlockResult
     */
    public static function toGetBucketPublicAccessBlock(OperationOutput $output): Models\GetBucketPublicAccessBlockResult
    {
        $result = new Models\GetBucketPublicAccessBlockResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\PutBucketPublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketPublicAccessBlock(Models\PutBucketPublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketPublicAccessBlock',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketPublicAccessBlockResult
     */
    public static function toPutBucketPublicAccessBlock(OperationOutput $output): Models\PutBucketPublicAccessBlockResult
    {
        $result = new Models\PutBucketPublicAccessBlockResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeleteBucketPublicAccessBlockRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketPublicAccessBlock(Models\DeleteBucketPublicAccessBlockRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketPublicAccessBlock',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('publicAccessBlock', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['publicAccessBlock',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketPublicAccessBlockResult
     */
    public static function toDeleteBucketPublicAccessBlock(OperationOutput $output): Models\DeleteBucketPublicAccessBlockResult
    {
        $result = new Models\DeleteBucketPublicAccessBlockResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
