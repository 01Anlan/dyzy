<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;
use PHPUnit\Util\Xml\ValidationResult;

/**
 * Class BucketPolicy
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketPolicy
{
    /**
     * @param Models\PutBucketPolicyRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketPolicy(Models\PutBucketPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketPolicy',
            'PUT',
        );
        $input->setParameter('policy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['policy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketPolicyResult
     */
    public static function toPutBucketPolicy(OperationOutput $output): Models\PutBucketPolicyResult
    {
        $result = new Models\PutBucketPolicyResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketPolicyRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketPolicy(Models\GetBucketPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketPolicy',
            'GET',
        );
        $input->setParameter('policy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['policy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketPolicyResult
     */
    public static function toGetBucketPolicy(OperationOutput $output): Models\GetBucketPolicyResult
    {
        $result = new Models\GetBucketPolicyResult();
        if ($output->getBody() !== null) {
            $result->body = $output->getBody()->getContents();
        }
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeleteBucketPolicyRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketPolicy(Models\DeleteBucketPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketPolicy',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('policy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['policy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketPolicyResult
     */
    public static function toDeleteBucketPolicy(OperationOutput $output): Models\DeleteBucketPolicyResult
    {
        $result = new Models\DeleteBucketPolicyResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketPolicyStatusRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketPolicyStatus(Models\GetBucketPolicyStatusRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketPolicyStatus',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('policyStatus', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['policyStatus',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketPolicyStatusResult
     */
    public static function toGetBucketPolicyStatus(OperationOutput $output): Models\GetBucketPolicyStatusResult
    {
        $result = new Models\GetBucketPolicyStatusResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
