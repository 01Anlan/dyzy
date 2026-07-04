<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

use AlibabaCloud\Oss\V2\Models;
use AlibabaCloud\Oss\V2\Exception;

/**
 * A trait providing extension functionality.
 * Trait ClientExtensionTrait
 * @package AlibabaCloud\Oss\V2
 */
trait ClientExtensionTrait
{
    /**
     * Determines whether or not a bucket exists by name.
     * This method uses GetBucketAcl operation.
     *
     * @param string $bucket The name of the bucket
     * @param array $args
     * @return bool
     */
    public function isBucketExist(string $bucket, array $args = []): bool
    {
        try {
            $result = $this->getBucketAcl(new Models\GetBucketAclRequest(
                $bucket
            ), $args);
        } catch (Exception\OperationException $e) {
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                return $se->getErrorCode() !== "NoSuchBucket";
            }
            // an unhandled exception
            throw $e;
        }

        return $result != null;
    }

    /**
     * Determines whether or not a object exists by name.
     * This method uses GetObjectMeta operation.
     *
     * @param string $bucket The name of the bucket
     * @param string $key The name of the key
     * @param string|null $versionId The version id of the object.
     * @param array $args
     * @return bool
     */
    public function isObjectExist(string $bucket, string $key, ?string $versionId = null, array $args = []): bool
    {
        try {
            $result = $this->getObjectMeta(new Models\GetObjectMetaRequest(
                $bucket,
                $key,
                $versionId
            ), $args);
        } catch (Exception\OperationException $e) {
            $se = $e->getPrevious();
            if ($se instanceof Exception\ServiceException) {
                $errorCode = $se->getErrorCode();
                if (
                    $errorCode === "NoSuchKey" ||
                    ($errorCode === "BadErrorResponse" && $se->getStatusCode() === 404)
                ) {
                    return false;
                }
            }
            // an unhandled exception
            throw $e;
        }

        return $result != null;
    }

    /**
     * Creates uploader.
     * The uploader calls the multipart upload operation to split a large local file or
     * stream into multiple smaller parts and upload the parts in parallel to improve upload performance.
     *
     * @param array $args accepts the following:
     * - part_size: (int) The part size. Default value: 6 MiB.
     * - parallel_num: (int) The number of the upload tasks in parallel.
     *   Default value: 3.
     * - leave_parts_on_error: (bool) Specifies whether to retain the uploaded parts when an upload task fails.
     *   By default, the uploaded parts are not retained.
     * @return Uploader
     */
    public function newUploader(array $args = []): Uploader
    {
        return new Uploader($this, $args);
    }

    /**
     * Creates downloader.
     * The Downloader uses range download to split a large object into multiple smaller parts
     * and download the parts in parallel to improve download performance.
     *
     * @param array $args accepts the following:
     * - part_size: (int) The part size. Default value: 6 MiB.
     * - parallel_num: (int) The number of the download tasks in parallel.
     *   Default value: 3.
     * - use_temp_file: (bool) Specifies whether to use a temporary file when you download an object.
     * @return Downloader
     */
    public function newDownloader(array $args = []): Downloader
    {
        return new Downloader($this, $args);
    }

    /**
     * Creates Copier.
     * Copier provides common copy operations, hides the differences and implementation details of the operations,
     * and automatically selects the appropriate operation to copy objects
     * according to the request parameters of the copy task.
     *
     * @param array $args accepts the following:
     * - part_size int: The part size. Default value: 64 MiB.
     * - parallel_num int: The number of the upload tasks in parallel. Default value: 3.
     * - multipart_copy_threshold int: The minimum object size for calling the multipart copy operation.
     *   Default value: 200 MiB.
     * - leave_parts_on_error bool: Specifies whether to retain the uploaded parts when an upload task fails.
     *   By default, the uploaded parts are not retained.
     * - disable_shallow_copy bool: Specifies that the shallow copy capability is not used.
     *   By default, the shallow copy capability is used.
     * @return Copier
     */
    public function newCopier(array $args = []): Copier
    {
        return new Copier($this, $args);
    }

    /**
     * @param Models\GetObjectRequest $request The get object request.
     * @param string $filePath The file path name.
     * @param array $args
     * @return Models\GetObjectResult
     * @throws Exception\OperationException
     */
    public function getObjectToFile(Models\GetObjectRequest $request, string $filePath, array $args = []): Models\GetObjectResult
    {
        return $this->getObjectToFileAsync($request, $filePath, $args)->wait();
    }

    /**
     * Get object to local file.
     * @param Models\GetObjectRequest $request The get object request.
     * @param string $filePath The file path name.
     * @param array $args
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @throws Exception\OperationException
     */
    public function getObjectToFileAsync(Models\GetObjectRequest $request, string $filePath, array $args = [])
    {
        return $this->getObjectAsync(
            $request,
            array_merge($args, [
                'request_options' => array_merge(
                    $args['request_options'] ?? [],
                    ['sink' => $filePath]
                )
            ])

        );
    }

    /**
     * Put Object from local file.
     * @param Models\PutObjectRequest $request
     * @param string $filePath
     * @param array $args
     * @return Models\PutObjectResult
     * @throws Exception\OperationException
     */
    public function putObjectFromFile(Models\PutObjectRequest $request, string $filePath, array $args = []): Models\PutObjectResult
    {
        return $this->putObjectFromFileAsync($request, $filePath, $args)->wait();
    }

    /**
     * Put Object from local file.
     * @param Models\PutObjectRequest $request
     * @param string $filePath
     * @param array $args
     * @return \GuzzleHttp\Promise\Promise
     * @throws Exception\OperationException
     */
    public function putObjectFromFileAsync(Models\PutObjectRequest $request, string $filePath, array $args = [])
    {
        $stream = new \GuzzleHttp\Psr7\LazyOpenStream($filePath, 'rb');
        $stream->seek(0);
        $req = clone $request;
        $req->body = $stream;
        if (empty($req->contentType)) {
            $req->contentType = Utils::guessContentType($filePath, 'application/octet-stream');
        }
        return $this->putObjectAsync($req, $args);
    }
}
