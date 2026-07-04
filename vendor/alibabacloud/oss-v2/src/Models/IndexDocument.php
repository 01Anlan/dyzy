<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class IndexDocument
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'IndexDocument')]
final class IndexDocument extends Model
{
    /**
     * The default homepage.
     * @var string|null
     */
    #[XmlElement(rename: 'Suffix', type: 'string')]
    public ?string $suffix;

    /**
     * Specifies whether to redirect the access to the default homepage of the subdirectory when the subdirectory is accessed. Valid values:*   true: The access is redirected to the default homepage of the subdirectory.*   false (default): The access is redirected to the default homepage of the root directory.For example, the default homepage is set to index.html, and `bucket.oss-cn-hangzhou.aliyuncs.com/subdir/` is the site that you want to access. If SupportSubDir is set to false, the access is redirected to `bucket.oss-cn-hangzhou.aliyuncs.com/index.html`. If SupportSubDir is set to true, the access is redirected to `bucket.oss-cn-hangzhou.aliyuncs.com/subdir/index.html`.
     * @var bool|null
     */
    #[XmlElement(rename: 'SupportSubDir', type: 'bool')]
    public ?bool $supportSubDir;

    /**
     * The operation to perform when the default homepage is set, the name of the accessed object does not end with a forward slash (/), and the object does not exist. This parameter takes effect only when SupportSubDir is set to true. It takes effect after RoutingRule but before ErrorFile. For example, the default homepage is set to index.html, `bucket.oss-cn-hangzhou.aliyuncs.com/abc` is the site that you want to access, and the abc object does not exist. In this case, different operations are performed based on the value of Type.*   0 (default): OSS checks whether the object named abc/index.html, which is in the `Object + Forward slash (/) + Homepage` format, exists. If the object exists, OSS returns HTTP status code 302 and the Location header value that contains URL-encoded `/abc/`. The URL-encoded /abc/ is in the `Forward slash (/) + Object + Forward slash (/)` format. If the object does not exist, OSS returns HTTP status code 404 and continues to check ErrorFile.*   1: OSS returns HTTP status code 404 and the NoSuchKey error code and continues to check ErrorFile.*   2: OSS checks whether abc/index.html exists. If abc/index.html exists, the content of the object is returned. If abc/index.html does not exist, OSS returns HTTP status code 404 and continues to check ErrorFile.
     * @var int|null
     */
    #[XmlElement(rename: 'Type', type: 'int')]
    public ?int $type;


    /**
     * IndexDocument constructor.
     * @param string|null $suffix The default homepage.
     * @param bool|null $supportSubDir Specifies whether to redirect the access to the default homepage of the subdirectory when the subdirectory is accessed.
     * @param int|null $type The operation to perform when the default homepage is set, the name of the accessed object does not end with a forward slash (/), and the object does not exist.
     */
    public function __construct(
        ?string $suffix = null,
        ?bool $supportSubDir = null,
        ?int $type = null
    )
    {
        $this->suffix = $suffix;
        $this->supportSubDir = $supportSubDir;
        $this->type = $type;
    }
}