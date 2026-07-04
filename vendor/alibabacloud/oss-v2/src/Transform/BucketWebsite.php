<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Transform;

use AlibabaCloud\Oss\V2\OperationInput;
use AlibabaCloud\Oss\V2\OperationOutput;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Deserializer;
use AlibabaCloud\Oss\V2\Serializer;

/**
 * Class BucketWebsite
 * @package AlibabaCloud\Oss\V2\Transform
 */
final class BucketWebsite
{   
    /**
     * @param Models\GetBucketWebsiteRequest $request
     * @return OperationInput
     */
    public static function fromGetBucketWebsite(Models\GetBucketWebsiteRequest $request): OperationInput
    {
        $input = new OperationInput(
            'GetBucketWebsite',
            'GET',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('website', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['website',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\GetBucketWebsiteResult
     */
    public static function toGetBucketWebsite(OperationOutput $output): Models\GetBucketWebsiteResult
    {
        $result = new Models\GetBucketWebsiteResult();
        $customDeserializer = [ 
            [Deserializer::class, 'deserializeOutputBody'],
        ];
        Deserializer::deserializeOutput($result, $output, $customDeserializer);
        return $result;
    }
    /**
     * @param Models\PutBucketWebsiteRequest $request
     * @return OperationInput
     */
    public static function fromPutBucketWebsite(Models\PutBucketWebsiteRequest $request): OperationInput
    {
        $input = new OperationInput(
            'PutBucketWebsite',
            'PUT',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('website', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['website',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\PutBucketWebsiteResult
     */
    public static function toPutBucketWebsite(OperationOutput $output): Models\PutBucketWebsiteResult
    {
        $result = new Models\PutBucketWebsiteResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
    /**
     * @param Models\DeleteBucketWebsiteRequest $request
     * @return OperationInput
     */
    public static function fromDeleteBucketWebsite(Models\DeleteBucketWebsiteRequest $request): OperationInput
    {
        $input = new OperationInput(
            'DeleteBucketWebsite',
            'DELETE',
             ['Content-Type' => 'application/xml']
        );
        $input->setParameter('website', '');
        $input->setBucket($request->bucket ?? '');
        $input->setOpMetadata('sub-resource', ['website',]);

        $customSerializer = [
	        [Functions::class, 'addContentMd5']
        ];
        Serializer::serializeInput($request, $input, $customSerializer);
        return $input;
    }

    /**
     * @param OperationOutput $output
     * @return Models\DeleteBucketWebsiteResult
     */
    public static function toDeleteBucketWebsite(OperationOutput $output): Models\DeleteBucketWebsiteResult
    {
        $result = new Models\DeleteBucketWebsiteResult();
        Deserializer::deserializeOutput($result, $output);
        return $result;
    }
}
