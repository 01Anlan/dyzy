<?php
declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\RequestModel;
use AlibabaCloud\Oss\V2\Annotation\TagProperty;
use AlibabaCloud\Oss\V2\Annotation\RequiredProperty;

/**
 * The request for the OptionObject operation.
 * Class OptionObjectRequest
 * @package AlibabaCloud\Oss\V2\Models
 */
final class OptionObjectRequest extends RequestModel
{   
    /** 
     * The name of the bucket.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'host', rename: 'bucket', type: 'string')]
    public ?string $bucket;

    /** 
     * The full path of the object.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'path', rename: 'key', type: 'string')]
    public ?string $key;

    /** 
     * The origin of the request. It is used to identify a cross-origin request. You can specify only one Origin header in a cross-origin request. By default, this header is left empty.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'header', rename: 'Origin', type: 'string')]
    public ?string $origin;

    /** 
     * The method to be used in the actual cross-origin request. You can specify only one Access-Control-Request-Method header in a cross-origin request. By default, this header is left empty.
     * @var string|null
     */
    #[RequiredProperty()]
    #[TagProperty(tag: '', position: 'header', rename: 'Access-Control-Request-Method', type: 'string')]
    public ?string $accessControlRequestMethod;

    /** 
     * The custom headers to be sent in the actual cross-origin request. You can configure multiple custom headers in a cross-origin request. Custom headers are separated by commas (,). By default, this header is left empty.
     * @var string|null
     */
    #[TagProperty(tag: '', position: 'header', rename: 'Access-Control-Request-Headers', type: 'string')]
    public ?string $accessControlRequestHeaders;

    /**
     * OptionObjectRequest constructor.
     * @param string|null $bucket The name of the bucket.
     * @param string|null $key The full path of the object.
     * @param string|null $origin The origin of the request.
     * @param string|null $accessControlRequestMethod The method to be used in the actual cross-origin request.
     * @param string|null $accessControlRequestHeaders The custom headers to be sent in the actual cross-origin request.
     * @param array|null $options
     */
    public function __construct( 
        ?string $bucket = null,
        ?string $key = null,
        ?string $origin = null,
        ?string $accessControlRequestMethod = null,
        ?string $accessControlRequestHeaders = null,
        ?array $options = null
    )
    {   
        $this->bucket = $bucket;
        $this->key = $key;
        $this->origin = $origin;
        $this->accessControlRequestMethod = $accessControlRequestMethod;
        $this->accessControlRequestHeaders = $accessControlRequestHeaders;
        parent::__construct($options);
    }
}