<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketMetaQuery
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketMetaQuery
{
    /**
     * @param Models\GetMetaQueryStatusRequest $request
     * @return OperationInput
     */
    public static function fromGetMetaQueryStatus(Models\GetMetaQueryStatusRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetMetaQueryStatus',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('metaQuery', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['metaQuery',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetMetaQueryStatusResult
     */
    public static function toGetMetaQueryStatus(OperationOutput $output): Models\GetMetaQueryStatusResult
    {
        $result = new Models\GetMetaQueryStatusResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\CloseMetaQueryRequest $request
     * @return OperationInput
     */
    public static function fromCloseMetaQuery(Models\CloseMetaQueryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'CloseMetaQuery',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'delete');
        $input->setParameter('metaQuery', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['metaQuery', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\CloseMetaQueryResult
     */
    public static function toCloseMetaQuery(OperationOutput $output): Models\CloseMetaQueryResult
    {
        $result = new Models\CloseMetaQueryResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\DoMetaQueryRequest $request
     * @return OperationInput
     */
    public static function fromDoMetaQuery(Models\DoMetaQueryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DoMetaQuery',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'query');
        $input->setParameter('metaQuery', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['metaQuery', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DoMetaQueryResult
     */
    public static function toDoMetaQuery(OperationOutput $output): Models\DoMetaQueryResult
    {
        $result = new Models\DoMetaQueryResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputInnerBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\OpenMetaQueryRequest $request
     * @return OperationInput
     */
    public static function fromOpenMetaQuery(Models\OpenMetaQueryRequest $request): OperationInput
    {
        $input = new OperationInput(
            'OpenMetaQuery',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'add');
        $input->setParameter('metaQuery', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['metaQuery', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\OpenMetaQueryResult
     */
    public static function toOpenMetaQuery(OperationOutput $output): Models\OpenMetaQueryResult
    {
        $result = new Models\OpenMetaQueryResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
