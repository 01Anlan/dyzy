<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketLifecycle
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketLifecycle
{   
    /**
     * @param Models\PutBucketLifecycleRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketLifecycle(Models\PutBucketLifecycleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketLifecycle',
            'PUT',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('lifecycle', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['lifecycle',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketLifecycleResult
     */
    public static function toPutBucketLifecycle(OperationOutput $output): Models\PutBucketLifecycleResult
    {
        $result = new Models\PutBucketLifecycleResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
    /**
     * @param Models\GetBucketLifecycleRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketLifecycle(Models\GetBucketLifecycleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketLifecycle',
            'GET',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('lifecycle', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['lifecycle',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketLifecycleResult
     */
    public static function toGetBucketLifecycle(OperationOutput $output): Models\GetBucketLifecycleResult
    {
        $result = new Models\GetBucketLifecycleResult();
        $customDeserializer = [ 
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
    /**
     * @param Models\DeleteBucketLifecycleRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketLifecycle(Models\DeleteBucketLifecycleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketLifecycle',
            'DELETE',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('lifecycle', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['lifecycle',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketLifecycleResult
     */
    public static function toDeleteBucketLifecycle(OperationOutput $output): Models\DeleteBucketLifecycleResult
    {
        $result = new Models\DeleteBucketLifecycleResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
