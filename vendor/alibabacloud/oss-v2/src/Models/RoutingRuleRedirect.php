<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Models;

use AlibabaCloud\Oss\V2\Types\Model;
use AlibabaCloud\Oss\V2\Annotation\XmlElement;
use AlibabaCloud\Oss\V2\Annotation\XmlRoot;

/**
 * Class RoutingRuleRedirect
 * @package AlibabaCloud\Oss\V2\Models
 */
#[XmlRoot(name: 'RoutingRuleRedirect')]
final class RoutingRuleRedirect extends Model
{
    /**
     * Is it transmitted transparently '/' to the source site.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorPassOriginalSlashes', type: 'bool')]
    public ?bool $mirrorPassOriginalSlashes;

    /**
     * The redirection type. Valid values:*   Mirror: mirroring-based back-to-origin.*   External: external redirection. OSS returns an HTTP 3xx status code and returns an address for you to redirect to.*   AliCDN: redirection based on Alibaba Cloud CDN. Compared with external redirection, OSS adds an additional header to the request. After Alibaba Cloud CDN identifies the header, Alibaba Cloud CDN redirects the access to the specified address and returns the obtained data instead of the HTTP 3xx status code that redirects the access to another address.  This parameter must be specified if Redirect is specified.
     * @var string|null
     */
    #[XmlElement(rename: 'RedirectType', type: 'string')]
    public ?string $redirectType;

    /**
     * The origin URL for mirroring-based back-to-origin. This parameter takes effect only when the value of RedirectType is Mirror. The origin URL must start with \*\*http:// or https://\*\* and end with a forward slash (/). OSS adds an object name to the end of the URL to generate a back-to-origin URL. For example, the name of the object to access is myobject. If MirrorURL is set to `http://example.com/`, the back-to-origin URL is `http://example.com/myobject`. If MirrorURL is set to `http://example.com/dir1/`, the back-to-origin URL is `http://example.com/dir1/myobject`.  This parameter must be specified if RedirectType is set to Mirror.Valid values:*   true            *   false
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorURL', type: 'string')]
    public ?string $mirrorURL;

    /**
     * This parameter plays the same role as PassQueryString and has a higher priority than PassQueryString. This parameter takes effect only when the value of RedirectType is Mirror. Default value: false.Valid values:*   true            *   false
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorPassQueryString', type: 'bool')]
    public ?bool $mirrorPassQueryString;

    /**
     * Specifies whether to check the MD5 hash of the body of the response returned by the origin. This parameter takes effect only when the value of RedirectType is Mirror. When MirrorCheckMd5 is set to true and the response returned by the origin includes the Content-Md5 header, OSS checks whether the MD5 hash of the obtained data matches the header value. If the MD5 hash of the obtained data does not match the header value, the obtained data is not stored in OSS. Default value: false.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorCheckMd5', type: 'bool')]
    public ?bool $mirrorCheckMd5;

    /**
     * Is SNI transparent.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorSNI', type: 'bool')]
    public ?bool $mirrorSNI;

    /**
     * The protocol used for redirection. This parameter takes effect only when RedirectType is set to External or AliCDN. For example, if you access an object named test, Protocol is set to https, and Hostname is set to `example.com`, the value of the Location header is `https://example.com/test`. Valid values: http and https.
     * @var string|null
     */
    #[XmlElement(rename: 'Protocol', type: 'string')]
    public ?string $protocol;

    /**
     * The string that is used to replace the prefix of the object name during redirection. If the prefix of an object name is empty, the string precedes the object name.  You can specify only one of the ReplaceKeyWith and ReplaceKeyPrefixWith parameters in a rule. For example, if you access an object named abc/test.txt, KeyPrefixEquals is set to abc/, ReplaceKeyPrefixWith is set to def/, the value of the Location header is `http://example.com/def/test.txt`.
     * @var string|null
     */
    #[XmlElement(rename: 'ReplaceKeyPrefixWith', type: 'string')]
    public ?string $replaceKeyPrefixWith;

