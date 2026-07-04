<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketEncryption
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketEncryption
{   
    /**
     * @param Models\PutBucketEncryptionRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketEncryption(Models\PutBucketEncryptionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketEncryption',
            'PUT',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('encryption', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['encryption',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketEncryptionResult
     */
    public static function toPutBucketEncryption(OperationOutput $output): Models\PutBucketEncryptionResult
    {
        $result = new Models\PutBucketEncryptionResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketEncryptionRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketEncryption(Models\GetBucketEncryptionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketEncryption',
            'GET',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('encryption', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['encryption',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketEncryptionResult
     */
    public static function toGetBucketEncryption(OperationOutput $output): Models\GetBucketEncryptionResult
    {
        $result = new Models\GetBucketEncryptionResult();
        $customDeserializer = [ 
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketEncryptionRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketEncryption(Models\DeleteBucketEncryptionRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketEncryption',
            'DELETE',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('encryption', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['encryption',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketEncryptionResult
     */
    public static function toDeleteBucketEncryption(OperationOutput $output): Models\DeleteBucketEncryptionResult
    {
        $result = new Models\DeleteBucketEncryptionResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
