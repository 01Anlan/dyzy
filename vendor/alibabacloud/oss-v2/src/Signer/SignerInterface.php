<?php

declare(strict_types=1);

namespace AlibabaCloud\Oss\V2\Signer;

interface SignerInterface
{
    const OSS_HEADER_PREFIX = "x-oss-";
    const AUTHORIZATION_HEADER = "Authorization";
    const SECURITY_TOKEN_HEADER = "x-oss-security-token";
    const DATE_HEADER = "Date";
    const CONTENT_TYPE_HEADER = "Content-Type";
    const CONTENT_MD5_HEADER = "Content-MD5";
    const OSS_DATE_HEADER = "x-oss-date";

    const SECURITY_TOKEN_QUERY = "security-token";
    const EXPIRES_QUERY = "Expires";
    const ACCESS_KEY_ID_QUERY = "OSSAccessKeyId";
    const SIGNATURE_QUERY = "Signature";
    const DEFAULT_EXPIRES_DURATION = 900;

    public function sign(SigningContext $signingCtx);
}