    /**
     * Specifies whether to redirect the access to the address specified by Location if the origin returns an HTTP 3xx status code. This parameter takes effect only when the value of RedirectType is Mirror. For example, when a mirroring-based back-to-origin request is initiated, the origin returns 302 and Location is specified.*   If you set MirrorFollowRedirect to true, OSS continues requesting the resource at the address specified by Location. The access can be redirected up to 10 times. If the access is redirected more than 10 times, the mirroring-based back-to-origin request fails.*   If you set MirrorFollowRedirect to false, OSS returns 302 and passes through Location.Default value: true.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorFollowRedirect', type: 'bool')]
    public ?bool $mirrorFollowRedirect;

    /**
     * The domain name used for redirection. The domain name must comply with the domain naming rules. For example, if you access an object named test, Protocol is set to https, and Hostname is set to `example.com`, the value of the Location header is `https://example.com/test`.
     * @var string|null
     */
    #[XmlElement(rename: 'HostName', type: 'string')]
    public ?string $hostName;

    /**
     * The headers contained in the response that is returned when you use mirroring-based back-to-origin. This parameter takes effect only when the value of RedirectType is Mirror.
     * @var MirrorHeaders|null
     */
    #[XmlElement(rename: 'MirrorHeaders', type: MirrorHeaders::class)]
    public ?MirrorHeaders $mirrorHeaders;

    /**
     * Specifies whether to include parameters of the original request in the redirection request when the system runs the redirection rule or mirroring-based back-to-origin rule. For example, if the PassQueryString parameter is set to true, the `?a=b&c=d` parameter string is included in a request sent to OSS, and the redirection mode is 302, this parameter is added to the Location header. For example, if the request is `Location:example.com?a=b&c=d` and the redirection type is mirroring-based back-to-origin, the ?a=b\&c=d parameter string is also included in the back-to-origin request. Valid values: true and false (default).
     * @var bool|null
     */
    #[XmlElement(rename: 'PassQueryString', type: 'bool')]
    public ?bool $passQueryString;

    /**
     * If this parameter is set to true, the prefix of the object names is replaced with the value specified by ReplaceKeyPrefixWith. If this parameter is not specified or empty, the prefix of object names is truncated.  When the ReplaceKeyWith parameter is not empty, the EnableReplacePrefix parameter cannot be set to true.Default value: false.
     * @var bool|null
     */
    #[XmlElement(rename: 'EnableReplacePrefix', type: 'bool')]
    public ?bool $enableReplacePrefix;

    /**
     * The string that is used to replace the requested object name when the request is redirected. This parameter can be set to the ${key} variable, which indicates the object name in the request. For example, if ReplaceKeyWith is set to `prefix/${key}.suffix` and the object to access is test, the value of the Location header is `http://example.com/prefix/test.suffix`.
     * @var string|null
     */
    #[XmlElement(rename: 'ReplaceKeyWith', type: 'string')]
    public ?string $replaceKeyWith;

    /**
     * The HTTP redirect code in the response. This parameter takes effect only when RedirectType is set to External or AliCDN. Valid values: 301, 302, and 307.
     * @var int|null
     */
    #[XmlElement(rename: 'HttpRedirectCode', type: 'int')]
    public ?int $httpRedirectCode;

