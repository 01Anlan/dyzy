<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketStyle
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketStyle
{
    /**
     * @param Models\PutStyleRequest $request
     * @return OperationInput
     */
    public static function fromPutStyle(Models\PutStyleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutStyle',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('style', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['style', 'styleName']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutStyleResult
     */
    public static function toPutStyle(OperationOutput $output): Models\PutStyleResult
    {
        $result = new Models\PutStyleResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\ListStyleRequest $request
     * @return OperationInput
     */
    public static function fromListStyle(Models\ListStyleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListStyle',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('style', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['style',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListStyleResult
     */
    public static function toListStyle(OperationOutput $output): Models\ListStyleResult
    {
        $result = new Models\ListStyleResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetStyleRequest $request
     * @return OperationInput
     */
    public static function fromGetStyle(Models\GetStyleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetStyle',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('style', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['style', 'styleName']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetStyleResult
     */
    public static function toGetStyle(OperationOutput $output): Models\GetStyleResult
    {
        $result = new Models\GetStyleResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteStyleRequest $request
     * @return OperationInput
     */
    public static function fromDeleteStyle(Models\DeleteStyleRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteStyle',
            'DELETE',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('style', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['style', 'styleName']);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteStyleResult
     */
    public static function toDeleteStyle(OperationOutput $output): Models\DeleteStyleResult
    {
        $result = new Models\DeleteStyleResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
