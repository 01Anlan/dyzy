<?php

namespace AlibabaCloud\Oss\V2\Credentials;

/**
 *Access OSS anonymously.
 */
class AnonymousCredentialsProvider implements CredentialsProvider
{
    public function getCredentials(): Credentials
    {
        return new Credentials('', '');
    }
}