    /**
     * The slave URL for mirror-based back-to-origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorURLSlave', type: 'string')]
    public ?string $mirrorURLSlave;

    /**
     * The probe URL for mirror-based back-to-origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorURLProbe', type: 'string')]
    public ?string $mirrorURLProbe;

    /**
     * Whether to store the user defined metadata in mirror-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorSaveOssMeta', type: 'bool')]
    public ?bool $mirrorSaveOssMeta;

    /**
     * Not save data in web-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorProxyPass', type: 'bool')]
    public ?bool $mirrorProxyPass;

    /**
     * Whether to allow get image information in mirror-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorAllowGetImageInfo', type: 'bool')]
    public ?bool $mirrorAllowGetImageInfo;

    /**
     * Whether to allow take video snapshot in mirror-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorAllowVideoSnapshot', type: 'bool')]
    public ?bool $mirrorAllowVideoSnapshot;

    /**
     * Mirror-based back-to-origin with express tunnel.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorIsExpressTunnel', type: 'bool')]
    public ?bool $mirrorIsExpressTunnel;

    /**
     * The VPC region for mirror-based back-to-origin express tunnel.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorDstRegion', type: 'string')]
    public ?string $mirrorDstRegion;

    /**
     * The VPC ID for mirror-based back-to-origin express tunnel.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorDstVpcId', type: 'string')]
    public ?string $mirrorDstVpcId;

    /**
     * The slave VPC ID for mirror-based back-to-origin express tunnel.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorDstSlaveVpcId', type: 'string')]
    public ?string $mirrorDstSlaveVpcId;

    /**
     * Use LastModifiedTime of the file from origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorUserLastModified', type: 'bool')]
    public ?bool $mirrorUserLastModified;

    /**
     * Used for determining the state of primary-secondary switching.
     * The logic for primary-secondary switching is based on the error code returned by the origin server.
     * If MirrorSwitchAllErrors is set to true, all status codes except the following are considered failures: 200, 206, 301, 302, 303, 307, 404.
     * If it is set to false, only status codes in the 5xx range or timeouts are considered failures.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorSwitchAllErrors', type: 'bool')]
    public ?bool $mirrorSwitchAllErrors;

    /**
     * The tunnel ID for mirror-based back-to-origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorTunnelId', type: 'string')]
    public ?string $mirrorTunnelId;

    /**
     * Whether to use role for mirror-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorUsingRole', type: 'bool')]
    public ?bool $mirrorUsingRole;

    /**
     * The role name used for mirror-based back-to-origin.
     * @var string|null
     */
    #[XmlElement(rename: 'MirrorRole', type: 'string')]
    public ?string $mirrorRole;

    /**
     * Whether to allow take HeadObject in mirror-based back-to-origin.
     * @var bool|null
     */
    #[XmlElement(rename: 'MirrorAllowHeadObject', type: 'bool')]
    public ?bool $mirrorAllowHeadObject;

    /**
     * Specify which status codes returned by the origin server should be passed through to the client along with the body.
     * The value should be HTTP status codes such as 4xx, 5xx, etc., separated by commas (,), for example, 400,404.
     * This setting takes effect only when RedirectType is set to Mirror.
     * When OSS requests content from the origin server, if the origin server returns one of the status codes specified in this parameter,
     * OSS will pass through the status code and body returned by the origin server to the client. If the 404 status code is specified in this parameter,
     * the configured ErrorDocument will be ineffective.
     * @var string|null
     */
    #[XmlElement(rename: 'TransparentMirrorResponseCodes', type: 'string')]
    public ?string $transparentMirrorResponseCodes;

    /**
     * The HTTP status codes that trigger the asynchronous pull mode in mirror-based back-to-origin.
     * @var int|null
     */
    #[XmlElement(rename: 'MirrorAsyncStatus', type: 'int')]
    public ?int $mirrorAsyncStatus;

    /**
     * The rules for setting tags when saving files during mirror-based back-to-origin.
     * @var MirrorTaggings|null
     */
    #[XmlElement(rename: 'MirrorTaggings', type: MirrorTaggings::class)]
    public ?MirrorTaggings $mirrorTaggings;

    /**
     * Container to store the rules for setting response headers in mirror-based back-to-origin.
     * @var MirrorReturnHeaders|null
     */
    #[XmlElement(rename: 'MirrorReturnHeaders', type: MirrorReturnHeaders::class)]
    public ?MirrorReturnHeaders $mirrorReturnHeaders;

    /**
     * The authentication information for the origin server in mirror-based back-to-origin.
     * @var MirrorAuth|null
     */
    #[XmlElement(rename: 'MirrorAuth', type: MirrorAuth::class)]
    public ?MirrorAuth $mirrorAuth;

    /**
     * The container to store the configuration for multiple origins in mirror-based back-to-origin.
     * @var MirrorMultiAlternates|null
     */
    #[XmlElement(rename: 'MirrorMultiAlternates', type: MirrorMultiAlternates::class)]
    public ?MirrorMultiAlternates $mirrorMultiAlternates;

