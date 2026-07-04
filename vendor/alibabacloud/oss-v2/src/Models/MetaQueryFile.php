<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class MetaQueryFile
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'File')]
final class MetaQueryFile extends Model
{
    /**
     * The full path of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'Filename', type: 'string')]
    public ?string $filename;

    /**
     * The time when the object was last modified.
     * @var string|null
     */
    #[XmlElement(rename: 'FileModifiedTime', type: 'string')]
    public ?string $fileModifiedTime;

    /**
     * The storage class of the object.
     * Valid values:* Archive : the Archive storage class .
     * ColdArchive : the Cold Archive storage class .
     * IA : the Infrequent Access (IA) storage class .
     * Standard : The Standard storage class .
     * @var string|null
     */
    #[XmlElement(rename: 'OSSStorageClass', type: 'string')]
    public ?string $ossStorageClass;

    /**
     * The ETag of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'ETag', type: 'string')]
    public ?string $etag;

    /**
     * The CRC-64 value of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'OSSCRC64', type: 'string')]
    public ?string $ossCrc64;

    /**
     * The number of the tags of the object.
     * @var int|null
     */
    #[XmlElement(rename: 'OSSTaggingCount', type: 'int')]
    public ?int $ossTaggingCount;

    /**
     * The object size.
     * @var int|null
     */
    #[XmlElement(rename: 'Size', type: 'int')]
    public ?int $size;

    /**
     * The type of the object.
     * Valid values:* Multipart : The object is uploaded by using multipart upload .
     * Symlink : The object is a symbolic link that was created by calling the PutSymlink operation.
     * Appendable : The object is uploaded by using AppendObject .
     * Normal : The object is uploaded by using PutObject.
     * @var string|null
     */
    #[XmlElement(rename: 'OSSObjectType', type: 'string')]
    public ?string $ossObjectType;

    /**
     * The access control list (ACL) of the object.
     * Valid values:
     * default:the ACL of the bucket.
     * public :public.
     * public-read :public-read.
     * public-read-write:public-read-write.
     * @var string|null
     */
    #[XmlElement(rename: 'ObjectACL', type: 'string')]
    public ?string $objectACL;

    /**
     * The server-side encryption of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'ServerSideEncryption', type: 'string')]
    public ?string $serverSideEncryption;

    /**
     * The server-side encryption algorithm used when the object was created.
     * @var string|null
     */
    #[XmlElement(rename: 'ServerSideEncryptionCustomerAlgorithm', type: 'string')]
    public ?string $serverSideEncryptionCustomerAlgorithm;

    /**
     * The tags.
     * @var MetaQueryOssTagging|null
     */
    #[XmlElement(rename: 'OSSTagging', type: MetaQueryOssTagging::class)]
    public ?MetaQueryOssTagging $ossTagging;

    /**
     * The user metadata items.
     * @var MetaQueryOssUserMeta|null
     */
    #[XmlElement(rename: 'OSSUserMeta', type: MetaQueryOssUserMeta::class)]
    public ?MetaQueryOssUserMeta $ossUserMeta;

    /**
     * The list of audio streams.
     * @var MetaQueryAudioStreams|null
     */
    #[XmlElement(rename: 'AudioStreams', type: MetaQueryAudioStreams::class)]
    public ?MetaQueryAudioStreams $audioStreams;

    /**
     * The algorithm used to encrypt objects.
     * @var string|null
     */
    #[XmlElement(rename: 'ServerSideDataEncryption', type: 'string')]
    public ?string $serverSideDataEncryption;

    /**
     * The cross-origin request methods that are allowed.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessControlRequestMethod', type: 'string')]
    public ?string $accessControlRequestMethod;

    /**
     * The artist.
     * @var string|null
     */
    #[XmlElement(rename: 'Artist', type: 'string')]
    public ?string $artist;

    /**
     * The total duration of the video. Unit: seconds.
     * @var string|null
     */
    #[XmlElement(rename: 'Duration', type: 'string')]
    public ?string $duration;

