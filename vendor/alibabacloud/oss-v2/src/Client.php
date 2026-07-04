<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use GuzzleHttp;
use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Transform;

/**
 * Client used to interact with **Alibaba Cloud Object Storage Service (OSS)**.
 *
 * args can include the following key value pairs:
 * - retryer: (Retry\RetryerInterface) Guides how HTTP requests should be retried
 *   in case of recoverable failures.
 * - retry_max_attempts: (int) The maximum number attempts an API client will call
 *   an operation that fails with a retryable error.
 * - request_options: (array) Guzzlephp's request options per a request. supports the following:
 *   connect_timeout, read_timeout, timeout, on_stats, sink, stream.
 *   For example, ['request_options' => ['connect_timeout' => 10.0 ]]
 *
 * @method \GuzzleHttp\Promise\Promise putBucketAsync(Models\PutBucketRequest $request, array $args = []) Creates a bucket.
 * @method Models\PutBucketResult putBucket(Models\PutBucketRequest $request, array $args = []) Creates a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketAsync(Models\DeleteBucketRequest $request, array $args = []) Deletes a bucket.
 * @method Models\PutBucketResult deleteBucket(Models\DeleteBucketRequest $request, array $args = []) Deletes a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketAclAsync(Models\PutBucketAclRequest $request, array $args = []) Configures or modifies the access control list (ACL) for a bucket.
 * @method Models\PutBucketAclResult putBucketAcl(Models\PutBucketAclRequest $request, array $args = []) Configures or modifies the access control list (ACL) for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketAclAsync(Models\GetBucketAclRequest $request, array $args = []) Queries the access control list (ACL) of a bucket. Only the owner of a bucket can query the ACL of the bucket.
 * @method Models\GetBucketAclResult getBucketAcl(Models\GetBucketAclRequest $request, array $args = []) Queries the access control list (ACL) of a bucket. Only the owner of a bucket can query the ACL of the bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketStatAsync(Models\GetBucketStatRequest $request, array $args = []) Queries the storage capacity of a bucket and the number of objects that are stored in the bucket.
 * @method Models\GetBucketStatResult getBucketStat(Models\GetBucketStatRequest $request, array $args = []) Queries the storage capacity of a bucket and the number of objects that are stored in the bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketLocationAsync(Models\GetBucketLocationRequest $request, array $args = []) Queries the region in which a bucket resides. Only the owner of a bucket can query the region in which the bucket resides.
 * @method Models\GetBucketLocationResult getBucketLocation(Models\GetBucketLocationRequest $request, array $args = []) Queries the region in which a bucket resides. Only the owner of a bucket can query the region in which the bucket resides.
 * @method \GuzzleHttp\Promise\Promise getBucketInfoAsync(Models\GetBucketInfoRequest $request, array $args = []) Queries the information about a bucket. Only the owner of a bucket can query the information about the bucket. You can call this operation from an Object Storage Service (OSS) endpoint.
 * @method Models\GetBucketInfoResult getBucketInfo(Models\GetBucketInfoRequest $request, array $args = []) Queries the information about a bucket. Only the owner of a bucket can query the information about the bucket. You can call this operation from an Object Storage Service (OSS) endpoint.
 * @method \GuzzleHttp\Promise\Promise putBucketVersioningAsync(Models\PutBucketVersioningRequest $request, array $args = []) Configures the versioning state for a bucket.
 * @method Models\PutBucketVersioningResult putBucketVersioning(Models\PutBucketVersioningRequest $request, array $args = []) Configures the versioning state for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketVersioningAsync(Models\GetBucketVersioningRequest $request, array $args = []) Queries the versioning state of a bucket.
 * @method Models\GetBucketVersioningResult getBucketVersioning(Models\GetBucketVersioningRequest $request, array $args = []) Queries the versioning state of a bucket.
 * @method \GuzzleHttp\Promise\Promise listObjectsAsync(Models\ListObjectsRequest $request, array $args = [])  Queries the information about all objects in a bucket.
 * @method Models\ListObjectsResult listObjects(Models\ListObjectsRequest $request, array $args = [])  Queries the information about all objects in a bucket.
 * @method \GuzzleHttp\Promise\Promise listObjectsV2Async(Models\ListObjectsV2Request $request, array $args = []) Queries the information about all objects in a bucket.
 * @method Models\ListObjectsV2Result listObjectsV2(Models\ListObjectsV2Request $request, array $args = []) Queries the information about all objects in a bucket.
 * @method \GuzzleHttp\Promise\Promise listObjectVersionsAsync(Models\ListObjectVersionsRequest $request, array $args = []) Queries the information about the versions of all objects in a bucket, including the delete markers.
 * @method Models\ListObjectVersionsResult listObjectVersions(Models\ListObjectVersionsRequest $request, array $args = []) Queries the information about the versions of all objects in a bucket, including the delete markers.
 * @method Models\PutObjectResult putObject(Models\PutObjectRequest $request, array $args = []) You can call this operation to upload an object.
 * @method \GuzzleHttp\Promise\Promise putObjectAsync(Models\PutObjectRequest $request, array $args = []) You can call this operation to upload an object.
 * @method Models\CopyObjectResult copyObject(Models\CopyObjectRequest $request, array $args = []) Copies objects within a bucket or between buckets in the same region.
 * @method \GuzzleHttp\Promise\Promise copyObjectAsync(Models\CopyObjectRequest $request, array $args = []) Copies objects within a bucket or between buckets in the same region.
 * @method Models\GetObjectResult getObject(Models\GetObjectRequest $request, array $args = []) You can call this operation to query an object.
 * @method \GuzzleHttp\Promise\Promise getObjectAsync(Models\GetObjectRequest $request, array $args = []) You can call this operation to query an object.
 * @method Models\AppendObjectResult appendObject(Models\AppendObjectRequest $request, array $args = []) You can call this operation to upload an object by appending the object to an existing object.
 * @method \GuzzleHttp\Promise\Promise appendObjectAsync(Models\AppendObjectRequest $request, array $args = []) You can call this operation to upload an object by appending the object to an existing object.
 * @method Models\SealAppendObjectResult sealAppendObject(Models\SealAppendObjectRequest $request, array $args = []) This operation stops writing to the Appendable Object, after which the user can configure lifecycle rules to change the storage class of the corresponding Appendable Object to Cold Archive or Deep Cold Archive.
 * @method \GuzzleHttp\Promise\Promise sealAppendObjectAsync(Models\SealAppendObjectRequest $request, array $args = []) This operation stops writing to the Appendable Object, after which the user can configure lifecycle rules to change the storage class of the corresponding Appendable Object to Cold Archive or Deep Cold Archive.
 * @method Models\DeleteObjectResult deleteObject(Models\DeleteObjectRequest $request, array $args = []) You can call this operation to delete an object.
 * @method \GuzzleHttp\Promise\Promise deleteObjectAsync(Models\DeleteObjectRequest $request, array $args = []) You can call this operation to delete an object.
 * @method Models\DeleteMultipleObjectsResult deleteMultipleObjects(Models\DeleteMultipleObjectsRequest $request, array $args = []) You can call this operation to delete an object.
 * @method \GuzzleHttp\Promise\Promise deleteMultipleObjectsAsync(Models\DeleteMultipleObjectsRequest $request, array $args = []) You can call this operation to delete an object.
 * @method Models\HeadObjectResult headObject(Models\HeadObjectRequest $request, array $args = []) You can call this operation to query the metadata of an object.
 * @method \GuzzleHttp\Promise\Promise headObjectAsync(Models\HeadObjectRequest $request, array $args = []) You can call this operation to query the metadata of an object.
 * @method Models\GetObjectMetaResult getObjectMeta(Models\GetObjectMetaRequest $request, array $args = []) You can call this operation to query the metadata of an object, including ETag, Size, and LastModified. The content of the object is not returned.
 * @method \GuzzleHttp\Promise\Promise getObjectMetaAsync(Models\GetObjectMetaRequest $request, array $args = []) You can call this operation to query the metadata of an object, including ETag, Size, and LastModified. The content of the object is not returned.
 * @method Models\RestoreObjectResult restoreObject(Models\RestoreObjectRequest $request, array $args = []) You can call this operation to restore objects of the Archive and Cold Archive storage classes. You are charged based on tier(restoration priority) when you restore object. For more information, see OSS document.
 * @method \GuzzleHttp\Promise\Promise restoreObjectAsync(Models\RestoreObjectRequest $request, array $args = []) You can call this operation to restore objects of the Archive and Cold Archive storage classes. You are charged based on tier(restoration priority) when you restore object. For more information, see OSS document.
 * @method Models\PutObjectAclResult putObjectAcl(Models\PutObjectAclRequest $request, array $args = []) You can call this operation to modify the ACL of an object.
 * @method \GuzzleHttp\Promise\Promise putObjectAclAsync(Models\PutObjectAclRequest $request, array $args = []) You can call this operation to modify the ACL of an object.
 * @method Models\GetObjectAclResult getObjectAcl(Models\GetObjectAclRequest $request, array $args = []) You can call this operation to query the ACL of an object in a bucket.
 * @method \GuzzleHttp\Promise\Promise getObjectAclAsync(Models\GetObjectAclRequest $request, array $args = []) You can call this operation to query the ACL of an object in a bucket.
 * @method Models\PutObjectTaggingResult putObjectTagging(Models\PutObjectTaggingRequest $request, array $args = []) You can call this operation to add tags to or modify the tags of an object.
 * @method \GuzzleHttp\Promise\Promise putObjectTaggingAsync(Models\PutObjectTaggingRequest $request, array $args = []) You can call this operation to add tags to or modify the tags of an object.
 * @method Models\GetObjectTaggingResult getObjectTagging(Models\GetObjectTaggingRequest $request, array $args = []) You can call this operation to query the tags of an object.
 * @method \GuzzleHttp\Promise\Promise getObjectTaggingAsync(Models\GetObjectTaggingRequest $request, array $args = []) You can call this operation to query the tags of an object.
 * @method Models\DeleteObjectTaggingResult deleteObjectTagging(Models\DeleteObjectTaggingRequest $request, array $args = []) You can call this operation to delete the tags of a specified object.
 * @method \GuzzleHttp\Promise\Promise deleteObjectTaggingAsync(Models\DeleteObjectTaggingRequest $request, array $args = []) You can call this operation to delete the tags of a specified object.
 * @method Models\PutSymlinkResult putSymlink(Models\PutSymlinkRequest $request, array $args = []) You can create a symbolic link for a target object.
 * @method \GuzzleHttp\Promise\Promise putSymlinkAsync(Models\PutSymlinkRequest $request, array $args = []) You can create a symbolic link for a target object.
 * @method Models\GetSymlinkResult getSymlink(Models\GetSymlinkRequest $request, array $args = []) You can call this operation to query a symbolic link of an object.
 * @method \GuzzleHttp\Promise\Promise getSymlinkAsync(Models\GetSymlinkRequest $request, array $args = []) You can call this operation to query a symbolic link of an object.
 * @method Models\InitiateMultipartUploadResult initiateMultipartUpload(Models\InitiateMultipartUploadRequest $request, array $args = []) Initiates a multipart upload task.
 * @method \GuzzleHttp\Promise\Promise initiateMultipartUploadAsync(Models\InitiateMultipartUploadRequest $request, array $args = []) Initiates a multipart upload task.
 * @method Models\UploadPartResult uploadPart(Models\UploadPartRequest $request, array $args = []) You can call this operation to upload an object by part based on the object name and the upload ID that you specify.
 * @method \GuzzleHttp\Promise\Promise uploadPartAsync(Models\UploadPartRequest $request, array $args = []) You can call this operation to upload an object by part based on the object name and the upload ID that you specify.
 * @method Models\CompleteMultipartUploadResult completeMultipartUpload(Models\CompleteMultipartUploadRequest $request, array $args = []) You can call this operation to complete the multipart upload task of an object.
 * @method \GuzzleHttp\Promise\Promise completeMultipartUploadAsync(Models\CompleteMultipartUploadRequest $request, array $args = []) You can call this operation to complete the multipart upload task of an object.
 * @method Models\UploadPartCopyResult uploadPartCopy(Models\UploadPartCopyRequest $request, array $args = []) Copies data from an existing object to upload a part.
 * @method \GuzzleHttp\Promise\Promise uploadPartCopyAsync(Models\UploadPartCopyRequest $request, array $args = []) Copies data from an existing object to upload a part.
 * @method Models\AbortMultipartUploadResult abortMultipartUpload(Models\AbortMultipartUploadRequest $request, array $args = []) You can call this operation to cancel a multipart upload task and delete the parts that are uploaded by the multipart upload task.
 * @method \GuzzleHttp\Promise\Promise abortMultipartUploadAsync(Models\AbortMultipartUploadRequest $request, array $args = []) You can call this operation to cancel a multipart upload task and delete the parts that are uploaded by the multipart upload task.
 * @method Models\ListMultipartUploadsResult listMultipartUploads(Models\ListMultipartUploadsRequest $request, array $args = []) You can call this operation to list all ongoing multipart upload tasks.
 * @method \GuzzleHttp\Promise\Promise listMultipartUploadsAsync(Models\ListMultipartUploadsRequest $request, array $args = []) You can call this operation to list all ongoing multipart upload tasks.
 * @method Models\ListPartsResult listParts(Models\ListPartsRequest $request, array $args = []) You can call this operation to list all parts that are uploaded by using a specified upload ID.
 * @method \GuzzleHttp\Promise\Promise listPartsAsync(Models\ListPartsRequest $request, array $args = []) You can call this operation to list all parts that are uploaded by using a specified upload ID.
 * @method Models\CleanRestoredObjectResult cleanRestoredObject(Models\CleanRestoredObjectRequest $request, array $args = []) You can call this operation to clean an object restored from Archive or Cold Archive state. After that, the restored object returns to the frozen state.
 * @method \GuzzleHttp\Promise\Promise cleanRestoredObjectAsync(Models\CleanRestoredObjectRequest $request, array $args = []) You can call this operation to clean an object restored from Archive or Cold Archive state. After that, the restored object returns to the frozen state.
 * @method Models\ProcessObjectResult processObject(Models\ProcessObjectRequest $request, array $args = []) You can call this operation to apply process on the specified image file.
 * @method \GuzzleHttp\Promise\Promise processObjectAsync(Models\ProcessObjectRequest $request, array $args = []) You can call this operation to apply process on the specified image file.
 * @method Models\AsyncProcessObjectResult asyncProcessObject(Models\AsyncProcessObjectRequest $request, array $args = []) You can call this operation to apply async process on the specified image file.
 * @method \GuzzleHttp\Promise\Promise asyncProcessObjectAsync(Models\AsyncProcessObjectRequest $request, array $args = []) You can call this operation to apply async process on the specified image file.
 * @method Models\InitiateBucketWormResult initiateBucketWorm(Models\InitiateBucketWormRequest $request, array $args = []) Creates a retention policy.
 * @method \GuzzleHttp\Promise\Promise initiateBucketWormAsync(Models\InitiateBucketWormRequest $request, array $args = []) Creates a retention policy.
 * @method Models\AbortBucketWormResult abortBucketWorm(Models\AbortBucketWormRequest $request, array $args = []) Deletes an unlocked retention policy for a bucket.
 * @method \GuzzleHttp\Promise\Promise abortBucketWormAsync(Models\AbortBucketWormRequest $request, array $args = []) Deletes an unlocked retention policy for a bucket.
 * @method Models\CompleteBucketWormResult completeBucketWorm(Models\CompleteBucketWormRequest $request, array $args = []) Locks a retention policy.
 * @method \GuzzleHttp\Promise\Promise completeBucketWormAsync(Models\CompleteBucketWormRequest $request, array $args = []) Locks a retention policy.
 * @method Models\ExtendBucketWormResult extendBucketWorm(Models\ExtendBucketWormRequest $request, array $args = []) Extends the retention period of objects in a bucket for which a retention policy is locked.
 * @method \GuzzleHttp\Promise\Promise extendBucketWormAsync(Models\ExtendBucketWormRequest $request, array $args = []) Extends the retention period of objects in a bucket for which a retention policy is locked.
 * @method Models\GetBucketWormResult getBucketWorm(Models\GetBucketWormRequest $request, array $args = []) Queries the retention policy configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketWormAsync(Models\GetBucketWormRequest $request, array $args = []) Queries the retention policy configured for a bucket.
 * @method Models\ListBucketsResult listBuckets(Models\ListBucketsRequest $request, array $args = []) Queries all buckets that are owned by a requester.
 * @method \GuzzleHttp\Promise\Promise listBucketsAsync(Models\ListBucketsRequest $request, array $args = []) Queries all buckets that are owned by a requester.
 * @method Models\DescribeRegionsResult describeRegions(Models\DescribeRegionsRequest $request, array $args = []) Queries the endpoints of all supported regions or the endpoints of a specific region.
 * @method \GuzzleHttp\Promise\Promise describeRegionsAsync(Models\DescribeRegionsRequest $request, array $args = []) Queries the endpoints of all supported regions or the endpoints of a specific region.
 * @method Models\PutBucketLifecycleResult putBucketLifecycle(Models\PutBucketLifecycleRequest $request, array $args = []) Configures a lifecycle rule for a bucket. After you configure a lifecycle rule for a bucket, Object Storage Service (OSS) automatically deletes the objects that matching the rule or converts the storage type of the objects based on the point in time that is specified in the lifecycle rule.
 * @method \GuzzleHttp\Promise\Promise putBucketLifecycleAsync(Models\PutBucketLifecycleRequest $request, array $args = []) Configures a lifecycle rule for a bucket. After you configure a lifecycle rule for a bucket, Object Storage Service (OSS) automatically deletes the objects that matching the rule or converts the storage type of the objects based on the point in time that is specified in the lifecycle rule.
 * @method Models\GetBucketLifecycleResult getBucketLifecycle(Models\GetBucketLifecycleRequest $request, array $args = []) Queries the lifecycle rules configured for a bucket. Only the owner of a bucket has the permissions to query the lifecycle rules configured for the bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketLifecycleAsync(Models\GetBucketLifecycleRequest $request, array $args = []) Queries the lifecycle rules configured for a bucket. Only the owner of a bucket has the permissions to query the lifecycle rules configured for the bucket.
 * @method Models\DeleteBucketLifecycleResult deleteBucketLifecycle(Models\DeleteBucketLifecycleRequest $request, array $args = []) Deletes the lifecycle rules of a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketLifecycleAsync(Models\DeleteBucketLifecycleRequest $request, array $args = []) Deletes the lifecycle rules of a bucket.
 * @method Models\PutBucketTransferAccelerationResult putBucketTransferAcceleration(Models\PutBucketTransferAccelerationRequest $request, array $args = []) Configures transfer acceleration for a bucket. After you enable transfer acceleration for a bucket, the object access speed is accelerated for users worldwide. The transfer acceleration feature is applicable to scenarios where data needs to be transferred over long geographical distances. This feature can also be used to download or upload objects that are gigabytes or terabytes in size.
 * @method \GuzzleHttp\Promise\Promise putBucketTransferAccelerationAsync(Models\PutBucketTransferAccelerationRequest $request, array $args = []) Configures transfer acceleration for a bucket. After you enable transfer acceleration for a bucket, the object access speed is accelerated for users worldwide. The transfer acceleration feature is applicable to scenarios where data needs to be transferred over long geographical distances. This feature can also be used to download or upload objects that are gigabytes or terabytes in size.
 * @method Models\GetBucketTransferAccelerationResult getBucketTransferAcceleration(Models\GetBucketTransferAccelerationRequest $request, array $args = []) Queries the transfer acceleration configurations of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketTransferAccelerationAsync(Models\GetBucketTransferAccelerationRequest $request, array $args = []) Queries the transfer acceleration configurations of a bucket.
 * @method Models\GetMetaQueryStatusResult getMetaQueryStatus(Models\GetMetaQueryStatusRequest $request, array $args = []) Queries the information about the metadata index library of a bucket.
 * @method \GuzzleHttp\Promise\Promise getMetaQueryStatusAsync(Models\GetMetaQueryStatusRequest $request, array $args = []) Queries the information about the metadata index library of a bucket.
 * @method Models\CloseMetaQueryResult closeMetaQuery(Models\CloseMetaQueryRequest $request, array $args = []) Disables the metadata management feature for an Object Storage Service (OSS) bucket. After the metadata management feature is disabled for a bucket, OSS automatically deletes the metadata index library of the bucket and you cannot perform metadata indexing.
 * @method \GuzzleHttp\Promise\Promise closeMetaQueryAsync(Models\CloseMetaQueryRequest $request, array $args = []) Disables the metadata management feature for an Object Storage Service (OSS) bucket. After the metadata management feature is disabled for a bucket, OSS automatically deletes the metadata index library of the bucket and you cannot perform metadata indexing.
 * @method Models\DoMetaQueryResult doMetaQuery(Models\DoMetaQueryRequest $request, array $args = []) Queries the objects in a bucket that meet the specified conditions by using the data indexing feature. The information about the objects is listed based on the specified fields and sorting methods.
 * @method \GuzzleHttp\Promise\Promise doMetaQueryAsync(Models\DoMetaQueryRequest $request, array $args = []) Queries the objects in a bucket that meet the specified conditions by using the data indexing feature. The information about the objects is listed based on the specified fields and sorting methods.
 * @method Models\OpenMetaQueryResult openMetaQuery(Models\OpenMetaQueryRequest $request, array $args = []) Enables metadata management for a bucket. After you enable the metadata management feature for a bucket, Object Storage Service (OSS) creates a metadata index library for the bucket and creates metadata indexes for all objects in the bucket. After the metadata index library is created, OSS continues to perform quasi-real-time scans on incremental objects in the bucket and creates metadata indexes for the incremental objects.
 * @method \GuzzleHttp\Promise\Promise openMetaQueryAsync(Models\OpenMetaQueryRequest $request, array $args = []) Enables metadata management for a bucket. After you enable the metadata management feature for a bucket, Object Storage Service (OSS) creates a metadata index library for the bucket and creates metadata indexes for all objects in the bucket. After the metadata index library is created, OSS continues to perform quasi-real-time scans on incremental objects in the bucket and creates metadata indexes for the incremental objects.
 * @method Models\PutBucketAccessMonitorResult putBucketAccessMonitor(Models\PutBucketAccessMonitorRequest $request, array $args = []) Modifies the access tracking status of a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketAccessMonitorAsync(Models\PutBucketAccessMonitorRequest $request, array $args = []) Modifies the access tracking status of a bucket.
 * @method Models\GetBucketAccessMonitorResult getBucketAccessMonitor(Models\GetBucketAccessMonitorRequest $request, array $args = []) Queries the access tracking status of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketAccessMonitorAsync(Models\GetBucketAccessMonitorRequest $request, array $args = []) Queries the access tracking status of a bucket.
 * @method Models\PutCnameResult putCname(Models\PutCnameRequest $request, array $args = []) Maps a CNAME record to a bucket.
 * @method \GuzzleHttp\Promise\Promise putCnameAsync(Models\PutCnameRequest $request, array $args = []) Maps a CNAME record to a bucket.
 * @method Models\ListCnameResult listCname(Models\ListCnameRequest $request, array $args = []) Queries all CNAME records that are mapped to a bucket.
 * @method \GuzzleHttp\Promise\Promise listCnameAsync(Models\ListCnameRequest $request, array $args = []) Queries all CNAME records that are mapped to a bucket.
 * @method Models\DeleteCnameResult deleteCname(Models\DeleteCnameRequest $request, array $args = []) Deletes a CNAME record that is mapped to a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteCnameAsync(Models\DeleteCnameRequest $request, array $args = []) Deletes a CNAME record that is mapped to a bucket.
 * @method Models\GetCnameTokenResult getCnameToken(Models\GetCnameTokenRequest $request, array $args = []) Queries the created CNAME tokens.
 * @method \GuzzleHttp\Promise\Promise getCnameTokenAsync(Models\GetCnameTokenRequest $request, array $args = []) Queries the created CNAME tokens.
 * @method Models\CreateCnameTokenResult createCnameToken(Models\CreateCnameTokenRequest $request, array $args = []) Creates a CNAME token to verify the ownership of a domain name.
 * @method \GuzzleHttp\Promise\Promise createCnameTokenAsync(Models\CreateCnameTokenRequest $request, array $args = []) Creates a CNAME token to verify the ownership of a domain name.
 * @method Models\PutBucketCorsResult putBucketCors(Models\PutBucketCorsRequest $request, array $args = []) Configures cross-origin resource sharing (CORS) rules for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketCorsAsync(Models\PutBucketCorsRequest $request, array $args = []) Configures cross-origin resource sharing (CORS) rules for a bucket.
 * @method Models\GetBucketCorsResult getBucketCors(Models\GetBucketCorsRequest $request, array $args = []) Queries the cross-origin resource sharing (CORS) rules that are configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketCorsAsync(Models\GetBucketCorsRequest $request, array $args = []) Queries the cross-origin resource sharing (CORS) rules that are configured for a bucket.
 * @method Models\DeleteBucketCorsResult deleteBucketCors(Models\DeleteBucketCorsRequest $request, array $args = []) Disables the cross-origin resource sharing (CORS) feature and deletes all CORS rules for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketCorsAsync(Models\DeleteBucketCorsRequest $request, array $args = []) Disables the cross-origin resource sharing (CORS) feature and deletes all CORS rules for a bucket.
 * @method Models\OptionObjectResult optionObject(Models\OptionObjectRequest $request, array $args = []) Determines whether to send a cross-origin request. Before a cross-origin request is sent, the browser sends a preflight OPTIONS request that includes a specific origin, HTTP method, and header information to Object Storage Service (OSS) to determine whether to send the cross-origin request.
 * @method \GuzzleHttp\Promise\Promise optionObjectAsync(Models\OptionObjectRequest $request, array $args = []) Determines whether to send a cross-origin request. Before a cross-origin request is sent, the browser sends a preflight OPTIONS request that includes a specific origin, HTTP method, and header information to Object Storage Service (OSS) to determine whether to send the cross-origin request.
 * @method Models\PutBucketRequestPaymentResult putBucketRequestPayment(Models\PutBucketRequestPaymentRequest $request, array $args = []) Enables pay-by-requester for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketRequestPaymentAsync(Models\PutBucketRequestPaymentRequest $request, array $args = []) Enables pay-by-requester for a bucket.
 * @method Models\GetBucketRequestPaymentResult getBucketRequestPayment(Models\GetBucketRequestPaymentRequest $request, array $args = []) Queries pay-by-requester configurations for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketRequestPaymentAsync(Models\GetBucketRequestPaymentRequest $request, array $args = []) Queries pay-by-requester configurations for a bucket.
 * @method Models\PutBucketTagsResult putBucketTags(Models\PutBucketTagsRequest $request, array $args = []) Adds tags to or modifies the existing tags of a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketTagsAsync(Models\PutBucketTagsRequest $request, array $args = []) Adds tags to or modifies the existing tags of a bucket.
 * @method Models\GetBucketTagsResult getBucketTags(Models\GetBucketTagsRequest $request, array $args = []) Queries the tags of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketTagsAsync(Models\GetBucketTagsRequest $request, array $args = []) Queries the tags of a bucket.
 * @method Models\DeleteBucketTagsResult deleteBucketTags(Models\DeleteBucketTagsRequest $request, array $args = []) Deletes tags configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketTagsAsync(Models\DeleteBucketTagsRequest $request, array $args = []) Deletes tags configured for a bucket.
 * @method Models\PutBucketRefererResult putBucketReferer(Models\PutBucketRefererRequest $request, array $args = []) Configures a Referer whitelist for an Object Storage Service (OSS) bucket. You can specify whether to allow the requests whose Referer field is empty or whose query strings are truncated.
 * @method \GuzzleHttp\Promise\Promise putBucketRefererAsync(Models\PutBucketRefererRequest $request, array $args = []) Configures a Referer whitelist for an Object Storage Service (OSS) bucket. You can specify whether to allow the requests whose Referer field is empty or whose query strings are truncated.
 * @method Models\GetBucketRefererResult getBucketReferer(Models\GetBucketRefererRequest $request, array $args = []) Queries the hotlink protection configurations for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketRefererAsync(Models\GetBucketRefererRequest $request, array $args = []) Queries the hotlink protection configurations for a bucket.
 * @method Models\GetBucketWebsiteResult getBucketWebsite(Models\GetBucketWebsiteRequest $request, array $args = []) Queries the static website hosting status and redirection rules configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketWebsiteAsync(Models\GetBucketWebsiteRequest $request, array $args = []) Queries the static website hosting status and redirection rules configured for a bucket.
 * @method Models\PutBucketWebsiteResult putBucketWebsite(Models\PutBucketWebsiteRequest $request, array $args = []) Enables the static website hosting mode for a bucket and configures redirection rules for the bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketWebsiteAsync(Models\PutBucketWebsiteRequest $request, array $args = []) Enables the static website hosting mode for a bucket and configures redirection rules for the bucket.
 * @method Models\DeleteBucketWebsiteResult deleteBucketWebsite(Models\DeleteBucketWebsiteRequest $request, array $args = []) Disables the static website hosting mode and deletes the redirection rules for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketWebsiteAsync(Models\DeleteBucketWebsiteRequest $request, array $args = []) Disables the static website hosting mode and deletes the redirection rules for a bucket.
 * @method Models\PutBucketLoggingResult putBucketLogging(Models\PutBucketLoggingRequest $request, array $args = []) Enables logging for a bucket. After you enable logging for a bucket, Object Storage Service (OSS) generates logs every hour based on the defined naming rule and stores the logs as objects in the specified destination bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketLoggingAsync(Models\PutBucketLoggingRequest $request, array $args = []) Enables logging for a bucket. After you enable logging for a bucket, Object Storage Service (OSS) generates logs every hour based on the defined naming rule and stores the logs as objects in the specified destination bucket.
 * @method Models\GetBucketLoggingResult getBucketLogging(Models\GetBucketLoggingRequest $request, array $args = []) Queries the configurations of access log collection of a bucket. Only the owner of a bucket can query the configurations of access log collection of the bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketLoggingAsync(Models\GetBucketLoggingRequest $request, array $args = []) Queries the configurations of access log collection of a bucket. Only the owner of a bucket can query the configurations of access log collection of the bucket.
 * @method Models\DeleteBucketLoggingResult deleteBucketLogging(Models\DeleteBucketLoggingRequest $request, array $args = []) Disables the logging feature for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketLoggingAsync(Models\DeleteBucketLoggingRequest $request, array $args = []) Disables the logging feature for a bucket.
 * @method Models\PutUserDefinedLogFieldsConfigResult putUserDefinedLogFieldsConfig(Models\PutUserDefinedLogFieldsConfigRequest $request, array $args = []) Customizes the user_defined_log_fields field in real-time logs by adding custom request headers or query parameters to the field for subsequent analysis of requests.
 * @method \GuzzleHttp\Promise\Promise putUserDefinedLogFieldsConfigAsync(Models\PutUserDefinedLogFieldsConfigRequest $request, array $args = []) Customizes the user_defined_log_fields field in real-time logs by adding custom request headers or query parameters to the field for subsequent analysis of requests.
 * @method Models\GetUserDefinedLogFieldsConfigResult getUserDefinedLogFieldsConfig(Models\GetUserDefinedLogFieldsConfigRequest $request, array $args = []) Queries the custom configurations of the user_defined_log_fields field in the real-time logs of a bucket.
 * @method \GuzzleHttp\Promise\Promise getUserDefinedLogFieldsConfigAsync(Models\GetUserDefinedLogFieldsConfigRequest $request, array $args = []) Queries the custom configurations of the user_defined_log_fields field in the real-time logs of a bucket.
 * @method Models\DeleteUserDefinedLogFieldsConfigResult deleteUserDefinedLogFieldsConfig(Models\DeleteUserDefinedLogFieldsConfigRequest $request, array $args = []) Deletes the custom configurations of the user_defined_log_fields field in the real-time logs of a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteUserDefinedLogFieldsConfigAsync(Models\DeleteUserDefinedLogFieldsConfigRequest $request, array $args = []) Deletes the custom configurations of the user_defined_log_fields field in the real-time logs of a bucket.
 * @method Models\PutBucketPolicyResult putBucketPolicy(Models\PutBucketPolicyRequest $request, array $args = []) Configures a policy for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketPolicyAsync(Models\PutBucketPolicyRequest $request, array $args = []) Configures a policy for a bucket.
 * @method Models\GetBucketPolicyResult getBucketPolicy(Models\GetBucketPolicyRequest $request, array $args = []) Queries the policies configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketPolicyAsync(Models\GetBucketPolicyRequest $request, array $args = []) Queries the policies configured for a bucket.
 * @method Models\DeleteBucketPolicyResult deleteBucketPolicy(Models\DeleteBucketPolicyRequest $request, array $args = []) Deletes a policy for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketPolicyAsync(Models\DeleteBucketPolicyRequest $request, array $args = []) Deletes a policy for a bucket.
 * @method Models\GetBucketPolicyStatusResult getBucketPolicyStatus(Models\GetBucketPolicyStatusRequest $request, array $args = []) Checks whether the current bucket policy allows public access.
 * @method \GuzzleHttp\Promise\Promise getBucketPolicyStatusAsync(Models\GetBucketPolicyStatusRequest $request, array $args = []) Checks whether the current bucket policy allows public access.
 * @method Models\PutBucketEncryptionResult putBucketEncryption(Models\PutBucketEncryptionRequest $request, array $args = []) Configures encryption rules for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketEncryptionAsync(Models\PutBucketEncryptionRequest $request, array $args = []) Configures encryption rules for a bucket.
 * @method Models\GetBucketEncryptionResult getBucketEncryption(Models\GetBucketEncryptionRequest $request, array $args = []) Queries the encryption rules configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketEncryptionAsync(Models\GetBucketEncryptionRequest $request, array $args = []) Queries the encryption rules configured for a bucket.
 * @method Models\DeleteBucketEncryptionResult deleteBucketEncryption(Models\DeleteBucketEncryptionRequest $request, array $args = []) Deletes encryption rules for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketEncryptionAsync(Models\DeleteBucketEncryptionRequest $request, array $args = []) Deletes encryption rules for a bucket.
 * @method Models\GetBucketArchiveDirectReadResult getBucketArchiveDirectRead(Models\GetBucketArchiveDirectReadRequest $request, array $args = []) Queries whether real-time access of Archive objects is enabled for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketArchiveDirectReadAsync(Models\GetBucketArchiveDirectReadRequest $request, array $args = []) Queries whether real-time access of Archive objects is enabled for a bucket.
 * @method Models\PutBucketArchiveDirectReadResult putBucketArchiveDirectRead(Models\PutBucketArchiveDirectReadRequest $request, array $args = []) Enables or disables real-time access of Archive objects for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketArchiveDirectReadAsync(Models\PutBucketArchiveDirectReadRequest $request, array $args = []) Enables or disables real-time access of Archive objects for a bucket.
 * @method Models\GetPublicAccessBlockResult getPublicAccessBlock(Models\GetPublicAccessBlockRequest $request, array $args = []) Queries the Block Public Access configurations of OSS resources.
 * @method \GuzzleHttp\Promise\Promise getPublicAccessBlockAsync(Models\GetPublicAccessBlockRequest $request, array $args = []) Queries the Block Public Access configurations of OSS resources.
 * @method Models\PutPublicAccessBlockResult putPublicAccessBlock(Models\PutPublicAccessBlockRequest $request, array $args = []) Enables or disables Block Public Access for Object Storage Service (OSS) resources.
 * @method \GuzzleHttp\Promise\Promise putPublicAccessBlockAsync(Models\PutPublicAccessBlockRequest $request, array $args = []) Enables or disables Block Public Access for Object Storage Service (OSS) resources.
 * @method Models\DeletePublicAccessBlockResult deletePublicAccessBlock(Models\DeletePublicAccessBlockRequest $request, array $args = []) Deletes the Block Public Access configurations of OSS resources.
 * @method \GuzzleHttp\Promise\Promise deletePublicAccessBlockAsync(Models\DeletePublicAccessBlockRequest $request, array $args = []) Deletes the Block Public Access configurations of OSS resources.
 * @method Models\GetBucketPublicAccessBlockResult getBucketPublicAccessBlock(Models\GetBucketPublicAccessBlockRequest $request, array $args = []) Queries the Block Public Access configurations of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketPublicAccessBlockAsync(Models\GetBucketPublicAccessBlockRequest $request, array $args = []) Queries the Block Public Access configurations of a bucket.
 * @method Models\PutBucketPublicAccessBlockResult putBucketPublicAccessBlock(Models\PutBucketPublicAccessBlockRequest $request, array $args = []) Enables or disables Block Public Access for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketPublicAccessBlockAsync(Models\PutBucketPublicAccessBlockRequest $request, array $args = []) Enables or disables Block Public Access for a bucket.
 * @method Models\DeleteBucketPublicAccessBlockResult deleteBucketPublicAccessBlock(Models\DeleteBucketPublicAccessBlockRequest $request, array $args = []) Deletes the Block Public Access configurations of a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketPublicAccessBlockAsync(Models\DeleteBucketPublicAccessBlockRequest $request, array $args = []) Deletes the Block Public Access configurations of a bucket.
 * @method Models\ListAccessPointsResult listAccessPoints(Models\ListAccessPointsRequest $request, array $args = []) Queries the information about user-level or bucket-level access points.
 * @method \GuzzleHttp\Promise\Promise listAccessPointsAsync(Models\ListAccessPointsRequest $request, array $args = []) Queries the information about user-level or bucket-level access points.
 * @method Models\GetAccessPointResult getAccessPoint(Models\GetAccessPointRequest $request, array $args = []) Queries the information about an access point.
 * @method \GuzzleHttp\Promise\Promise getAccessPointAsync(Models\GetAccessPointRequest $request, array $args = []) Queries the information about an access point.
 * @method Models\GetAccessPointPolicyResult getAccessPointPolicy(Models\GetAccessPointPolicyRequest $request, array $args = []) Queries the configurations of an access point policy.
 * @method \GuzzleHttp\Promise\Promise getAccessPointPolicyAsync(Models\GetAccessPointPolicyRequest $request, array $args = []) Queries the configurations of an access point policy.
 * @method Models\DeleteAccessPointPolicyResult deleteAccessPointPolicy(Models\DeleteAccessPointPolicyRequest $request, array $args = []) Deletes an access point policy.
 * @method \GuzzleHttp\Promise\Promise deleteAccessPointPolicyAsync(Models\DeleteAccessPointPolicyRequest $request, array $args = []) Deletes an access point policy.
 * @method Models\PutAccessPointPolicyResult putAccessPointPolicy(Models\PutAccessPointPolicyRequest $request, array $args = []) Configures an access point policy.
 * @method \GuzzleHttp\Promise\Promise putAccessPointPolicyAsync(Models\PutAccessPointPolicyRequest $request, array $args = []) Configures an access point policy.
 * @method Models\DeleteAccessPointResult deleteAccessPoint(Models\DeleteAccessPointRequest $request, array $args = []) Deletes an access point.
 * @method \GuzzleHttp\Promise\Promise deleteAccessPointAsync(Models\DeleteAccessPointRequest $request, array $args = []) Deletes an access point.
 * @method Models\CreateAccessPointResult createAccessPoint(Models\CreateAccessPointRequest $request, array $args = []) Creates an access point.
 * @method \GuzzleHttp\Promise\Promise createAccessPointAsync(Models\CreateAccessPointRequest $request, array $args = []) Creates an access point.
 * @method Models\PutBucketRtcResult putBucketRtc(Models\PutBucketRtcRequest $request, array $args = []) Enables or disables the Replication Time Control (RTC) feature for existing cross-region replication (CRR) rules.
 * @method \GuzzleHttp\Promise\Promise putBucketRtcAsync(Models\PutBucketRtcRequest $request, array $args = []) Enables or disables the Replication Time Control (RTC) feature for existing cross-region replication (CRR) rules.
 * @method Models\PutBucketReplicationResult putBucketReplication(Models\PutBucketReplicationRequest $request, array $args = []) Configures data replication rules for a bucket. Object Storage Service (OSS) supports cross-region replication (CRR) and same-region replication (SRR).
 * @method \GuzzleHttp\Promise\Promise putBucketReplicationAsync(Models\PutBucketReplicationRequest $request, array $args = []) Configures data replication rules for a bucket. Object Storage Service (OSS) supports cross-region replication (CRR) and same-region replication (SRR).
 * @method Models\GetBucketReplicationResult getBucketReplication(Models\GetBucketReplicationRequest $request, array $args = []) Queries the data replication rules configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketReplicationAsync(Models\GetBucketReplicationRequest $request, array $args = []) Queries the data replication rules configured for a bucket.
 * @method Models\GetBucketReplicationLocationResult getBucketReplicationLocation(Models\GetBucketReplicationLocationRequest $request, array $args = []) Queries the regions in which available destination buckets reside. You can determine the region of the destination bucket to which the data in the source bucket are replicated based on the returned response.
 * @method \GuzzleHttp\Promise\Promise getBucketReplicationLocationAsync(Models\GetBucketReplicationLocationRequest $request, array $args = []) Queries the regions in which available destination buckets reside. You can determine the region of the destination bucket to which the data in the source bucket are replicated based on the returned response.
 * @method Models\GetBucketReplicationProgressResult getBucketReplicationProgress(Models\GetBucketReplicationProgressRequest $request, array $args = []) Queries the information about the data replication process of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketReplicationProgressAsync(Models\GetBucketReplicationProgressRequest $request, array $args = []) Queries the information about the data replication process of a bucket.
 * @method Models\DeleteBucketReplicationResult deleteBucketReplication(Models\DeleteBucketReplicationRequest $request, array $args = []) Disables data replication for a bucket and deletes the data replication rule configured for the bucket. After you call this operation, all operations performed on the source bucket are not synchronized to the destination bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketReplicationAsync(Models\DeleteBucketReplicationRequest $request, array $args = []) Disables data replication for a bucket and deletes the data replication rule configured for the bucket. After you call this operation, all operations performed on the source bucket are not synchronized to the destination bucket.
 * @method Models\ListCloudBoxesResult listCloudBoxes(Models\ListCloudBoxesRequest $request, array $args = []) Lists cloud box buckets that belong to the current account.
 * @method \GuzzleHttp\Promise\Promise listCloudBoxesAsync(Models\ListCloudBoxesRequest $request, array $args = []) Lists cloud box buckets that belong to the current account.
 * @method Models\ListBucketDataRedundancyTransitionResult listBucketDataRedundancyTransition(Models\ListBucketDataRedundancyTransitionRequest $request, array $args = []) Lists all redundancy type conversion tasks of a bucket.
 * @method \GuzzleHttp\Promise\Promise listBucketDataRedundancyTransitionAsync(Models\ListBucketDataRedundancyTransitionRequest $request, array $args = []) Lists all redundancy type conversion tasks of a bucket.
 * @method Models\ListUserDataRedundancyTransitionResult listUserDataRedundancyTransition(Models\ListUserDataRedundancyTransitionRequest $request, array $args = []) Lists all redundancy type conversion tasks.
 * @method \GuzzleHttp\Promise\Promise listUserDataRedundancyTransitionAsync(Models\ListUserDataRedundancyTransitionRequest $request, array $args = []) Lists all redundancy type conversion tasks.
 * @method Models\GetBucketDataRedundancyTransitionResult getBucketDataRedundancyTransition(Models\GetBucketDataRedundancyTransitionRequest $request, array $args = []) Queries the redundancy type conversion tasks of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketDataRedundancyTransitionAsync(Models\GetBucketDataRedundancyTransitionRequest $request, array $args = []) Queries the redundancy type conversion tasks of a bucket.
 * @method Models\CreateBucketDataRedundancyTransitionResult createBucketDataRedundancyTransition(Models\CreateBucketDataRedundancyTransitionRequest $request, array $args = []) Creates a redundancy type conversion task for a bucket.
 * @method \GuzzleHttp\Promise\Promise createBucketDataRedundancyTransitionAsync(Models\CreateBucketDataRedundancyTransitionRequest $request, array $args = []) Creates a redundancy type conversion task for a bucket.
 * @method Models\DeleteBucketDataRedundancyTransitionResult deleteBucketDataRedundancyTransition(Models\DeleteBucketDataRedundancyTransitionRequest $request, array $args = []) Deletes a redundancy type conversion task of a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketDataRedundancyTransitionAsync(Models\DeleteBucketDataRedundancyTransitionRequest $request, array $args = []) Deletes a redundancy type conversion task of a bucket.
 * @method Models\GetBucketHttpsConfigResult getBucketHttpsConfig(Models\GetBucketHttpsConfigRequest $request, array $args = []) Queries the Transport Layer Security (TLS) version configurations of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketHttpsConfigAsync(Models\GetBucketHttpsConfigRequest $request, array $args = []) Queries the Transport Layer Security (TLS) version configurations of a bucket.
 * @method Models\PutBucketHttpsConfigResult putBucketHttpsConfig(Models\PutBucketHttpsConfigRequest $request, array $args = []) Enables or disables Transport Layer Security (TLS) version management for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketHttpsConfigAsync(Models\PutBucketHttpsConfigRequest $request, array $args = []) Enables or disables Transport Layer Security (TLS) version management for a bucket.
 * @method Models\GetBucketResourceGroupResult getBucketResourceGroup(Models\GetBucketResourceGroupRequest $request, array $args = []) Queries the ID of the resource group to which a bucket belongs.
 * @method \GuzzleHttp\Promise\Promise getBucketResourceGroupAsync(Models\GetBucketResourceGroupRequest $request, array $args = []) Queries the ID of the resource group to which a bucket belongs.
 * @method Models\PutBucketResourceGroupResult putBucketResourceGroup(Models\PutBucketResourceGroupRequest $request, array $args = []) Modifies the ID of the resource group to which a bucket belongs.
 * @method \GuzzleHttp\Promise\Promise putBucketResourceGroupAsync(Models\PutBucketResourceGroupRequest $request, array $args = []) Modifies the ID of the resource group to which a bucket belongs.
 * @method Models\PutStyleResult putStyle(Models\PutStyleRequest $request, array $args = []) Adds an image style to a bucket. An image style contains one or more image processing parameters.
 * @method \GuzzleHttp\Promise\Promise putStyleAsync(Models\PutStyleRequest $request, array $args = []) Adds an image style to a bucket. An image style contains one or more image processing parameters.
 * @method Models\ListStyleResult listStyle(Models\ListStyleRequest $request, array $args = []) Queries all image styles that are created for a bucket.
 * @method \GuzzleHttp\Promise\Promise listStyleAsync(Models\ListStyleRequest $request, array $args = []) Queries all image styles that are created for a bucket.
 * @method Models\GetStyleResult getStyle(Models\GetStyleRequest $request, array $args = []) Queries the information about an image style of a bucket.
 * @method \GuzzleHttp\Promise\Promise getStyleAsync(Models\GetStyleRequest $request, array $args = []) Queries the information about an image style of a bucket.
 * @method Models\DeleteStyleResult deleteStyle(Models\DeleteStyleRequest $request, array $args = []) Deletes an image style from a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteStyleAsync(Models\DeleteStyleRequest $request, array $args = []) Deletes an image style from a bucket.
 * @method Models\PutBucketInventoryResult putBucketInventory(Models\PutBucketInventoryRequest $request, array $args = []) Configures an inventory for a bucket.
 * @method \GuzzleHttp\Promise\Promise putBucketInventoryAsync(Models\PutBucketInventoryRequest $request, array $args = []) Configures an inventory for a bucket.
 * @method Models\GetBucketInventoryResult getBucketInventory(Models\GetBucketInventoryRequest $request, array $args = []) Queries the inventories that are configured for a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketInventoryAsync(Models\GetBucketInventoryRequest $request, array $args = []) Queries the inventories that are configured for a bucket.
 * @method Models\ListBucketInventoryResult listBucketInventory(Models\ListBucketInventoryRequest $request, array $args = []) Queries all inventories in a bucket at a time.
 * @method \GuzzleHttp\Promise\Promise listBucketInventoryAsync(Models\ListBucketInventoryRequest $request, array $args = []) Queries all inventories in a bucket at a time.
 * @method Models\DeleteBucketInventoryResult deleteBucketInventory(Models\DeleteBucketInventoryRequest $request, array $args = []) Deletes an inventory for a bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketInventoryAsync(Models\DeleteBucketInventoryRequest $request, array $args = []) Deletes an inventory for a bucket.
 * @method Models\PutBucketOverwriteConfigResult putBucketOverwriteConfig(Models\PutBucketOverwriteConfigRequest $request, array $args = []) Call the PutBucketOverwriteConfig operation to configure overwrite protection for a bucket. This prevents specified objects from being overwritten.
 * @method \GuzzleHttp\Promise\Promise putBucketOverwriteConfigAsync(Models\PutBucketOverwriteConfigRequest $request, array $args = []) Call the PutBucketOverwriteConfig operation to configure overwrite protection for a bucket. This prevents specified objects from being overwritten.
 * @method Models\GetBucketOverwriteConfigResult getBucketOverwriteConfig(Models\GetBucketOverwriteConfigRequest $request, array $args = []) Call the GetBucketOverwriteConfig operation to retrieve the overwrite configuration of a bucket.
 * @method \GuzzleHttp\Promise\Promise getBucketOverwriteConfigAsync(Models\GetBucketOverwriteConfigRequest $request, array $args = []) Call the GetBucketOverwriteConfig operation to retrieve the overwrite configuration of a bucket.
 * @method Models\DeleteBucketOverwriteConfigResult deleteBucketOverwriteConfig(Models\DeleteBucketOverwriteConfigRequest $request, array $args = []) Delete overwrite configuration rule for the bucket.
 * @method \GuzzleHttp\Promise\Promise deleteBucketOverwriteConfigAsync(Models\DeleteBucketOverwriteConfigRequest $request, array $args = []) Delete overwrite configuration rule for the bucket.
 */