    /**
     * RoutingRuleRedirect constructor.
     * @param bool|null $mirrorPassOriginalSlashes Is it transmitted transparently '/' to the source site.
     * @param string|null $redirectType The redirection type.
     * @param string|null $mirrorURL The origin URL for mirroring-based back-to-origin.
     * @param bool|null $mirrorPassQueryString This parameter plays the same role as PassQueryString and has a higher priority than PassQueryString.
     * @param bool|null $mirrorCheckMd5 Specifies whether to check the MD5 hash of the body of the response returned by the origin.
     * @param bool|null $mirrorSNI Is SNI transparent.
     * @param string|null $protocol The protocol used for redirection.
     * @param string|null $replaceKeyPrefixWith The string that is used to replace the prefix of the object name during redirection.
     * @param bool|null $mirrorFollowRedirect Specifies whether to redirect the access to the address specified by Location if the origin returns an HTTP 3xx status code.
     * @param string|null $hostName The domain name used for redirection.
     * @param MirrorHeaders|null $mirrorHeaders The headers contained in the response that is returned when you use mirroring-based back-to-origin.
     * @param bool|null $passQueryString Specifies whether to include parameters of the original request in the redirection request when the system runs the redirection rule or mirroring-based back-to-origin rule.
     * @param bool|null $enableReplacePrefix If this parameter is set to true, the prefix of the object names is replaced with the value specified by ReplaceKeyPrefixWith.
     * @param string|null $replaceKeyWith The string that is used to replace the requested object name when the request is redirected.
     * @param int|null $httpRedirectCode The HTTP redirect code in the response.
     * @param string|null $mirrorURLSlave The slave URL for mirror-based back-to-origin.
     * @param string|null $mirrorURLProbe The probe URL for mirror-based back-to-origin.
     * @param bool|null $mirrorSaveOssMeta Whether to store the user defined metadata in mirror-based back-to-origin.
     * @param bool|null $mirrorProxyPass Not save data in web-based back-to-origin.
     * @param bool|null $mirrorAllowGetImageInfo Whether to allow get image information in mirror-based back-to-origin.
     * @param bool|null $mirrorAllowVideoSnapshot Whether to allow take video snapshot in mirror-based back-to-origin.
     * @param bool|null $mirrorIsExpressTunnel Mirror-based back-to-origin with express tunnel.
     * @param string|null $mirrorDstRegion The VPC region for mirror-based back-to-origin express tunnel.
     * @param string|null $mirrorDstVpcId The VPC ID for mirror-based back-to-origin express tunnel.
     * @param string|null $mirrorDstSlaveVpcId The slave VPC ID for mirror-based back-to-origin express tunnel.
     * @param bool|null $mirrorUserLastModified Use LastModifiedTime of the file from origin.
     * @param bool|null $mirrorSwitchAllErrors Used for determining the state of primary-secondary switching.
     * @param string|null $mirrorTunnelId The tunnel ID for mirror-based back-to-origin.
     * @param bool|null $mirrorUsingRole Whether to use role for mirror-based back-to-origin.
     * @param string|null $mirrorRole The role name used for mirror-based back-to-origin.
     * @param bool|null $mirrorAllowHeadObject Whether to allow take HeadObject in mirror-based back-to-origin.
     * @param string|null $transparentMirrorResponseCodes Specify which status codes returned by the origin server should be passed through to the client along with the body.
     * @param int|null $mirrorAsyncStatus The HTTP status codes that trigger the asynchronous pull mode in mirror-based back-to-origin.
     * @param MirrorTaggings|null $mirrorTaggings The rules for setting tags when saving files during mirror-based back-to-origin.
     * @param MirrorReturnHeaders|null $mirrorReturnHeaders Container to store the rules for setting response headers in mirror-based back-to-origin.
     * @param MirrorAuth|null $mirrorAuth The authentication information for the origin server in mirror-based back-to-origin.
     * @param MirrorMultiAlternates|null $mirrorMultiAlternates The container to store the configuration for multiple origins in mirror-based back-to-origin.
     */
    public function __construct(
        ?bool $mirrorPassOriginalSlashes = null,
        ?string $redirectType = null,
        ?string $mirrorURL = null,
        ?bool $mirrorPassQueryString = null,
        ?bool $mirrorCheckMd5 = null,
        ?bool $mirrorSNI = null,
        ?string $protocol = null,
        ?string $replaceKeyPrefixWith = null,
        ?bool $mirrorFollowRedirect = null,
        ?string $hostName = null,
        ?MirrorHeaders $mirrorHeaders = null,
        ?bool $passQueryString = null,
        ?bool $enableReplacePrefix = null,
        ?string $replaceKeyWith = null,
        ?int $httpRedirectCode = null,
        ?string $mirrorURLSlave = null,
        ?string $mirrorURLProbe = null,
        ?bool $mirrorSaveOssMeta = null,
        ?bool $mirrorProxyPass = null,
        ?bool $mirrorAllowGetImageInfo = null,
        ?bool $mirrorAllowVideoSnapshot = null,
        ?bool $mirrorIsExpressTunnel = null,
        ?string $mirrorDstRegion = null,
        ?string $mirrorDstVpcId = null,
        ?string $mirrorDstSlaveVpcId = null,
        ?bool $mirrorUserLastModified = null,
        ?bool $mirrorSwitchAllErrors = null,
        ?string $mirrorTunnelId = null,
        ?bool $mirrorUsingRole = null,
        ?string $mirrorRole = null,
        ?bool $mirrorAllowHeadObject = null,
        ?string $transparentMirrorResponseCodes = null,
        ?int $mirrorAsyncStatus = null,
        ?MirrorTaggings $mirrorTaggings = null,
        ?MirrorReturnHeaders $mirrorReturnHeaders = null,
        ?MirrorAuth $mirrorAuth = null,
        ?MirrorMultiAlternates $mirrorMultiAlternates = null
    )
    {
        $this->mirrorPassOriginalSlashes = $mirrorPassOriginalSlashes;
        $this->redirectType = $redirectType;
        $this->mirrorURL = $mirrorURL;
        $this->mirrorPassQueryString = $mirrorPassQueryString;
        $this->mirrorCheckMd5 = $mirrorCheckMd5;
        $this->mirrorSNI = $mirrorSNI;
        $this->protocol = $protocol;
        $this->replaceKeyPrefixWith = $replaceKeyPrefixWith;
        $this->mirrorFollowRedirect = $mirrorFollowRedirect;
        $this->hostName = $hostName;
        $this->mirrorHeaders = $mirrorHeaders;
        $this->passQueryString = $passQueryString;
        $this->enableReplacePrefix = $enableReplacePrefix;
        $this->replaceKeyWith = $replaceKeyWith;
        $this->httpRedirectCode = $httpRedirectCode;
        $this->mirrorURLSlave = $mirrorURLSlave;
        $this->mirrorURLProbe = $mirrorURLProbe;
        $this->mirrorSaveOssMeta = $mirrorSaveOssMeta;
        $this->mirrorProxyPass = $mirrorProxyPass;
        $this->mirrorAllowGetImageInfo = $mirrorAllowGetImageInfo;
        $this->mirrorAllowVideoSnapshot = $mirrorAllowVideoSnapshot;
        $this->mirrorIsExpressTunnel = $mirrorIsExpressTunnel;
        $this->mirrorDstRegion = $mirrorDstRegion;
        $this->mirrorDstVpcId = $mirrorDstVpcId;
        $this->mirrorDstSlaveVpcId = $mirrorDstSlaveVpcId;
        $this->mirrorUserLastModified = $mirrorUserLastModified;
        $this->mirrorSwitchAllErrors = $mirrorSwitchAllErrors;
        $this->mirrorTunnelId = $mirrorTunnelId;
        $this->mirrorUsingRole = $mirrorUsingRole;
        $this->mirrorRole = $mirrorRole;
        $this->mirrorAllowHeadObject = $mirrorAllowHeadObject;
        $this->transparentMirrorResponseCodes = $transparentMirrorResponseCodes;
        $this->mirrorAsyncStatus = $mirrorAsyncStatus;
        $this->mirrorTaggings = $mirrorTaggings;
        $this->mirrorReturnHeaders = $mirrorReturnHeaders;
        $this->mirrorAuth = $mirrorAuth;
        $this->mirrorMultiAlternates = $mirrorMultiAlternates;
    }
}