    /**
     * The longitude and latitude information.
     * @var string|null
     */
    #[XmlElement(rename: 'LatLong', type: 'string')]
    public ?string $latLong;

    /**
     * The width of the image. Unit: pixel.
     * @var int|null
     */
    #[XmlElement(rename: 'ImageWidth', type: 'int')]
    public ?int $imageWidth;

    /**
     * The composer.
     * @var string|null
     */
    #[XmlElement(rename: 'Composer', type: 'string')]
    public ?string $composer;

    /**
     * The player.
     * @var string|null
     */
    #[XmlElement(rename: 'Performer', type: 'string')]
    public ?string $performer;

    /**
     * The type of multimedia.
     * @var string|null
     */
    #[XmlElement(rename: 'MediaType', type: 'string')]
    public ?string $mediaType;

    /**
     * The title of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'Title', type: 'string')]
    public ?string $title;

    /**
     * The width of the video image. Unit: pixel.
     * @var int|null
     */
    #[XmlElement(rename: 'VideoWidth', type: 'int')]
    public ?int $videoWidth;

    /**
     * The time when the object expires.
     * @var string|null
     */
    #[XmlElement(rename: 'OSSExpiration', type: 'string')]
    public ?string $ossExpiration;

    /**
     * The language of the object content.
     * @var string|null
     */
    #[XmlElement(rename: 'ContentLanguage', type: 'string')]
    public ?string $contentLanguage;

    /**
     * The singer.
     * @var string|null
     */
    #[XmlElement(rename: 'AlbumArtist', type: 'string')]
    public ?string $albumArtist;

    /**
     * The list of video streams.
     * @var MetaQueryVideoStreams|null
     */
    #[XmlElement(rename: 'VideoStreams', type: MetaQueryVideoStreams::class)]
    public ?MetaQueryVideoStreams $videoStreams;

    /**
     * The list of subtitle streams.
     * @var MetaQuerySubtitles|null
     */
    #[XmlElement(rename: 'Subtitles', type: MetaQuerySubtitles::class)]
    public ?MetaQuerySubtitles $subtitles;

    /**
     * The origins allowed in cross-origin requests.
     * @var string|null
     */
    #[XmlElement(rename: 'AccessControlAllowOrigin', type: 'string')]
    public ?string $accessControlAllowOrigin;

    /**
     * The album.
     * @var string|null
     */
    #[XmlElement(rename: 'Album', type: 'string')]
    public ?string $album;

    /**
     * The web page caching behavior that is performed when the object is downloaded.
     * @var string|null
     */
    #[XmlElement(rename: 'CacheControl', type: 'string')]
    public ?string $cacheControl;

    /**
     * The content encoding format of the object when the object is downloaded.
     * @var string|null
     */
    #[XmlElement(rename: 'ContentEncoding', type: 'string')]
    public ?string $contentEncoding;

    /**
     * The full path of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'URI', type: 'string')]
    public ?string $uri;

    /**
     * The height of the image. Unit: pixel.
     * @var int|null
     */
    #[XmlElement(rename: 'ImageHeight', type: 'string')]
    public ?int $imageHeight;

    /**
     * The height of the video image. Unit: pixel.
     * @var int|null
     */
    #[XmlElement(rename: 'VideoHeight', type: 'int')]
    public ?int $videoHeight;

    /**
     * The bitrate. Unit: bit/s.
     * @var int|null
     */
    #[XmlElement(rename: 'Bitrate', type: 'int')]
    public ?int $bitrate;

    /**
     * The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @var string|null
     */
    #[XmlElement(rename: 'ServerSideEncryptionKeyId', type: 'string')]
    public ?string $serverSideEncryptionKeyId;

    /**
     * The Multipurpose Internet Mail Extensions (MIME) type of the object.
     * @var string|null
     */
    #[XmlElement(rename: 'ContentType', type: 'string')]
    public ?string $contentType;

