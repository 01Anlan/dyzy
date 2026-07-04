<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketLogging
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketLogging
{   
    /**
     * @param Models\PutBucketLoggingRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketLogging(Models\PutBucketLoggingRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketLogging',
            'PUT',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('logging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['logging',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketLoggingResult
     */
    public static function toPutBucketLogging(OperationOutput $output): Models\PutBucketLoggingResult
    {
        $result = new Models\PutBucketLoggingResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
    /**
     * @param Models\GetBucketLoggingRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketLogging(Models\GetBucketLoggingRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketLogging',
            'GET',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('logging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['logging',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketLoggingResult
     */
    public static function toGetBucketLogging(OperationOutput $output): Models\GetBucketLoggingResult
    {
        $result = new Models\GetBucketLoggingResult();
        $customDeserializer = [ 
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
    /**
     * @param Models\DeleteBucketLoggingRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketLogging(Models\DeleteBucketLoggingRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketLogging',
            'DELETE',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('logging', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['logging',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketLoggingResult
     */
    public static function toDeleteBucketLogging(OperationOutput $output): Models\DeleteBucketLoggingResult
    {
        $result = new Models\DeleteBucketLoggingResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
    /**
     * @param Models\PutUserDefinedLogFieldsConfigRequest $request
     * @return OperationInput
     */
    public static function fromPutUserDefinedLogFieldsConfig(Models\PutUserDefinedLogFieldsConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutUserDefinedLogFieldsConfig',
            'PUT',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('userDefinedLogFieldsConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['userDefinedLogFieldsConfig',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutUserDefinedLogFieldsConfigResult
     */
    public static function toPutUserDefinedLogFieldsConfig(OperationOutput $output): Models\PutUserDefinedLogFieldsConfigResult
    {
        $result = new Models\PutUserDefinedLogFieldsConfigResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
    /**
     * @param Models\GetUserDefinedLogFieldsConfigRequest $request
     * @return OperationInput
     */
    public static function fromGetUserDefinedLogFieldsConfig(Models\GetUserDefinedLogFieldsConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetUserDefinedLogFieldsConfig',
            'GET',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('userDefinedLogFieldsConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['userDefinedLogFieldsConfig',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetUserDefinedLogFieldsConfigResult
     */
    public static function toGetUserDefinedLogFieldsConfig(OperationOutput $output): Models\GetUserDefinedLogFieldsConfigResult
    {
        $result = new Models\GetUserDefinedLogFieldsConfigResult();
        $customDeserializer = [ 
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
    /**
     * @param Models\DeleteUserDefinedLogFieldsConfigRequest $request
     * @return OperationInput
     */
    public static function fromDeleteUserDefinedLogFieldsConfig(Models\DeleteUserDefinedLogFieldsConfigRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteUserDefinedLogFieldsConfig',
            'DELETE',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('userDefinedLogFieldsConfig', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['userDefinedLogFieldsConfig',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteUserDefinedLogFieldsConfigResult
     */
    public static function toDeleteUserDefinedLogFieldsConfig(OperationOutput $output): Models\DeleteUserDefinedLogFieldsConfigResult
    {
        $result = new Models\DeleteUserDefinedLogFieldsConfigResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
