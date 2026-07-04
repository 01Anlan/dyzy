<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketRequestPayment
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketRequestPayment
{
    /**
     * @param Models\PutBucketRequestPaymentRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketRequestPayment(Models\PutBucketRequestPaymentRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketRequestPayment',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('requestPayment', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['requestPayment',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketRequestPaymentResult
     */
    public static function toPutBucketRequestPayment(OperationOutput $output): Models\PutBucketRequestPaymentResult
    {
        $result = new Models\PutBucketRequestPaymentResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketRequestPaymentRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketRequestPayment(Models\GetBucketRequestPaymentRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketRequestPayment',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('requestPayment', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['requestPayment',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketRequestPaymentResult
     */
    public static function toGetBucketRequestPayment(OperationOutput $output): Models\GetBucketRequestPaymentResult
    {
        $result = new Models\GetBucketRequestPaymentResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