    /**
     * The time when the image or video was taken.
     * @var string|null
     */
    #[XmlElement(rename: 'ProduceTime', type: 'string')]
    public ?string $produceTime;

    /**
     * The name of the object when it is downloaded.
     * @var string|null
     */
    #[XmlElement(rename: 'ContentDisposition', type: 'string')]
    public ?string $contentDisposition;

    /**
     * The addresses.
     * @var MetaQueryAddresses|null
     */
    #[XmlElement(rename: 'Addresses', type: MetaQueryAddresses::class)]
    public ?MetaQueryAddresses $addresses;

    /**
     * MetaQueryFile constructor.
     * @param string|null $filename The full path of the object.
     * @param string|null $fileModifiedTime The time when the object was last modified.
     * @param string|null $ossStorageClass The storage class of the object.
     * @param string|null $etag The ETag of the object.
     * @param string|null $ossCrc64 The CRC-64 value of the object.
     * @param int|null $ossTaggingCount The number of the tags of the object.
     * @param int|null $size The object size.
     * @param string|null $ossObjectType The type of the object.
     * @param string|null $objectACL The access control list (ACL) of the object.
     * @param string|null $serverSideEncryption The server-side encryption of the object.
     * @param string|null $serverSideEncryptionCustomerAlgorithm The server-side encryption algorithm used when the object was created.
     * @param OSSTagging|null $ossTagging The tags.
     * @param MetaQueryOssUserMeta|null $ossUserMeta The user metadata items.
     * @param string|null $accessControlAllowOrigin The origins allowed in cross-origin requests.
     * @param string|null $album The album.
     * @param string|null $uri The full path of the object.
     * @param string|null $cacheControl The web page caching behavior that is performed when the object is downloaded.
     * @param string|null $contentEncoding The content encoding format of the object when the object is downloaded.
     * @param int|null $videoHeight The height of the video image.
     * @param int|null $bitrate The bitrate.
     * @param string|null $serverSideDataEncryption The algorithm used to encrypt objects.
     * @param string|null $serverSideEncryptionKeyId The ID of the customer master key (CMK) that is managed by Key Management Service (KMS).
     * @param string|null $contentDisposition The name of the object when it is downloaded.
     * @param MetaQuerySubtitles|null $subtitles
     * @param MetaQueryAddresses|null $addresses The addresses.
     * @param string|null $contentType The Multipurpose Internet Mail Extensions (MIME) type of the object.
     * @param string|null $produceTime The time when the image or video was taken.
     * @param int|null $imageHeight The height of the image.
     * @param string|null $artist The singer.
     * @param string|null $latLong The longitude and latitude information.
     * @param int|null $imageWidth The width of the image.
     * @param string|null $composer The composer.
     * @param string|null $performer The player.
     * @param string|null $mediaType The type of multimedia.
     * @param string|null $title The title of the object.
     * @param int|null $videoWidth The width of the video image.
     * @param MetaQueryAudioStreams|null $audioStreams The list of video streams.
     * @param string|null $ossExpiration The time when the object expires.
     * @param string|null $accessControlRequestMethod The cross-origin request methods that are allowed.
     * @param string|null $contentLanguage The language of the object content.
     * @param string|null $albumArtist The singer.
     * @param float|null $duration The duration of the audio stream in seconds.
     * @param MetaQueryVideoStreams|null $videoStreams The list of video streams.
     */
    public function __construct(
        ?string $filename = null,
        ?string $fileModifiedTime = null,
        ?string $ossStorageClass = null,
        ?string $etag = null,
        ?string $ossCrc64 = null,
        ?int $ossTaggingCount = null,
        ?int $size = null,
        ?string $ossObjectType = null,
        ?string $objectACL = null,
        ?string $serverSideEncryption = null,
        ?string $serverSideEncryptionCustomerAlgorithm = null,
        ?OSSTagging $ossTagging = null,
        ?MetaQueryOssUserMeta $ossUserMeta = null,
        ?string $accessControlAllowOrigin = null,
        ?string $album = null,
        ?string $uri = null,
        ?string $cacheControl = null,
        ?string $contentEncoding = null,
        ?int $videoHeight = null,
        ?int $bitrate = null,
        ?string $serverSideDataEncryption = null,
        ?string $serverSideEncryptionKeyId = null,
        ?string $contentDisposition = null,
        ?MetaQuerySubtitles $subtitles = null,
        ?MetaQueryAddresses $addresses = null,
        ?string $contentType = null,
        ?string $produceTime = null,
        ?int $imageHeight = null,
        ?string $artist = null,
        ?string $latLong = null,
        ?int $imageWidth = null,
        ?string $composer = null,
        ?string $performer = null,
        ?string $mediaType = null,
        ?string $title = null,
        ?int $videoWidth = null,
        ?MetaQueryAudioStreams $audioStreams = null,
        ?string $ossExpiration = null,
        ?string $accessControlRequestMethod = null,
        ?string $contentLanguage = null,
        ?string $albumArtist = null,
        ?float $duration = null,
        ?MetaQueryVideoStreams $videoStreams = null
    )
    {
        $this->filename = $filename;
        $this->fileModifiedTime = $fileModifiedTime;
        $this->ossStorageClass = $ossStorageClass;
        $this->etag = $etag;
        $this->ossCrc64 = $ossCrc64;
        $this->ossTaggingCount = $ossTaggingCount;
        $this->size = $size;
        $this->ossObjectType = $ossObjectType;
        $this->objectACL = $objectACL;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->serverSideEncryptionCustomerAlgorithm = $serverSideEncryptionCustomerAlgorithm;
        $this->ossTagging = $ossTagging;
        $this->ossUserMeta = $ossUserMeta;
        $this->accessControlAllowOrigin = $accessControlAllowOrigin;
        $this->album = $album;
        $this->serverSideEncryptionCustomerAlgorithm = $serverSideEncryptionCustomerAlgorithm;
        $this->uri = $uri;
        $this->cacheControl = $cacheControl;
        $this->contentEncoding = $contentEncoding;
        $this->videoHeight = $videoHeight;
        $this->bitrate = $bitrate;
        $this->ossCrc64 = $ossCrc64;
        $this->serverSideDataEncryption = $serverSideDataEncryption;
        $this->serverSideEncryptionKeyId = $serverSideEncryptionKeyId;
        $this->contentDisposition = $contentDisposition;
        $this->subtitles = $subtitles;
        $this->addresses = $addresses;
        $this->ossObjectType = $ossObjectType;
        $this->contentType = $contentType;
        $this->produceTime = $produceTime;
        $this->imageHeight = $imageHeight;
        $this->artist = $artist;
        $this->ossStorageClass = $ossStorageClass;
        $this->objectACL = $objectACL;
        $this->latLong = $latLong;
        $this->imageWidth = $imageWidth;
        $this->composer = $composer;
        $this->performer = $performer;
        $this->serverSideEncryption = $serverSideEncryption;
        $this->mediaType = $mediaType;
        $this->ossTagging = $ossTagging;
        $this->title = $title;
        $this->videoWidth = $videoWidth;
        $this->audioStreams = $audioStreams;
        $this->filename = $filename;
        $this->ossTaggingCount = $ossTaggingCount;
        $this->etag = $etag;
        $this->ossUserMeta = $ossUserMeta;
        $this->ossExpiration = $ossExpiration;
        $this->accessControlRequestMethod = $accessControlRequestMethod;
        $this->contentLanguage = $contentLanguage;
        $this->albumArtist = $albumArtist;
        $this->size = $size;
        $this->fileModifiedTime = $fileModifiedTime;
        $this->duration = $duration;
        $this->videoStreams = $videoStreams;
    }
}