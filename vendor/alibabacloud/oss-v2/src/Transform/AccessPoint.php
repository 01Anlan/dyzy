<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class AccessPoint
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class AccessPoint
{
    /**
     * @param Models\ListAccessPointsRequest $request
     * @return OperationInput
     */
    public static function fromListAccessPoints(Models\ListAccessPointsRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListAccessPoints',
            'GET',
            ['Content-Type' => 'application/xml'],
        );
        $input->setParameter('accessPoint', '');
        if (isset($request->bucket)) {
            $input->setBucket($request->bucket);
        }
        $input->setOpMetadata('sub-resource', ['accessPoint',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListAccessPointsResult
     */
    public static function toListAccessPoints(OperationOutput $output): Models\ListAccessPointsResult
    {
        $result = new Models\ListAccessPointsResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetAccessPointRequest $request
     * @return OperationInput
     */
    public static function fromGetAccessPoint(Models\GetAccessPointRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetAccessPoint',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('accessPoint', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPoint',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetAccessPointResult
     */
    public static function toGetAccessPoint(OperationOutput $output): Models\GetAccessPointResult
    {
        $result = new Models\GetAccessPointResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetAccessPointPolicyRequest $request
     * @return OperationInput
     */
    public static function fromGetAccessPointPolicy(Models\GetAccessPointPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetAccessPointPolicy',
            'GET',
        );
        $input->setParameter('accessPointPolicy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPointPolicy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetAccessPointPolicyResult
     */
    public static function toGetAccessPointPolicy(OperationOutput $output): Models\GetAccessPointPolicyResult
    {
        $result = new Models\GetAccessPointPolicyResult();
        if ($output->getBody() !== null) {
            $result->body = $output->getBody()->getContents();
        }
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeleteAccessPointPolicyRequest $request
     * @return OperationInput
     */
    public static function fromDeleteAccessPointPolicy(Models\DeleteAccessPointPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteAccessPointPolicy',
            'DELETE',
        );
        $input->setParameter('accessPointPolicy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPointPolicy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteAccessPointPolicyResult
     */
    public static function toDeleteAccessPointPolicy(OperationOutput $output): Models\DeleteAccessPointPolicyResult
    {
        $result = new Models\DeleteAccessPointPolicyResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\PutAccessPointPolicyRequest $request
     * @return OperationInput
     */
    public static function fromPutAccessPointPolicy(Models\PutAccessPointPolicyRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutAccessPointPolicy',
            'PUT',
        );
        $input->setParameter('accessPointPolicy', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPointPolicy',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutAccessPointPolicyResult
     */
    public static function toPutAccessPointPolicy(OperationOutput $output): Models\PutAccessPointPolicyResult
    {
        $result = new Models\PutAccessPointPolicyResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DeleteAccessPointRequest $request
     * @return OperationInput
     */
    public static function fromDeleteAccessPoint(Models\DeleteAccessPointRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteAccessPoint',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('accessPoint', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPoint',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteAccessPointResult
     */
    public static function toDeleteAccessPoint(OperationOutput $output): Models\DeleteAccessPointResult
    {
        $result = new Models\DeleteAccessPointResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\CreateAccessPointRequest $request
     * @return OperationInput
     */
    public static function fromCreateAccessPoint(Models\CreateAccessPointRequest $request): OperationInput
    {
        $input = new OperationInput(
            'CreateAccessPoint',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('accessPoint', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['accessPoint',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CreateAccessPointResult
     */
    public static function toCreateAccessPoint(OperationOutput $output): Models\CreateAccessPointResult
    {
        $result = new Models\CreateAccessPointResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
