<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class Upload
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'Upload')]
final class Upload extends Model
{
    /**
     * The name of the object for which a multipart upload task was initiated.  The results returned by OSS are listed in ascending alphabetical order of object names. Multiple multipart upload tasks that are initiated to upload the same object are listed in ascending order of upload IDs.
     * @var string|null
     */
    #[XmlElement(rename: 'Key', type: 'string')]
    public ?string $key;

    /**
     * The ID of the multipart upload task.
     * @var string|null
     */
    #[XmlElement(rename: 'UploadId', type: 'string')]
    public ?string $uploadId;

    /**
     * The time when the multipart upload task was initiated.
     * @var \DateTime|null
     */
    #[XmlElement(rename: 'Initiated', type: 'datetime')]
    public ?\DateTime $initiated;

    /**
     * Upload constructor.
     *
     * @param string|null $key The name of the object for which a multipart upload task was initiated.
     * @param string|null $uploadId The ID of the multipart upload task.
     * @param \DateTime|null $initiated The time when the multipart upload task was initiated.
     */
    public function __construct(
        ?string $key = null,
        ?string $uploadId = null,
        ?\DateTime $initiated = null
    )
    {
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->initiated = $initiated;
    }
}
