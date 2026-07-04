<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketAccessmonitor
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketAccessMonitor
{
    /**
     * @param Models\PutBucketAccessMonitorRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketAccessMonitor(Models\PutBucketAccessMonitorRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketAccessMonitor',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('accessmonitor', '');
        $input->setBucket($request->bucket ?? '');
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketAccessMonitorResult
     */
    public static function toPutBucketAccessMonitor(OperationOutput $output): Models\PutBucketAccessMonitorResult
    {
        $result = new Models\PutBucketAccessMonitorResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\GetBucketAccessMonitorRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketAccessMonitor(Models\GetBucketAccessMonitorRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketAccessMonitor',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('accessmonitor', '');
        $input->setBucket($request->bucket ?? '');
        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketAccessMonitorResult
     */
    public static function toGetBucketAccessMonitor(OperationOutput $output): Models\GetBucketAccessMonitorResult
    {
        $result = new Models\GetBucketAccessMonitorResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
}
