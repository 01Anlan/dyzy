<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketTags
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketTags
{
    /**
     * @param Models\PutBucketTagsRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketTags(Models\PutBucketTagsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketTags',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('tagging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['tagging',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketTagsResult
     */
    public static function toPutBucketTags(OperationOutput $output): Models\PutBucketTagsResult
    {
        $result = new Models\PutBucketTagsResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketTagsRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketTags(Models\GetBucketTagsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketTags',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('tagging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['tagging',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketTagsResult
     */
    public static function toGetBucketTags(OperationOutput $output): Models\GetBucketTagsResult
    {
        $result = new Models\GetBucketTagsResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketTagsRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketTags(Models\DeleteBucketTagsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketTags',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('tagging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['tagging',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketTagsResult
     */
    public static function toDeleteBucketTags(OperationOutput $output): Models\DeleteBucketTagsResult
    {
        $result = new Models\DeleteBucketTagsResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