final class Client
{
    use ClientExtensionTrait;

    /**
     * @var ClientImpl
     */
    private ClientImpl $client;

    /**
     * Client constructor.
     * @param Config $config
     * @param array $options
     */
    public function __construct(Config $config, array $options = [])
    {
        $this->client = new ClientImpl($config, $options);
    }

    /**
     * @param OperationInput $input
     * @param array $options
     * @return OperationOutput
     */
    public function invokeOperation(OperationInput $input, array $options = []): OperationOutput
    {
        return $this->client->executeAsync($input, $options)->wait();
    }

    /**
     * @param OperationInput $input
     * @param array $options
     * @return GuzzleHttp\Promise\Promise
     */
    public function invokeOperationAsync(OperationInput $input, array $options = []): GuzzleHttp\Promise\Promise
    {
        return $this->client->executeAsync($input, $options);
    }

    /**
     * @param string $name The api name.
     * @param array $args
     * @return GuzzleHttp\Promise\PromiseInterface|mixed
     */
    public function __call($name, $args)
    {
        if (substr($name, -5) === 'Async') {
            $name = substr($name, 0, -5);
            $isAsync = true;
        }

        // api name
        $opName = ucfirst($name);
        $class = Transform\Functions::getTransformClass($opName);
        $fromFunc = "from$opName";
        $toFunc = "to$opName";

        if (
            !\method_exists($class, $fromFunc) ||
            !\method_exists($class, $toFunc)
        ) {
            throw new \BadMethodCallException('Not implement ' . self::class . '::' . $name);
        }

        // args, {Operation}Request request, array options
        $request = isset($args[0]) ? $args[0] : [];
        $options = count($args) > 1 ? $args[1] : [];

        if (!($request instanceof Types\RequestModel)) {
            throw new \InvalidArgumentException('args[0] is not subclass of RequestModel, got ' . \gettype($request));
        }

        if (!\is_array($options)) {
            $options = [];
        }

        // execute
        $input = call_user_func([$class, $fromFunc], $request);
        $promise = $this->client->executeAsync($input, $options)->then(
            function (OperationOutput $output) use ($toFunc, $class) {
                return call_user_func([$class, $toFunc], $output);
            }
        );

        // result
        return !empty($isAsync) ? $promise : $promise->wait();
    }

