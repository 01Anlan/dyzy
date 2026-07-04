<?php
namespace AlibabaCloud\Oss\V2\Credentials;

/**
*Obtaining credentials from environment variables.
*OSS_ACCESS_KEY_ID
*OSS_ACCESS_KEY_SECRET
*OSS_SESSION_TOKEN (Optional)
 */
class EnvironmentVariableCredentialsProvider implements CredentialsProvider
{
    public function getCredentials(): Credentials
    {
        $ak = getenv('OSS_ACCESS_KEY_ID');
        $sk = getenv('OSS_ACCESS_KEY_SECRET');
        $token = getenv('OSS_SESSION_TOKEN');
        return new Credentials($ak, $sk, $token);
    }
}

