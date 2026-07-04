<?php
namespace AlibabaCloud\Oss\V2\Credentials;

/**
 * Interface for providing Credential.
 */
interface CredentialsProvider
{
    /**
     * Return a Credentials instance if it successfully retrieved the value.
     */    
    public function getCredentials(): Credentials;
}