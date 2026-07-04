<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\ResultModel;

/**
 * The result for the GetBucketStat operation.
 * Class GetBucketStatResult
 * @package AlibabaCloud\Oss\V2\Models
 */
final class GetBucketStatResult extends ResultModel
{
    /**
     * The storage usage of the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $storage;

    /**
     * The total number of objects in the bucket.
     * @var int|null
     */
    public ?int $objectCount;

    /**
     * The number of multipart upload tasks that have been initiated but are not completed or canceled.
     * @var int|null
     */
    public ?int $multipartUploadCount;

    /**
     * The number of multipart parts in the bucket.
     * @var int|null
     */
    public ?int $multipartPartCount;

    /**
     * The number of LiveChannels in the bucket.
     * @var int|null
     */
    public ?int $liveChannelCount;

    /**
     * The time when the obtained information was last modified. The value of this parameter is a UNIX timestamp. Unit: seconds.
     * @var int|null
     */
    public ?int $lastModifiedTime;

    /**
     * The storage usage of Standard objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $standardStorage;

    /**
     * The number of Standard objects in the bucket.
     * @var int|null
     */
    public ?int $standardObjectCount;

    /**
     * The billed storage usage of IA objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $infrequentAccessStorage;
    /**
     * The actual storage usage of IA objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $infrequentAccessRealStorage;

    /**
     * The number of IA objects in the bucket.
     * @var int|null
     */
    public ?int $infrequentAccessObjectCount;

    /**
     * The billed storage usage of Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $archiveStorage;

    /**
     * The actual storage usage of Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $archiveRealStorage;

    /**
     * The number of Archive objects in the bucket.
     * @var int|null
     */
    public ?int $archiveObjectCount;

    /**
     * The billed storage usage of Cold Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $coldArchiveStorage;

    /**
     * The actual storage usage of Cold Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $coldArchiveRealStorage;

    /**
     * The number of Cold Archive objects in the bucket.
     * @var int|null
     */
    public ?int $coldArchiveObjectCount;

    /**
     * The billed storage usage of Deep Cold Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $deepColdArchiveStorage;

    /**
     * The actual storage usage of Deep Cold Archive objects in the bucket. Unit: bytes.
     * @var int|null
     */
    public ?int $deepColdArchiveRealStorage;

    /**
     * The number of Deep Cold Archive objects in the bucket.
     * @var int|null
     */
    public ?int $deepColdArchiveObjectCount;

    /**
     * The number of delete marker in the bucket.
     * @var int|null
     */
    public ?int $deleteMarkerCount;


    /**
     * GetBucketStatResult constructor.
     * @param int|null $storage The storage usage of the bucket.
     * @param int|null $objectCount The total number of objects in the bucket.
     * @param int|null $multipartUploadCount The number of multipart upload tasks that have been initiated but are not completed or canceled.
     * @param int|null $multipartPartCount The number of multipart parts in the bucket.
     * @param int|null $liveChannelCount The number of LiveChannels in the bucket.
     * @param int|null $lastModifiedTime The time when the obtained information was last modified.
     * @param int|null $standardStorage The storage usage of Standard objects in the bucket.
     * @param int|null $standardObjectCount The number of Standard objects in the bucket.
     * @param int|null $infrequentAccessStorage The billed storage usage of IA objects in the bucket.
     * @param int|null $infrequentAccessRealStorage The actual storage usage of IA objects in the bucket.
     * @param int|null $infrequentAccessObjectCount The number of IA objects in the bucket.
     * @param int|null $archiveStorage The billed storage usage of Archive objects in the bucket.
     * @param int|null $archiveRealStorage The actual storage usage of Archive objects in the bucket.
     * @param int|null $archiveObjectCount The number of Archive objects in the bucket.
     * @param int|null $coldArchiveStorage The actual storage usage of Cold Archive objects in the bucket.
     * @param int|null $coldArchiveRealStorage The billed storage usage of Cold Archive objects in the bucket.
     * @param int|null $coldArchiveObjectCount The number of Cold Archive objects in the bucket.
     * @param int|null $deepColdArchiveStorage The billed storage usage of Deep Cold Archive objects in the bucket.
     * @param int|null $deepColdArchiveRealStorage The actual storage usage of Deep Cold Archive objects in the bucket.
     * @param int|null $deepColdArchiveObjectCount The number of Deep Cold Archive objects in the bucket.
     * @param int|null $deleteMarkerCount The number of delete marker in the bucket.
     */
    public function __construct(
        ?int $storage = null,
        ?int $objectCount = null,
        ?int $multipartUploadCount = null,
        ?int $multipartPartCount = null,
        ?int $liveChannelCount = null,
        ?int $lastModifiedTime = null,
        ?int $standardStorage = null,
        ?int $standardObjectCount = null,
        ?int $infrequentAccessStorage = null,
        ?int $infrequentAccessRealStorage = null,
        ?int $infrequentAccessObjectCount = null,
        ?int $archiveStorage = null,
        ?int $archiveRealStorage = null,
        ?int $archiveObjectCount = null,
        ?int $coldArchiveStorage = null,
        ?int $coldArchiveRealStorage = null,
        ?int $coldArchiveObjectCount = null,
        ?int $deepColdArchiveStorage = null,
        ?int $deepColdArchiveRealStorage = null,
        ?int $deepColdArchiveObjectCount = null,
        ?int $deleteMarkerCount = null
    )
    {
        $this->storage = $storage;
        $this->objectCount = $objectCount;
        $this->multipartUploadCount = $multipartUploadCount;
        $this->multipartPartCount = $multipartPartCount;
        $this->liveChannelCount = $liveChannelCount;
        $this->lastModifiedTime = $lastModifiedTime;
        $this->standardStorage = $standardStorage;
        $this->standardObjectCount = $standardObjectCount;
        $this->infrequentAccessStorage = $infrequentAccessStorage;
        $this->infrequentAccessRealStorage = $infrequentAccessRealStorage;
        $this->infrequentAccessObjectCount = $infrequentAccessObjectCount;
        $this->archiveStorage = $archiveStorage;
        $this->archiveRealStorage = $archiveRealStorage;
        $this->archiveObjectCount = $archiveObjectCount;
        $this->coldArchiveStorage = $coldArchiveStorage;
        $this->coldArchiveRealStorage = $coldArchiveRealStorage;
        $this->coldArchiveObjectCount = $coldArchiveObjectCount;
        $this->deepColdArchiveStorage = $deepColdArchiveStorage;
        $this->deepColdArchiveRealStorage = $deepColdArchiveRealStorage;
        $this->deepColdArchiveObjectCount = $deepColdArchiveObjectCount;
        $this->deleteMarkerCount = $deleteMarkerCount;
    }
}
