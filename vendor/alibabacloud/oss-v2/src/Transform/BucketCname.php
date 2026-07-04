<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketCname
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketCname
{
    /**
     * @param Models\PutCnameRequest $request
     * @return OperationInput
     */
    public static function fromPutCname(Models\PutCnameRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutCname',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cname', '');
        $input->setParameter('comp', 'add');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cname', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutCnameResult
     */
    public static function toPutCname(OperationOutput $output): Models\PutCnameResult
    {
        $result = new Models\PutCnameResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\ListCnameRequest $request
     * @return OperationInput
     */
    public static function fromListCname(Models\ListCnameRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListCname',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cname', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cname',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListCnameResult
     */
    public static function toListCname(OperationOutput $output): Models\ListCnameResult
    {
        $result = new Models\ListCnameResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputInnerBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteCnameRequest $request
     * @return OperationInput
     */
    public static function fromDeleteCname(Models\DeleteCnameRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteCname',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cname', '');
        $input->setParameter('comp', 'delete');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cname', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteCnameResult
     */
    public static function toDeleteCname(OperationOutput $output): Models\DeleteCnameResult
    {
        $result = new Models\DeleteCnameResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetCnameTokenRequest $request
     * @return OperationInput
     */
    public static function fromGetCnameToken(Models\GetCnameTokenRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetCnameToken',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'token');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['comp', 'cname']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetCnameTokenResult
     */
    public static function toGetCnameToken(OperationOutput $output): Models\GetCnameTokenResult
    {
        $result = new Models\GetCnameTokenResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CreateCnameTokenRequest $request
     * @return OperationInput
     */
    public static function fromCreateCnameToken(Models\CreateCnameTokenRequest $request): OperationInput
    {
        $input = new OperationInput(
            'CreateCnameToken',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cname', '');
        $input->setParameter('comp', 'token');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['cname', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CreateCnameTokenResult
     */
    public static function toCreateCnameToken(OperationOutput $output): Models\CreateCnameTokenResult
    {
        $result = new Models\CreateCnameTokenResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