    /**
     * Generates the presigned URL.
     * If you do not specify expires or expiration, the pre-signed URL uses 15 minutes as default.
     * @param Models\GetObjectRequest|Models\PutObjectRequest|Models\HeadObjectRequest|Models\InitiateMultipartUploadRequest|Models\UploadPartRequest|Models\CompleteMultipartUploadRequest|Models\AbortMultipartUploadRequest $request The request for the Presign operation.
     * @param array $args accepts the following:
     * - expires \DateInterval: The expiration duration for the generated presign url.
     *   For example, ['expires' => \DateInterval::createFromDateString('1 day')]
     * - expiration \DateTime: The expiration time for the generated presign url.
     *   For example, ['expiration' => \DateTime::createFromFormat(''Y-m-d\TH:i:s\Z'', '2024-12-03T03:30:19Z', new \DateTimeZone('UTC')]
     * @return Models\PresignResult
     * @throws \Exception
     */
    public function presign($request, $args = []): Models\PresignResult
    {
        if (!($request instanceof Types\RequestModel)) {
            throw new \InvalidArgumentException(
                '$request is not subclass of RequestModel, got ' . \gettype($request)
            );
        }
        $validRequestTypes = [
            Models\GetObjectRequest::class,
            Models\PutObjectRequest::class,
            Models\HeadObjectRequest::class,
            Models\InitiateMultipartUploadRequest::class,
            Models\UploadPartRequest::class,
            Models\CompleteMultipartUploadRequest::class,
            Models\AbortMultipartUploadRequest::class,
        ];

        if (!in_array(get_class($request), $validRequestTypes, true)) {
            throw new \InvalidArgumentException("Invalid request type: " . get_class($request));
        }
        $name = \get_class($request);
        $pos = \strrpos($name, '\\');
        $opName = \substr($name, $pos + 1, -7);
        $class = Transform\Functions::getTransformClass($opName);
        $fromFunc = "from$opName";

        // prepare
        $input = call_user_func([$class, $fromFunc], $request);

        $options = $args;
        $options['sdk_presign'] = true;
        $options['auth_method'] = 'query';
        if (isset($args['expiration']) && ($args['expiration'] instanceof \DateTime)) {
            $input->setOpMetadata('expiration_time', $args['expiration']);
        } else if (isset($args['expires']) && ($args['expires'] instanceof \DateInterval)) {
            $expirationTime = (new \DateTime('now', new \DateTimeZone('UTC')))->add($args['expires']);
            $input->setOpMetadata('expiration_time', $expirationTime);
        }

        // execute
        $result = $this->client->presignInner($input, $options);

        return new Models\PresignResult(
            $result['method'],
            $result['url'],
            $result['expiration'] ?? null,
            $result['signedHeaders'] ?? null
        );
    }
}
