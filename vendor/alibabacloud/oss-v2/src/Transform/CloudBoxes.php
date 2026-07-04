<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class CloudBoxes
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class CloudBoxes
{
    /**
     * @param Models\ListCloudBoxesRequest $request
     * @return OperationInput
     */
    public static function fromListCloudBoxes(Models\ListCloudBoxesRequest $request): OperationInput
    {
        $input = new OperationInput(
            'ListCloudBoxes',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('cloudboxes', '');
        $input->setOpMetadata('sub-resource', ['cloudboxes',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\ListCloudBoxesResult
     */
    public static function toListCloudBoxes(OperationOutput $output): Models\ListCloudBoxesResult
    {
        $result = new Models\ListCloudBoxesResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
