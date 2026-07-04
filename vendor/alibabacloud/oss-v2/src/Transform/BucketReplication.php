<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketReplication
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketReplication
{
    /**
     * @param Models\PutBucketRtcRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketRtc(Models\PutBucketRtcRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketRtc',
            'PUT',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('rtc', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['rtc',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketRtcResult
     */
    public static function toPutBucketRtc(OperationOutput $output): Models\PutBucketRtcResult
    {
        $result = new Models\PutBucketRtcResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }

    /**
     * @param Models\PutBucketReplicationRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketReplication(Models\PutBucketReplicationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketReplication',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'add');
        $input->setParameter('replication', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['replication', 'comp',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketReplicationResult
     */
    public static function toPutBucketReplication(OperationOutput $output): Models\PutBucketReplicationResult
    {
        $result = new Models\PutBucketReplicationResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputHeaders'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketReplicationRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketReplication(Models\GetBucketReplicationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketReplication',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('replication', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['replication',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketReplicationResult
     */
    public static function toGetBucketReplication(OperationOutput $output): Models\GetBucketReplicationResult
    {
        $result = new Models\GetBucketReplicationResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketReplicationLocationRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketReplicationLocation(Models\GetBucketReplicationLocationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketReplicationLocation',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('replicationLocation', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['replicationLocation',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketReplicationLocationResult
     */
    public static function toGetBucketReplicationLocation(OperationOutput $output): Models\GetBucketReplicationLocationResult
    {
        $result = new Models\GetBucketReplicationLocationResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\GetBucketReplicationProgressRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketReplicationProgress(Models\GetBucketReplicationProgressRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketReplicationProgress',
            'GET',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('replicationProgress', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['replicationProgress',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketReplicationProgressResult
     */
    public static function toGetBucketReplicationProgress(OperationOutput $output): Models\GetBucketReplicationProgressResult
    {
        $result = new Models\GetBucketReplicationProgressResult();
        $customDeserializer = [
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }

    /**
     * @param Models\DeleteBucketReplicationRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketReplication(Models\DeleteBucketReplicationRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketReplication',
            'POST',
            ['Content-Type' => 'application/xml']
        );
        $input->setParameter('comp', 'delete');
        $input->setParameter('replication', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['comp', 'replication',]);

        $customSerializer = [
            [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketReplicationResult
     */
    public static function toDeleteBucketReplication(OperationOutput $output): Models\DeleteBucketReplicationResult
    {
        $result = new Models\DeleteBucketReplicationResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
