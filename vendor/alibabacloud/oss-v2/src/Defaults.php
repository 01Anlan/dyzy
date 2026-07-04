<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2;

final class Defaults
{
    /**
     *Default transport 's connect timeout is 10, the unit is seconod
     */
    const CONNECT_TIMEOUT = 10.0;

    /**
     *Default transport 's request timeout is 20, the unit is seconod
     */
    const READWRITE_TIMEOUT = 20.0;

    /**
     *Default signature version is v4
     */

    const SIGNATURE_VERSION = "v4";

    /**
     *Product for signing
     */
    const PRODUCT = "oss";

    /**
     *The URL's scheme, default is https
     */
    const ENDPOINT_SCHEME = "https";

    const MAX_ATTEMPTS = 3;
    const MAX_BACKOFF_S = 20.0;
    const BASE_DELAY_S = 0.2;

    /**
     * Default part size, 6MiB
     */
    const DEFAULT_PART_SIZE = 6 * 1024 * 1024;

    /**
     * Default part size for uploader uploads data
     */
    const DEFAULT_UPLOAD_PART_SIZE = self::DEFAULT_PART_SIZE;

    /**
     * Default part size for downloader downloads object
     */
    const DEFAULT_DOWNLOAD_PART_SIZE = self::DEFAULT_PART_SIZE;

    /**
     * Default part size for copier copys object, 64M
     */
    const DEFAULT_COPY_PART_SIZE = 64 * 1024 * 1024;

    /**
     * Default parallel
     */
    const DEFAULT_PARALLEL = 3;

    /**
     * Default parallel for uploader uploads data
     */
    const DEFAULT_UPLOAD_PARALLEL = self::DEFAULT_PARALLEL;

    /**
     * Default parallel for downloader downloads object
     */
    const DEFAULT_DOWNLOAD_PARALLEL = self::DEFAULT_PARALLEL;

    /**
     * Default parallel for downloader downloads object
     */
    const DEFAULT_COPY_PARALLEL = self::DEFAULT_PARALLEL;

    /**
     * Default threshold to use muitipart copy in Copier, 200M
     */
    const DEFAULT_COPY_THRESHOLD = 200 * 1024 * 1024;

    /**
     * Temp file suffix
     */
    const DEFAULT_TEMP_FILE_SUFFIX = '.temp';

    /**
     * Temp file suffix
     */
    const MAX_UPLOAD_PARTS = 10000;

    /**
     * Checkpoint file suffix for Downloader
     */
    const CHECKPOINT_FILE_SUFFIX_UPLOADER = '.ucp';

    /**
     * Checkpoint file suffix for Downloader
     */
    const CHECKPOINT_FILE_SUFFIX_DOWNLOADER = '.dcp';

    /**
     * Checkpoint file Magic
     */
    const CHECKPOINT_MAGIC = '92611BED-89E2-46B6-89E5-72F273D4B0A3';

    /**
     *  CloudBoxProduct Product of cloud box for signing
     */
    const CLOUD_BOX_PRODUCT = "oss-cloudbox";
}
